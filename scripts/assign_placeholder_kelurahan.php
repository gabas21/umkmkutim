<?php
// One-off script to create placeholder kelurahan "Belum Diketahui" per kecamatan
// and assign UMKM with kecamatan set but kelurahan_id NULL to the placeholder.

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Kelurahan;
use App\Models\Umkm;

$ids = [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18];

foreach ($ids as $kecId) {
    $kel = Kelurahan::firstOrCreate([
        'kecamatan_id' => $kecId,
        'name' => 'Belum Diketahui',
    ]);

    $updated = Umkm::where('kecamatan_id', $kecId)
        ->whereNull('kelurahan_id')
        ->update(['kelurahan_id' => $kel->id]);

    echo "kecamatan {$kecId} => kelurahan_id {$kel->id}, updated {$updated}\n";
}

echo "Selesai.\n";
