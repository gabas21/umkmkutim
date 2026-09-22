<?php
$geoPath = __DIR__ . '/../peta-lokasi-kaltim/ADMINISTRASIDESA_AR_250K.json';
$contents = file_get_contents($geoPath);
$start = strpos($contents, '"features"');
$startBrace = strpos($contents, '[', $start);
$endPos = strrpos($contents, ']');
$featuresJson = substr($contents, $startBrace, $endPos - $startBrace + 1);
$len = strlen($featuresJson);
$idx = 0;
$minX=1e9;$minY=1e9;$maxX=-1e9;$maxY=-1e9;$count=0;
while ($idx < $len) {
    while ($idx < $len && (trim($featuresJson[$idx]) === '' || $featuresJson[$idx] === ',')) $idx++;
    if ($idx >= $len) break;
    if ($featuresJson[$idx] !== '{') break;
    $brace = 0; $startIdx = $idx;
    while ($idx < $len) { $ch = $featuresJson[$idx]; if ($ch === '{') $brace++; if ($ch === '}') $brace--; $idx++; if ($brace===0) break; }
    $featureJson = substr($featuresJson, $startIdx, $idx - $startIdx);
    $feature = json_decode($featureJson, true);
    if (!$feature) continue;
    $geom = $feature['geometry'] ?? null;
    if (!$geom) continue;
    $coords = $geom['coordinates'];
    $type = $geom['type'];
    $count++;
    if ($type === 'Polygon') {
        $multi = [$coords];
    } elseif ($type === 'MultiPolygon') {
        $multi = $coords;
    } else continue;
    foreach ($multi as $poly) {
        foreach ($poly as $ring) {
            foreach ($ring as $pt) {
                $x = floatval($pt[0]); $y = floatval($pt[1]);
                if ($x<$minX) $minX=$x; if ($x>$maxX) $maxX=$x;
                if ($y<$minY) $minY=$y; if ($y>$maxY) $maxY=$y;
            }
        }
    }
}
echo "Processed features: $count\n";
echo "BBox: minX=$minX minY=$minY maxX=$maxX maxY=$maxY\n";
