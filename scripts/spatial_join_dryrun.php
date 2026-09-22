<?php
// Spatial join dry-run: assign UMKM points to polygons from GeoJSON (peta-lokasi-kaltim/ADMINISTRASIDESA_AR_250K.json)
// This script does NOT change the database. It writes CSV of candidate assignments: storage/app/exports/spatial_assign_candidates_YYYYMMDD_HHMMSS.csv

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Umkm;
use Illuminate\Support\Facades\Storage;

$geoPath = __DIR__ . '/../peta-lokasi-kaltim/ADMINISTRASIDESA_AR_250K.json';
if (!file_exists($geoPath)) { echo "GeoJSON not found: $geoPath\n"; exit(1); }

// Load UMKM points (id, lat, lng) - where location is not null
$umkms = [];
\App\Models\Umkm::withCoordinates()->whereNotNull('location')->chunk(1000, function($rows) use (&$umkms){
    foreach($rows as $r){
        $lat = isset($r->latitude)?(float)$r->latitude:null;
        $lng = isset($r->longitude)?(float)$r->longitude:null;
        if ($lat !== null && $lng !== null) {
            $umkms[$r->id] = ['id'=>$r->id,'lat'=>$lat,'lng'=>$lng];
        }
    }
});

$totalPoints = count($umkms);
echo "Loaded {$totalPoints} UMKM points\n";
if ($totalPoints === 0) { exit(0); }

// Prepare output CSV
$now = (new DateTime())->format('Ymd_His');
$outRel = "exports/spatial_assign_candidates_{$now}.csv";
$outPath = __DIR__ . "/../storage/app/{$outRel}";
@mkdir(dirname($outPath), 0777, true);
$outHandle = fopen($outPath, 'w');
fputcsv($outHandle, ['umkm_id','umkm_lat','umkm_lng','feature_index','feature_objectid','feature_namobj','matched_ring_index']);

$fp = fopen($geoPath,'r');
if (!$fp) { echo "Cannot open geojson\n"; exit(1); }

$assigned = 0;
$featureIndex = -1;
$readPos = 0;
$bufferSize = 8192;
$partial = '';

// We'll stream-read and look for occurrences of '{"type":"Feature"' to decode each feature object
// Simpler: read file in chunks and accumulate; extract features by regex finding '},{"type":"Feature"' boundaries - but safer to parse by tracking braces when encountering '"type"\s*:\s*"Feature"'

$contents = '';
while (!feof($fp)){
    $chunk = fread($fp, $bufferSize);
    if ($chunk === false) break;
    $contents .= $chunk;
}
fclose($fp);

// Try to locate the features array body
$start = strpos($contents, '"features"');
if ($start === false) { echo "No features key found\n"; exit(1); }
$startBrace = strpos($contents, '[', $start);
$endPos = strrpos($contents, ']');
if ($startBrace === false || $endPos === false || $endPos <= $startBrace) { echo "Cannot locate features array\n"; exit(1); }
$featuresJson = substr($contents, $startBrace, $endPos - $startBrace + 1);

// Now we try to split features by top-level braces. We'll iterate through the string and extract each feature JSON object by brace matching.
$len = strlen($featuresJson);
$idx = 0;
while ($idx < $len) {
    // skip whitespace and commas
    while ($idx < $len && (trim($featuresJson[$idx]) === '' || $featuresJson[$idx] === ',')) $idx++;
    if ($idx >= $len) break;
    if ($featuresJson[$idx] !== '{') break;
    $brace = 0;
    $startIdx = $idx;
    while ($idx < $len) {
        $ch = $featuresJson[$idx];
        if ($ch === '{') $brace++;
        if ($ch === '}') $brace--;
        $idx++;
        if ($brace === 0) break;
    }
    $featureIndex++;
    $featureJson = substr($featuresJson, $startIdx, $idx - $startIdx);
    $feature = json_decode($featureJson, true);
    if (!$feature) { /* skip invalid */ continue; }
    $props = $feature['properties'] ?? [];
    $geom = $feature['geometry'] ?? null;
    $objectid = $props['objectid'] ?? ($props['OBJECTID'] ?? null);
    $namobj = $props['namobj'] ?? ($props['NAME'] ?? ($props['name'] ?? null));

    if (!$geom || !isset($geom['type']) || !isset($geom['coordinates'])) continue;

    $geomType = $geom['type'];
    $coordinates = $geom['coordinates'];

    // Normalize to array of polygons rings: for Polygon -> [coordinates], for MultiPolygon -> coordinates
    $polygons = [];
    if ($geomType === 'Polygon') {
        $polygons[] = $coordinates;
    } elseif ($geomType === 'MultiPolygon') {
        foreach ($coordinates as $poly) $polygons[] = $poly;
    } else {
        continue;
    }

    // For each polygon, compute bbox and test points
    foreach ($polygons as $ringIndex => $poly) {
        // poly is array of rings; outer ring = poly[0]
        $outer = $poly[0];
        $minX = $minY = PHP_FLOAT_MAX; $maxX = $maxY = -PHP_FLOAT_MAX;
        foreach ($outer as $pt) {
            $x = floatval($pt[0]); $y = floatval($pt[1]);
            if ($x < $minX) $minX = $x; if ($x > $maxX) $maxX = $x;
            if ($y < $minY) $minY = $y; if ($y > $maxY) $maxY = $y;
        }
        // iterate points and test if inside bbox then point-in-polygon
        foreach ($umkms as $id => $p) {
            if ($p['lng'] < $minX || $p['lng'] > $maxX || $p['lat'] < $minY || $p['lat'] > $maxY) continue;
            // point-in-polygon on outer ring (ignore holes for now for simplicity)
            if (pointInPolygon([$p['lng'],$p['lat']], $outer)) {
                // record
                fputcsv($outHandle, [$id, $p['lat'], $p['lng'], $featureIndex, $objectid, $namobj, $ringIndex]);
                $assigned++;
                // remove from umkms to avoid multiple matches
                unset($umkms[$id]);
            }
        }
        // if no more points, break early
        if (count($umkms) === 0) break 2;
    }
}

fclose($outHandle);
echo "Dry-run complete. Assigned candidates: {$assigned}. Output: storage/app/{$outRel}\n";

function pointInPolygon($point, $polygon) {
    // polygon: array of [ [x,y], ... ] (closed ring maybe)
    $x = $point[0]; $y = $point[1];
    $inside = false;
    $len = count($polygon);
    for ($i=0, $j=$len-1; $i<$len; $j=$i++) {
        $xi = $polygon[$i][0]; $yi = $polygon[$i][1];
        $xj = $polygon[$j][0]; $yj = $polygon[$j][1];
        $intersect = (($yi > $y) != ($yj > $y)) && ($x < ($xj - $xi) * ($y - $yi) / ($yj - $yi + 0.0) + $xi);
        if ($intersect) $inside = !$inside;
    }
    return $inside;
}

?>