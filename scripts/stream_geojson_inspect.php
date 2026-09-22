<?php
$path = __DIR__ . '/../peta-lokasi-kaltim/ADMINISTRASIDESA_AR_250K.json';
$handle = fopen($path, 'r');
if (!$handle) { echo "Cannot open file\n"; exit(1); }
$featureCount = 0;
$propKeysCounts = [];
$geomTypes = [];
$maxFeatures = 500; // sample limit
while (!feof($handle)){
    $chunk = fread($handle, 8192);
    if ($chunk === false) break;
    // count occurrences of ""type"\s*:\s*"Feature""
    $featureCount += preg_match_all('/"type"\s*:\s*"Feature"/i', $chunk, $m);
    // find properties blocks in chunk start positions
    $offset = 0;
    while (($pos = strpos($chunk, '"properties"', $offset)) !== false){
        // move file pointer to absolute position
        $absPos = ftell($handle) - strlen($chunk) + $pos;
        // seek to absPos
        fseek($handle, $absPos);
        // read until we capture full properties object
        // find opening '{' after "properties" :
        $s = '';
        // read forward char by char
        // skip until first '{'
        $found = false;
        while (!feof($handle)){
            $c = fgetc($handle);
            if ($c === false) break;
            $s .= $c;
            if ($c === '{') { $found = true; break; }
        }
        if (!$found) break;
        $brace = 1;
        while ($brace > 0 && !feof($handle)){
            $c = fgetc($handle);
            if ($c === false) break;
            $s .= $c;
            if ($c === '{') $brace++;
            if ($c === '}') $brace--;
        }
        // now $s contains the properties JSON including leading chars up to closing brace
        // extract the JSON object part
        $jsonStart = strpos($s, '{');
        $propJson = substr($s, $jsonStart, strrpos($s, '}') - $jsonStart + 1);
        $props = json_decode($propJson, true);
        if (is_array($props)){
            foreach(array_keys($props) as $k) {
                if (!isset($propKeysCounts[$k])) $propKeysCounts[$k]=0;
                $propKeysCounts[$k]++;
            }
        }
        // move offset past pos
        $offset = $pos + 12;
        // move back to continue reading chunk (we moved file pointer forward), reposition to continue reading next chunk from current position
        $chunk = fread($handle, 8192); if ($chunk === false) break; // read next chunk
    }
    // break if enough keys counted
    if (count($propKeysCounts) > 0 && $featureCount > $maxFeatures) break;
}
fclose($handle);
arsort($propKeysCounts);
echo "Estimated feature count (partial scan): $featureCount\n";
echo "Top property keys (sample):\n";
foreach($propKeysCounts as $k=>$c) echo " - $k : $c\n";
