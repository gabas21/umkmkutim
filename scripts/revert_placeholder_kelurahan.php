<?php
// Revert placeholder kelurahan: set Umkm.kelurahan_id = NULL where kelurahan name = 'Belum Diketahui'
// then delete those Kelurahan rows.

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Kelurahan;
use App\Models\Umkm;
use Illuminate\Support\Facades\DB;

$placeholders = Kelurahan::where('name', 'Belum Diketahui')->get();
if ($placeholders->isEmpty()) {
    echo "No placeholder kelurahan found (name = 'Belum Diketahui').\n";
    exit(0);
}

$ids = $placeholders->pluck('id')->all();

// Update UMKM
$updated = Umkm::whereIn('kelurahan_id', $ids)->update(['kelurahan_id' => null]);

// Delete kelurahan placeholders
$deletedCount = Kelurahan::whereIn('id', $ids)->delete();

echo "Found " . count($ids) . " placeholder kelurahan (ids: " . implode(',', $ids) . ").\n";
echo "UMKM rows updated (kelurahan_id set NULL): {$updated}\n";
echo "Placeholder kelurahan deleted: {$deletedCount}\n";

echo "Done.\n";
