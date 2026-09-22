<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
use App\Models\Umkm;
$rows = Umkm::withCoordinates()->whereNotNull('location')->take(10)->get();
foreach ($rows as $u) {
    echo $u->id . " => lat:" . ($u->latitude ?? 'null') . " lng:" . ($u->longitude ?? 'null') . PHP_EOL;
}
