<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
use App\Models\Umkm;

$rows = Umkm::withCoordinates()->whereNotNull('location')->inRandomOrder()->take(50)->get();
if ($rows->isEmpty()) { echo "No UMKM points found\n"; exit(0); }

foreach ($rows as $r) {
    echo sprintf("%d | %s | lat=%s | lng=%s | kecamatan_id=%s\n", $r->id, str_replace("\n"," ", $r->nama_usaha), ($r->latitude ?? 'null'), ($r->longitude ?? 'null'), ($r->kecamatan_id ?? 'null'));
}
