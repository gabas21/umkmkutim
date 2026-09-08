<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Umkm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class UmkmClusterController extends Controller
{
    /**
     * PATCH #1 (bug staleness): cache key sebelumnya TIDAK terhubung ke data --
     * jadi kalau ada import/refresh grid baru, response lama tetap ke-serve sampai
     * TTL 600 detik habis walau tabel umkm_grid_cluster sudah di-refresh duluan.
     * Sekarang cache key ikut versi ini, dan RefreshGridCluster akan increment versi
     * setiap kali selesai refresh -> otomatis invalidate SEMUA cache lama secara instan
     * tanpa perlu cache driver yang support tags (Redis/DB/File cache semua kompatibel).
     */
    private function cacheVersion(): int
    {
        return (int) Cache::get('umkm_cluster_cache_ver', 1);
    }

    /**
     * PATCH #2 (bug scalability serius): cabang "filter spesifik aktif" (saat user
     * search / filter kategori / filter status_klaim) sebelumnya TIDAK punya LIMIT
     * sama sekali -- query ->get() lalu di-looping di PHP untuk clustering manual.
     * Di 45rb data, filter yang cocok ke ribuan baris (misal kata kunci umum "warung"
     * di zoom rendah tanpa bounding box sempit) akan menarik SEMUA baris itu ke memory
     * PHP sekaligus. Ini bomb waktu/memory yang nggak kelihatan pas testing pakai
     * data dummy kecil. Ditambahkan hard cap MAX_RAW_POINTS + flag `truncated` di
     * response supaya FE bisa kasih tau user "hasil dipersempit, coba zoom in/perkecil
     * filter" alih-alih diam-diam motong data tanpa penjelasan.
     */
    private const MAX_RAW_POINTS = 8000;

    public function index(Request $request)
    {
        $zoom  = (int) ($request->zoom ?? 9);
        $swLat = (float) $request->sw_lat;
        $swLng = (float) $request->sw_lng;
        $neLat = (float) $request->ne_lat;
        $neLng = (float) $request->ne_lng;

        $gridSize = match (true) {
            $zoom <= 9  => 0.10,
            $zoom <= 11 => 0.05,
            $zoom <= 13 => 0.02,
            $zoom <= 14 => 0.008,
            default     => null,
        };

        $cacheParams = [
            'v' => $this->cacheVersion(),
            'z' => $zoom,
            'grid' => $gridSize,
            'q' => $request->q,
            'kec' => $request->kecamatan,
            'kat' => $request->kategori,
            'st' => $request->status_klaim,
        ];

        if ($zoom > 10 && $swLat && $neLat && $swLng && $neLng) {
            $minLat = min($swLat, $neLat);
            $maxLat = max($swLat, $neLat);
            $minLng = min($swLng, $neLng);
            $maxLng = max($swLng, $neLng);
            $cacheParams['bbox'] = round($minLat, 2) . '_' . round($minLng, 2) . '_' . round($maxLat, 2) . '_' . round($maxLng, 2);
        } else {
            $cacheParams['bbox'] = 'all';
            $minLat = $maxLat = $minLng = $maxLng = null;
        }

        $cacheKey = 'umkm_grid_cluster_v1_' . md5(json_encode($cacheParams));

        $result = Cache::remember($cacheKey, 600, function () use ($request, $zoom, $gridSize, $minLat, $maxLat, $minLng, $maxLng) {
            $query = Umkm::active();

            if ($zoom > 10 && $minLat !== null && $maxLat !== null && $minLng !== null && $maxLng !== null) {
                $polygon = "POLYGON(($minLng $minLat, $maxLng $minLat, $maxLng $maxLat, $minLng $maxLat, $minLng $minLat))";
                $query->whereRaw("MBRContains(ST_GeomFromText(?, 4326, 'axis-order=long-lat'), location)", [$polygon]);
            }

            if ($request->filled('kecamatan')) {
                $query->where('kecamatan', $request->kecamatan);
            }
            if ($request->filled('kategori')) {
                $query->where('kategori_id', $request->kategori);
            }
            if ($request->filled('status_klaim')) {
                $query->where('status_klaim', $request->status_klaim);
            }
            if ($request->filled('q')) {
                $keyword = '%' . $request->q . '%';
                $query->where(function ($q) use ($keyword) {
                    $q->where('nama_usaha', 'like', $keyword)
                      ->orWhere('alamat', 'like', $keyword);
                });
            }

            // KASUS 1: Zoom Tinggi (>= 15) -> Kembalikan Titik Individual
            if ($gridSize === null) {
                $points = $query->withCoordinates()
                    ->with('kategori:id,nama,icon')
                    ->limit(2000)
                    ->get()
                    ->map(function ($item) {
                        return [
                            'type'          => 'point',
                            'id'            => $item->id,
                            'nama'          => $item->nama_usaha,
                            'nama_usaha'    => $item->nama_usaha,
                            'slug'          => $item->slug,
                            'kategori'      => $item->kategori?->nama ?? 'Umum',
                            'icon'          => $item->kategori?->icon ?? 'store',
                            'kecamatan'     => $item->kecamatan,
                            'alamat'        => $item->alamat,
                            'lat'           => (float) $item->latitude,
                            'lng'           => (float) $item->longitude,
                            'rating'        => (float) $item->rating,
                            'jumlah_review' => (int) $item->jumlah_review,
                            'status_klaim'  => $item->status_klaim,
                            'url'           => route('umkm.show', $item->slug),
                        ];
                    })->all();

                return [
                    'mode'        => 'points',
                    'zoom'        => $zoom,
                    'total_count' => count($points),
                    'data'        => $points,
                ];
            }

            // KASUS 2: Zoom Rendah/Sedang (< 15) -> Server-Side Grid Clustering
            $isUnfiltered = !$request->filled('kategori') && !$request->filled('q') && !$request->filled('status_klaim');
            $truncated = false;

            // Helper: aggregate an array of cells into clusters at a given grid resolution
            $aggregateCells = function (array $cells, float $gs) {
                $clusters = [];
                foreach ($cells as $cell) {
                    $lat = (float) ($cell->grid_lat ?? $cell['grid_lat']);
                    $lng = (float) ($cell->grid_lng ?? $cell['grid_lng']);
                    $count = (int) ($cell->jumlah_umkm ?? $cell['jumlah_umkm']);
                    $verifCount = (int) ($cell->terverifikasi_count ?? $cell['terverifikasi_count']);
                    $kec = $cell->kecamatan ?? $cell['kecamatan'] ?? null;

                    $gridLat = round($lat / $gs) * $gs;
                    $gridLng = round($lng / $gs) * $gs;
                    $key = sprintf('%.4f_%.4f', $gridLat, $gridLng);

                    if (!isset($clusters[$key])) {
                        $clusters[$key] = [
                            'grid_lat' => $gridLat,
                            'grid_lng' => $gridLng,
                            'lat_sum'  => 0.0,
                            'lng_sum'  => 0.0,
                            'count'    => 0,
                            'terverifikasi_count' => 0,
                            'kecamatans' => [],
                            'raw_cells' => [],
                        ];
                    }

                    $clusters[$key]['lat_sum'] += $lat * $count;
                    $clusters[$key]['lng_sum'] += $lng * $count;
                    $clusters[$key]['count'] += $count;
                    $clusters[$key]['terverifikasi_count'] += $verifCount;
                    $clusters[$key]['raw_cells'][] = $cell;

                    if ($kec) {
                        $clusters[$key]['kecamatans'][$kec] = ($clusters[$key]['kecamatans'][$kec] ?? 0) + $count;
                    }
                }
                return $clusters;
            };

            // Maximum UMKM per cluster before auto-splitting at higher zoom levels
            $maxClusterCount = 2000;

            if ($isUnfiltered && \Illuminate\Support\Facades\Schema::hasTable('umkm_grid_cluster')) {
                // Saat zoom <= 9 (tampilan seluruh Kabupaten Kutai Timur):
                // Kelompokkan secara administratif per kecamatan agar tiap wilayah memiliki
                // tepat 1 penanda kluster resmi di pusat geografisnya, tanpa tumpang tindih.
                if ($zoom <= 9 && !$request->filled('kecamatan')) {
                    $kecRows = DB::table('umkm_grid_cluster')
                        ->select(
                            'kecamatan',
                            DB::raw('SUM(jumlah_umkm) as total_umkm'),
                            DB::raw('SUM(terverifikasi_count) as total_verif'),
                            DB::raw('SUM(grid_lat * jumlah_umkm) / SUM(jumlah_umkm) as avg_lat'),
                            DB::raw('SUM(grid_lng * jumlah_umkm) / SUM(jumlah_umkm) as avg_lng')
                        )
                        ->groupBy('kecamatan')
                        ->get();

                    $clusters = [];
                    foreach ($kecRows as $r) {
                        $count = (int) $r->total_umkm;
                        $lat = (float) $r->avg_lat;
                        $lng = (float) $r->avg_lng;
                        $clusters[$r->kecamatan] = [
                            'grid_lat' => $lat,
                            'grid_lng' => $lng,
                            'lat_sum'  => $lat * $count,
                            'lng_sum'  => $lng * $count,
                            'count'    => $count,
                            'terverifikasi_count' => (int) $r->total_verif,
                            'kecamatans' => [$r->kecamatan => $count],
                            'raw_cells'  => [],
                        ];
                    }
                    $totalCount = (int) $kecRows->sum('total_umkm');
                } else {
                    $gridQuery = DB::table('umkm_grid_cluster');
                    if ($zoom > 10 && $minLat !== null && $maxLat !== null && $minLng !== null && $maxLng !== null) {
                        $gridQuery->whereBetween('grid_lat', [$minLat, $maxLat])
                                  ->whereBetween('grid_lng', [$minLng, $maxLng]);
                    }
                    if ($request->filled('kecamatan')) {
                        $gridQuery->where('kecamatan', $request->kecamatan);
                    }

                    $gridData = $gridQuery->get();
                    $totalCount = (int) $gridData->sum('jumlah_umkm');

                    // Initial aggregation
                    $clusters = $aggregateCells($gridData->all(), $gridSize);

                    // Auto-split oversized clusters by halving grid size (up to 3 levels)
                    for ($split = 0; $split < 3; $split++) {
                        $hasOversized = false;
                        $newClusters = [];
                        foreach ($clusters as $key => $c) {
                            if ($c['count'] > $maxClusterCount && count($c['raw_cells']) > 1) {
                                $hasOversized = true;
                                // Re-aggregate this cluster's cells at half the grid size
                                $subGridSize = $gridSize / pow(2, $split + 1);
                                $subClusters = $aggregateCells($c['raw_cells'], $subGridSize);
                                foreach ($subClusters as $sk => $sc) {
                                    $newClusters[$key . '_' . $sk] = $sc;
                                }
                            } else {
                                $newClusters[$key] = $c;
                            }
                        }
                        $clusters = $newClusters;
                        if (!$hasOversized) break;
                    }
                }
            } else {
                // Saat filter aktif dan zoom <= 9 tanpa bbox sempit:
                // Agregasi langsung per kecamatan untuk performa super cepat
                if ($zoom <= 9 && !$request->filled('kecamatan') && $minLat === null) {
                    $kecPoints = $query->selectRaw("
                        kecamatan,
                        COUNT(*) as count,
                        SUM(CASE WHEN status_klaim = 'terverifikasi' THEN 1 ELSE 0 END) as terverifikasi_count,
                        AVG(ST_Latitude(location)) as avg_lat,
                        AVG(ST_Longitude(location)) as avg_lng
                    ")->groupBy('kecamatan')->get();

                    $totalCount = (int) $kecPoints->sum('count');
                    $clusters = [];
                    foreach ($kecPoints as $kp) {
                        $cnt = (int) $kp->count;
                        $lat = (float) $kp->avg_lat;
                        $lng = (float) $kp->avg_lng;
                        $clusters[$kp->kecamatan] = [
                            'grid_lat' => $lat,
                            'grid_lng' => $lng,
                            'lat_sum'  => $lat * $cnt,
                            'lng_sum'  => $lng * $cnt,
                            'count'    => $cnt,
                            'terverifikasi_count' => (int) $kp->terverifikasi_count,
                            'kecamatans' => [$kp->kecamatan => $cnt],
                        ];
                    }
                } else {
                    // PATCH: cap jumlah baris raw yang ditarik untuk clustering manual di PHP.
                    $rawPoints = $query->selectRaw("
                        ST_Latitude(location) AS lat,
                        ST_Longitude(location) AS lng,
                        kecamatan,
                        status_klaim
                    ")->limit(self::MAX_RAW_POINTS + 1)->get();

                    if ($rawPoints->count() > self::MAX_RAW_POINTS) {
                        $truncated = true;
                        $rawPoints = $rawPoints->take(self::MAX_RAW_POINTS);
                    }

                    $totalCount = $rawPoints->count();
                    $clusters = [];

                    foreach ($rawPoints as $pt) {
                        $lat = (float) $pt->lat;
                        $lng = (float) $pt->lng;

                        if (!$lat || !$lng) continue;


                    $gridLat = round($lat / $gridSize) * $gridSize;
                    $gridLng = round($lng / $gridSize) * $gridSize;
                    $key = sprintf('%.4f_%.4f', $gridLat, $gridLng);

                    if (!isset($clusters[$key])) {
                        $clusters[$key] = [
                            'grid_lat' => $gridLat,
                            'grid_lng' => $gridLng,
                            'lat_sum'  => 0.0,
                            'lng_sum'  => 0.0,
                            'count'    => 0,
                            'terverifikasi_count' => 0,
                            'kecamatans' => [],
                        ];
                    }

                    $clusters[$key]['lat_sum'] += $lat;
                    $clusters[$key]['lng_sum'] += $lng;
                    $clusters[$key]['count']++;

                    if ($pt->status_klaim === 'terverifikasi') {
                        $clusters[$key]['terverifikasi_count']++;
                    }

                    if ($pt->kecamatan) {
                        $clusters[$key]['kecamatans'][$pt->kecamatan] = ($clusters[$key]['kecamatans'][$pt->kecamatan] ?? 0) + 1;
                    }
                }
            }
        }

            $clusterResults = [];
            foreach ($clusters as $c) {
                $count = $c['count'];
                $avgLat = round($c['lat_sum'] / $count, 6);
                $avgLng = round($c['lng_sum'] / $count, 6);

                arsort($c['kecamatans']);
                $topKecamatans = array_keys(array_slice($c['kecamatans'], 0, 2, true));
                $topKecamatanStr = !empty($topKecamatans) ? implode(', ', $topKecamatans) : 'Kutai Timur';

                $clusterResults[] = [
                    'type'                => 'cluster',
                    'lat'                 => $avgLat,
                    'lng'                 => $avgLng,
                    'count'               => $count,
                    'terverifikasi_count' => $c['terverifikasi_count'],
                    'label'               => $count >= 1000 ? round($count / 1000, 1) . 'k' : (string) $count,
                    'kecamatan'           => $topKecamatanStr,
                    'kecamatan_detail'    => $c['kecamatans'],
                ];
            }

            usort($clusterResults, fn($a, $b) => $b['count'] <=> $a['count']);

            return [
                'mode'          => 'clusters',
                'zoom'          => $zoom,
                'total_count'   => $totalCount,
                'cluster_count' => count($clusterResults),
                'truncated'     => $truncated,
                'data'          => $clusterResults,
            ];
        });

        return response()->json($result);
    }
}
