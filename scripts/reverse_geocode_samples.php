<?php
// Reverse-geocode 50 random UMKM points using Nominatim (dry-run).
// Writes CSV to storage/app/exports/reverse_geocode_samples_YYYYmmdd_HHMMSS.csv

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Umkm;

$rows = Umkm::withCoordinates()->whereNotNull('location')->inRandomOrder()->take(50)->get(['id','nama_usaha']);
if ($rows->isEmpty()) { echo "No UMKM points found\n"; exit(0); }

$now = (new DateTime())->format('Ymd_His');
$outRel = "exports/reverse_geocode_samples_{$now}.csv";
$outPath = __DIR__ . "/../storage/app/{$outRel}";
@mkdir(dirname($outPath), 0777, true);
$fp = fopen($outPath, 'w');
fputcsv($fp, ['umkm_id','nama_usaha','lat','lng','nominatim_display_name','nominatim_type','village','hamlet','suburb','town','city','state','country','raw_json']);

$contactEmail = 'kaminaribrn@gmail.com';
$userAgent = "umkm-kutim-mapping/1.0 ({$contactEmail})";

foreach ($rows as $r) {
    $lat = $r->latitude; $lng = $r->longitude;
    if ($lat === null || $lng === null) continue;
    $url = 'https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=' . urlencode($lat) . '&lon=' . urlencode($lng) . '&addressdetails=1&accept-language=id&email=' . urlencode($contactEmail);

    // Use curl
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    $res = curl_exec($ch);
    $errno = curl_errno($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $display = null; $type = null; $addr = [];
    if ($errno === 0 && $httpCode === 200 && $res) {
        $j = json_decode($res, true);
        if (is_array($j)) {
            $display = $j['display_name'] ?? null;
            $type = $j['type'] ?? null;
            $addr = $j['address'] ?? [];
        }
    } else {
        $display = "ERROR_{$httpCode}_{$errno}";
    }

    $rowOut = [
        $r->id,
        $r->nama_usaha,
        $lat,
        $lng,
        $display,
        $type,
        $addr['village'] ?? ($addr['suburb'] ?? null),
        $addr['hamlet'] ?? null,
        $addr['suburb'] ?? null,
        $addr['town'] ?? null,
        $addr['city'] ?? null,
        $addr['state'] ?? null,
        $addr['country'] ?? null,
        json_encode($addr, JSON_UNESCAPED_UNICODE)
    ];

    fputcsv($fp, $rowOut);

    // Respect rate-limit: sleep 1 sec
    sleep(1);
}

fclose($fp);

echo "Reverse-geocode finished. Output: storage/app/{$outRel}\n";
