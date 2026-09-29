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
    if ($f === '.' || $f === '..') {
        continue;
    }

    $n = strtolower(preg_replace('/[^a-z0-9 ]/', ' ', mb_strtolower(trim((string) pathinfo($f, PATHINFO_FILENAME)))));
    $map[$n] = $f;
}

$updated = [];
Umkm::chunk(100, function ($chunk) use (&$map, &$updated) {
    foreach ($chunk as $u) {
        $foto = trim((string) $u->foto_utama);
        $useSampul = false;

        if ($foto === '') {
            $useSampul = true;
        } elseif (!str_starts_with($foto, 'http') && !Storage::disk('public')->exists($foto)) {
            $useSampul = true;
        }

        if ($useSampul) {
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

                if (! $found) {
                    $tokens = preg_split('/\s+|&|,|-|_/', $search);
                    foreach ($tokens as $t) {
                        $t = trim($t);
                        if ($t === '') {
                            continue;
                        }

                        foreach ($map as $n => $f) {
                            if (str_contains($n, $t)) {
                                $found = $f;
                                break 2;
                            }
                        }
                    }
                }
            }

            if ($found) {
                $u->foto_utama = 'sampul/' . $found;
                $u->save();
                $updated[] = $u->id;
            }
        }
    }
});

echo json_encode(['updated' => $updated]);
