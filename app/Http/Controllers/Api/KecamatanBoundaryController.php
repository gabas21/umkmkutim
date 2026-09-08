<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class KecamatanBoundaryController extends Controller
{
    /**
     * Palet warna unik untuk 18 kecamatan Kutai Timur.
     * Format: [fill, stroke]
     */
    private array $kecamatanColors = [
        'Sangatta Utara'   => ['#ef4444', '#dc2626'],
        'Sangatta Selatan' => ['#f97316', '#ea580c'],
        'Rantau Pulung'    => ['#eab308', '#ca8a04'],
        'Bengalon'         => ['#84cc16', '#65a30d'],
        'Kaliorang'        => ['#10b981', '#059669'],
        'Sangkulirang'     => ['#06b6d4', '#0891b2'],
        'Sandaran'         => ['#3b82f6', '#2563eb'],
        'Karangan'         => ['#8b5cf6', '#7c3aed'],
        'Kaubun'           => ['#ec4899', '#db2777'],
        'Busang'           => ['#f43f5e', '#e11d48'],
        'Long Mesangat'    => ['#14b8a6', '#0d9488'],
        'Muara Bengkal'    => ['#a855f7', '#9333ea'],
        'Muara Wahau'      => ['#f59e0b', '#d97706'],
        'Telen'            => ['#22c55e', '#16a34a'],
        'Kongbeng'         => ['#0ea5e9', '#0284c7'],
        'Batu Ampar'       => ['#6366f1', '#4f46e5'],
        'Teluk Pandan'     => ['#d946ef', '#c026d3'],
        'Muara Ancalong'   => ['#64748b', '#475569'],
    ];

    private array $fallbackPalette = [
        ['#ef4444', '#dc2626'], ['#f97316', '#ea580c'], ['#eab308', '#ca8a04'],
        ['#84cc16', '#65a30d'], ['#10b981', '#059669'], ['#06b6d4', '#0891b2'],
        ['#3b82f6', '#2563eb'], ['#8b5cf6', '#7c3aed'], ['#ec4899', '#db2777'],
        ['#f43f5e', '#e11d48'], ['#14b8a6', '#0d9488'], ['#a855f7', '#9333ea'],
        ['#f59e0b', '#d97706'], ['#22c55e', '#16a34a'], ['#0ea5e9', '#0284c7'],
        ['#6366f1', '#4f46e5'], ['#d946ef', '#c026d3'], ['#64748b', '#475569'],
    ];

    /**
     * Kembalikan GeoJSON batas kecamatan Kutai Timur.
     * Priority:
     *  1. File statis public/geojson/kutim-kecamatan.json (terbaik)
     *  2. Cache jika ada
     *  3. Fetch dari Nominatim via curl
     *  4. Fallback kosong
     */
    public function index()
    {
        // 1. Coba baca dari file statis (paling cepat, < 1ms)
        $staticFile = public_path('geojson/kutim-kecamatan.json');
        if (file_exists($staticFile) && filesize($staticFile) > 500) {
            $raw = json_decode(file_get_contents($staticFile), true);
            if (! empty($raw['features'])) {
                // Inject warna ke setiap feature
                $raw = $this->injectColors($raw);
                return response()->json($raw)
                    ->header('Cache-Control', 'public, max-age=86400')
                    ->header('X-Source', 'static-file');
            }
        }

        // 2. Coba dari cache
        $cached = Cache::get('kutim_kecamatan_geojson_v4');
        if ($cached && ! empty($cached['features'])) {
            return response()->json($cached)
                ->header('Cache-Control', 'public, max-age=86400')
                ->header('X-Source', 'cache');
        }

        // 3. Fetch dari Nominatim via curl
        $data = Cache::remember('kutim_kecamatan_geojson_v4', now()->addDays(7), function () {
            return $this->fetchFromNominatim();
        });

        return response()->json($data)
            ->header('Cache-Control', 'public, max-age=86400')
            ->header('X-Source', 'nominatim');
    }

    /**
     * Inject warna ke setiap feature GeoJSON berdasarkan nama kecamatan.
     */
    private function injectColors(array $geojson): array
    {
        foreach ($geojson['features'] as &$feature) {
            $name   = $feature['properties']['kecamatan'] ?? $feature['properties']['name'] ?? '';
            $colors = $this->getColorForKecamatan($name);
            $feature['properties']['fill']   = $colors[0];
            $feature['properties']['stroke'] = $colors[1];
        }
        return $geojson;
    }

    /**
     * Fetch polygon dari Nominatim menggunakan curl native.
     */
    private function fetchFromNominatim(): array
    {
        $kecamatanList = [
            'Sangatta Utara', 'Sangatta Selatan', 'Rantau Pulung', 'Bengalon',
            'Kaliorang', 'Sangkulirang', 'Sandaran', 'Karangan', 'Kaubun',
            'Busang', 'Long Mesangat', 'Muara Bengkal', 'Muara Wahau',
            'Telen', 'Kongbeng', 'Batu Ampar', 'Teluk Pandan',
        ];

        $features = [];

        foreach ($kecamatanList as $kec) {
            $result = $this->nominatimSearch("Kecamatan $kec, Kutai Timur, Indonesia");
            if (! $result) {
                $result = $this->nominatimSearch("$kec, Kutai Timur, Indonesia");
            }

            if ($result) {
                $colors = $this->getColorForKecamatan($kec);
                $features[] = [
                    'type'       => 'Feature',
                    'properties' => [
                        'name'      => $kec,
                        'kecamatan' => $kec,
                        'fill'      => $colors[0],
                        'stroke'    => $colors[1],
                    ],
                    'geometry' => $result['geojson'],
                ];
            }

            usleep(1000000); // 1 detik delay (rate limit Nominatim)
        }

        // Simpan ke file statis agar tidak perlu fetch ulang
        $geojson = [
            'type'     => 'FeatureCollection',
            'features' => $features,
            'meta'     => [
                'source' => 'Nominatim/OpenStreetMap',
                'count'  => count($features),
                'date'   => now()->toDateString(),
            ],
        ];

        $staticFile = public_path('geojson/kutim-kecamatan.json');
        if (! is_dir(dirname($staticFile))) {
            mkdir(dirname($staticFile), 0755, true);
        }
        file_put_contents($staticFile, json_encode($geojson, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        return $geojson;
    }

    /**
     * Curl ke Nominatim API untuk satu kecamatan.
     */
    private function nominatimSearch(string $query): ?array
    {
        $url = 'https://nominatim.openstreetmap.org/search?' . http_build_query([
            'q'               => $query,
            'format'          => 'json',
            'limit'           => 5,
            'polygon_geojson' => 1,
            'addressdetails'  => 1,
        ]);

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_USERAGENT      => 'UMKMKutim/1.0 (Laravel App)',
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER     => ['Accept-Language: id-ID,id;q=0.9'],
        ]);

        $response = curl_exec($ch);
        $code     = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($code !== 200 || ! $response) {
            return null;
        }

        $results = json_decode($response, true);

        foreach ($results ?? [] as $r) {
            $hasGeo = isset($r['geojson']) &&
                in_array($r['geojson']['type'], ['Polygon', 'MultiPolygon']);

            $inKutim = stripos($r['display_name'] ?? '', 'Kutai Timur') !== false;

            if ($hasGeo && $inKutim) {
                return $r;
            }
        }

        return null;
    }

    /**
     * Tentukan warna berdasarkan nama kecamatan.
     */
    private function getColorForKecamatan(string $name): array
    {
        $normalized = trim(preg_replace('/^kecamatan\s+/i', '', $name));

        foreach ($this->kecamatanColors as $key => $colors) {
            if (stripos($normalized, $key) !== false || stripos($key, $normalized) !== false) {
                return $colors;
            }
        }

        $idx = abs(crc32($normalized)) % count($this->fallbackPalette);
        return $this->fallbackPalette[$idx];
    }
}
