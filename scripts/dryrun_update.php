<?php

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

use App\Models\Umkm;
use Illuminate\Contracts\Console\Kernel;

$query = Umkm::with('kategori')
    ->whereNull('image')
    ->orWhere('image', '')
    ->orWhere('image', 'umkm.png')
    ->orWhere('image', 'like', '%umkm.png%');

$items = $query->get();
$count = $items->count();

$csvPath = storage_path('app/placeholders-update-preview.csv');
$fp = fopen($csvPath, 'w');
if ($fp) {
    fputcsv($fp, ['id', 'nama_usaha', 'kategori', 'current_image', 'new_preview_url']);
    foreach ($items as $u) {
        $id = $u->id;
        $name = $u->nama_usaha ?? ($u->nama ?? '');
        $kategori = optional($u->kategori)->nama ?? '';
        $current = $u->image ?? '';
        $preview = url("storage/placeholders/umkm_{$id}.svg");
        fputcsv($fp, [$id, $name, $kategori, $current, $preview]);
    }
    fclose($fp);
}

echo json_encode(['count' => $count, 'csv' => $csvPath]).PHP_EOL;
