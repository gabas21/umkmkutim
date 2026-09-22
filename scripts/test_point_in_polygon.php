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
// get first feature
$idx = 0; while ($idx < strlen($featuresJson) && trim($featuresJson[$idx]) !== '{') $idx++;
$brace=0;$startIdx=$idx; while ($idx < strlen($featuresJson)) { $ch=$featuresJson[$idx]; if($ch=='{') $brace++; if($ch=='}') $brace--; $idx++; if($brace==0) break; }
$featureJson = substr($featuresJson,$startIdx,$idx-$startIdx);
$feature = json_decode($featureJson,true);
$geom = $feature['geometry'];
$outer = $geom['coordinates'][0];
$sample = Umkm::withCoordinates()->whereNotNull('location')->first();
if (!$sample) { echo "no sample\n"; exit; }
$point = [$sample->longitude, $sample->latitude];
function pip($point,$polygon){
    $x=$point[0];$y=$point[1];$inside=false;$len=count($polygon);
    for($i=0,$j=$len-1;$i<$len;$j=$i++){
        $xi=$polygon[$i][0]; $yi=$polygon[$i][1]; $xj=$polygon[$j][0]; $yj=$polygon[$j][1];
        $intersect = (($yi > $y) != ($yj > $y)) && ($x < ($xj - $xi) * ($y - $yi) / ($yj - $yi + 0.0) + $xi);
        if ($intersect) $inside = !$inside;
    }
    return $inside;
}
$inside = pip($point,$outer);
echo "Sample UMKM id: {$sample->id} lat={$sample->latitude} lng={$sample->longitude}\n";
echo "First feature name: ".($feature['properties']['namobj'] ?? 'N/A')."\n";
echo "Point in first polygon? " . ($inside? 'YES':'NO') . "\n";
