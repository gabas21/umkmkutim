<?php

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

use App\Models\Umkm;
use Illuminate\Contracts\Console\Kernel;

$umkms = Umkm::with('kategori')->limit(10)->get();

$iconsKeys = [
    'perdagangan', 'kelontong', 'grosir', 'toko', 'warung', 'minyak', 'gas', 'tabung', 'jualan', 'pedagang',
    'kopi', 'kopisusu', 'teh', 'roti', 'kue', 'gorengan', 'mie', 'sate', 'ikan', 'ayam', 'buah', 'sayur', 'minuman', 'baju', 'kaos', 'pakaian', 'batik', 'sepatu', 'tas', 'perhiasan', 'souvenir', 'kerajinan', 'sabun',
];

foreach ($umkms as $u) {
    $category = $u->kategori->nama ?? '';
    $label = $u->nama_usaha ?? $u->nama ?? '';
    $searchSource = mb_strtolower(trim(($category ?? '').' '.($label ?? '')));
    $matched = null;
    foreach ($iconsKeys as $k) {
        if (mb_strpos($searchSource, $k) !== false) {
            $matched = $k;
            break;
        }
    }
    echo json_encode(['id' => $u->id, 'searchSource' => $searchSource, 'matched' => $matched]).PHP_EOL;
}
