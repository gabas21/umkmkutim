<?php
$path = __DIR__ . '/../peta-lokasi-kaltim/ADMINISTRASIDESA_AR_250K.json';
if (!file_exists($path)) { echo "File not found: $path\n"; exit(1); }
$contents = file_get_contents($path);
$json = json_decode($contents, true);
if (!$json || !isset($json['features'])) { echo "Not a valid GeoJSON FeatureCollection\n"; exit(1); }
$features = $json['features'];
echo "Total features: " . count($features) . "\n";
// collect property keys frequency (first 50 features to avoid heavy)
$keyCounts = [];
$sampleProps = [];
$limit = min(100, count($features));
for ($i=0;$i<$limit;$i++){
    $f = $features[$i];
    if (isset($f['properties']) && is_array($f['properties'])){
        foreach(array_keys($f['properties']) as $k){
            if (!isset($keyCounts[$k])) $keyCounts[$k]=0;
            $keyCounts[$k]++;
        }
        if ($i==0) $sampleProps = $f['properties'];
    }
}
arsort($keyCounts);
echo "Top property keys (from first $limit features):\n";
foreach($keyCounts as $k=>$c){ echo " - $k : $c\n"; }
if (!empty($sampleProps)){
    echo "\nSample properties from feature[0]:\n";
    foreach($sampleProps as $k=>$v){
        $vstr = is_scalar($v)? (string)$v : json_encode($v);
        if (strlen($vstr)>200) $vstr = substr($vstr,0,197).'...';
        echo " $k => $vstr\n";
    }
}
// Show geometry types distribution for first 200 features
$geomCounts = [];
$limit2 = min(200, count($features));
for ($i=0;$i<$limit2;$i++){
    $t = $features[$i]['geometry']['type'] ?? 'null';
    if (!isset($geomCounts[$t])) $geomCounts[$t]=0;
    $geomCounts[$t]++;
}
echo "\nGeometry types (sample $limit2):\n";
foreach($geomCounts as $k=>$c) echo " - $k : $c\n";
