<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
use App\Models\Umkm;
$geoPath = __DIR__ . '/../peta-lokasi-kaltim/ADMINISTRASIDESA_AR_250K.json';
$contents = file_get_contents($geoPath);
$start = strpos($contents, '"features"');
$startBrace = strpos($contents, '[', $start);
$endPos = strrpos($contents, ']');
$featuresJson = substr($contents, $startBrace, $endPos - $startBrace + 1);
$sample = Umkm::withCoordinates()->whereNotNull('location')->first();
$point = [$sample->longitude, $sample->latitude];
$pointSwapped = [$sample->latitude, $sample->longitude];

$len = strlen($featuresJson); $idx=0; $found=0; $count=0;
function pip($point,$polygon){ $x=$point[0];$y=$point[1];$inside=false;$len=count($polygon);for($i=0,$j=$len-1;$i<$len;$j=$i++){ $xi=$polygon[$i][0]; $yi=$polygon[$i][1]; $xj=$polygon[$j][0]; $yj=$polygon[$j][1]; $intersect = (($yi > $y) != ($yj > $y)) && ($x < ($xj - $xi) * ($y - $yi) / ($yj - $yi + 0.0) + $xi); if ($intersect) $inside = !$inside;} return $inside; }
while ($idx < $len) {
    while ($idx < $len && (trim($featuresJson[$idx]) === '' || $featuresJson[$idx] === ',')) $idx++;
    if ($idx >= $len) break; if ($featuresJson[$idx] !== '{') break;
    $brace=0;$startIdx=$idx; while ($idx<$len){ $ch=$featuresJson[$idx]; if($ch=='{')$brace++; if($ch=='}')$brace--; $idx++; if($brace==0) break; }
    $featureJson = substr($featuresJson,$startIdx,$idx-$startIdx);
    $feat = json_decode($featureJson,true);
    if (!$feat) continue; $count++;
    $geom = $feat['geometry'] ?? null; if(!$geom) continue;
    $type = $geom['type']; $coords = $geom['coordinates'];
    $polygons = ($type==='Polygon')? [$coords] : ($type==='MultiPolygon'? $coords : []);
    $foundHere = false;
    foreach ($polygons as $poly){
        $outer = $poly[0];
        if (pip($point,$outer) || pip($pointSwapped,$outer)){
            echo "Found in feature index: $count name=".($feat['properties']['namobj'] ?? 'N/A')."\n";
            $found=1;
            $foundHere = true;
            break;
        }
    }
    if ($foundHere) break;

}
if(!$found) echo "No containing polygon found for sample point\n";
