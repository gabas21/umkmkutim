<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Umkm;
use Illuminate\Support\Facades\Storage;

$dir = public_path('sampul');
if (!is_dir($dir)) {
    echo json_encode(['error' => 'sampul dir not found', 'dir' => $dir]);
    exit(1);
}

$files = scandir($dir);
$map = [];
foreach ($files as $f) {
    if ($f === '.' || $f === '..') continue;
    $n = strtolower(preg_replace('/[^a-z0-9 ]/', ' ', mb_strtolower(trim((string) pathinfo($f, PATHINFO_FILENAME)))));
    $map[$n] = $f;
}

$backup = [];
$updated = [];
$timestamp = date('YmdHis');

Umkm::chunk(500, function ($chunk) use (&$map, &$backup, &$updated) {
    foreach ($chunk as $u) {
        $old = $u->foto_utama;
        $kat = trim((string) ($u->kategori?->nama ?? ''));
        $found = null;

        if ($kat !== '') {
            $search = strtolower(preg_replace('/[^a-z0-9 ]/', ' ', mb_strtolower(trim($kat))));
            foreach ($map as $n => $f) {
                if (str_contains($n, $search) || str_contains($search, $n)) {
                    $found = $f;
                    break;
                }
            }
            if (!$found) {
                $tokens = preg_split('/\s+|&|,|-|_/', $search);
                foreach ($tokens as $t) {
                    $t = trim($t);
                    if ($t === '') continue;
                    foreach ($map as $n => $f) {
                        if (str_contains($n, $t)) { $found = $f; break 2; }
                    }
                }
            }
        }

        $new = $found ? 'sampul/' . $found : null;
        $backup[] = ['id' => $u->id, 'old' => $old, 'new' => $new];

        if ($new) {
            $u->foto_utama = $new;
            $u->save();
            $updated[] = $u->id;
        }
    }
});

$backupFile = __DIR__ . "/foto_utama_backup_{$timestamp}.json";
file_put_contents($backupFile, json_encode($backup, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

echo json_encode(['backup_file' => $backupFile, 'updated_count' => count($updated), 'updated_ids_sample' => array_slice($updated, 0, 50)]);
