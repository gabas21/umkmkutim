<?php
// Clear kelurahan_id for UMKM that have kecamatan_id but no kelurahan_desa (empty or null).
// Writes a CSV audit file to storage/app/exports/ with details of changed rows.

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Umkm;
use Illuminate\Support\Facades\Storage;

$now = (new DateTime())->format('Ymd_His');
$exportPath = "exports/cleared_kelurahan_{$now}.csv";
$fullExport = __DIR__ . "/../storage/app/{$exportPath}";

// Select UMKM matching criteria
$toClear = Umkm::whereNotNull('kecamatan_id')
    ->where(function ($q) {
        $q->whereNull('kelurahan_desa')
          ->orWhereRaw("TRIM(COALESCE(kelurahan_desa,'')) = ''");
    })
    ->get(['id','nama_usaha','kecamatan_id','kelurahan_desa','kelurahan_id']);

$count = $toClear->count();

if ($count === 0) {
    echo "No UMKM found that have kecamatan but empty kelurahan_desa.\n";
    exit(0);
}

// Write CSV header
$fp = fopen($fullExport, 'w');
fputcsv($fp, ['umkm_id','nama_usaha','kecamatan_id','kelurahan_desa_before','kelurahan_id_before']);

$ids = [];
foreach ($toClear as $r) {
    fputcsv($fp, [$r->id, $r->nama_usaha, $r->kecamatan_id, $r->kelurahan_desa, $r->kelurahan_id]);
    $ids[] = $r->id;
}

fclose($fp);

// Perform update
$updated = Umkm::whereIn('id', $ids)->update(['kelurahan_id' => null]);

echo "Found {$count} UMKM; updated kelurahan_id set to NULL for {$updated} rows.\n";
echo "Audit CSV written to storage/app/{$exportPath}\n";

exit(0);
