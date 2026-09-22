<?php
// Reverse geocode all UMKM with coordinates using Nominatim (respectful, 1 req/sec)
// Writes CSV to storage/app/exports/reverse_geocode_all_<timestamp>.csv
// This script can take several hours for ~12k records. It retries transient errors.

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Umkm;

$contactEmail = 'kaminaribrn@gmail.com';
$userAgent = "umkm-kutim-mapping/1.0 ({$contactEmail})";

$now = (new DateTime())->format('Ymd_His');
$outRel = "exports/reverse_geocode_all_{$now}.csv";
$outPath = __DIR__ . "/../storage/app/{$outRel}";
@mkdir(dirname($outPath), 0777, true);
$fp = fopen($outPath, 'w');
if (!$fp) { echo "Cannot open output file: {$outPath}\n"; exit(1); }

fputcsv($fp, ['umkm_id','nama_usaha','lat','lng','nominatim_display_name','nominatim_type','village','hamlet','suburb','town','city','state','country','raw_address_json']);

$processed = 0;
$skipped = 0;
$errors = 0;

Umkm::withCoordinates()->whereNotNull('location')->orderBy('id')->chunk(100, function($rows) use (&$fp, &$processed, &$skipped, &$errors, $userAgent, $contactEmail) {
    foreach ($rows as $r) {
        $id = $r->id;
        $lat = $r->latitude; $lng = $r->longitude;
        if ($lat === null || $lng === null) { $skipped++; continue; }

        $attempt = 0; $maxAttempts = 4; $resDecoded = null; $httpCode = 0; $curlErrNo = 0;
        while ($attempt < $maxAttempts) {
            $attempt++;
            $url = 'https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=' . urlencode($lat) . '&lon=' . urlencode($lng) . '&addressdetails=1&accept-language=id&email=' . urlencode($contactEmail);

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            $res = curl_exec($ch);
            $curlErrNo = curl_errno($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($curlErrNo !== 0) {
                // retry
                $wait = min(30, 2 ** $attempt);
                sleep($wait);
                continue;
            }

            if ($httpCode >= 500) {
                // server error, backoff and retry
                $wait = min(30, 2 ** $attempt);
                sleep($wait);
                continue;
            }

            if ($httpCode === 429) {
                // rate limited - wait longer
                sleep(5);
                continue;
            }

            if ($httpCode !== 200) {
                // non-success (including 403) - record and stop retrying further for this id
                break;
            }

            $resDecoded = json_decode($res, true);
            break;
        }

        $display = null; $type = null; $addr = [];
        if ($resDecoded && is_array($resDecoded)) {
            $display = $resDecoded['display_name'] ?? null;
            $type = $resDecoded['type'] ?? null;
            $addr = $resDecoded['address'] ?? [];
        } else {
            $errors++;
            $display = "ERROR_{$httpCode}_{$curlErrNo}";
        }

        $rowOut = [
            $id,
            $r->nama_usaha,
            $lat,
            $lng,
            $display,
            $type,
            $addr['village'] ?? '',
            $addr['hamlet'] ?? '',
            $addr['suburb'] ?? '',
            $addr['town'] ?? '',
            $addr['city'] ?? '',
            $addr['state'] ?? '',
            $addr['country'] ?? '',
            json_encode($addr, JSON_UNESCAPED_UNICODE)
        ];

        fputcsv($fp, $rowOut);
        fflush($fp);
        $processed++;

        // Respect polite rate limit: 1 request per second
        sleep(1);
    }
});

fclose($fp);

echo "Reverse-geocode full run finished. processed={$processed}, skipped={$skipped}, errors={$errors}\n";
echo "Output CSV: storage/app/{$outRel}\n";

?>