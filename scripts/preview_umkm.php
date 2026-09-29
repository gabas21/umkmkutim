<?php

// Script to list first N UMKM with current image and preview URL
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

use App\Models\Umkm;
use Illuminate\Contracts\Console\Kernel;

$limit = 10;
$umkms = Umkm::with('kategori')->limit($limit)->get();
foreach ($umkms as $u) {
    $id = $u->id;
    $name = $u->nama_usaha ?? ($u->nama ?? '(no-name)');
    $kategori = optional($u->kategori)->nama ?? '(no-kategori)';
    $currentImage = $u->image ?? '';
    $previewUrl = url("storage/placeholders/umkm_{$id}.svg");
    echo json_encode(['id' => $id, 'nama_usaha' => $name, 'kategori' => $kategori, 'current_image' => $currentImage, 'preview_url' => $previewUrl]).PHP_EOL;
}
