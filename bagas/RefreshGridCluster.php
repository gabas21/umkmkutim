<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class RefreshGridCluster extends Command
{
    protected $signature = 'umkm:refresh-grid';
    protected $description = 'Perbarui tabel umkm_grid_cluster untuk performa peta cepat pada skala besar';

    public function handle()
    {
        $this->info('Memulai pembaruan grid cluster UMKM...');
        $startTime = microtime(true);

        DB::statement("TRUNCATE TABLE umkm_grid_cluster");

        DB::statement("
            INSERT INTO umkm_grid_cluster (grid_lat, grid_lng, jumlah_umkm, terverifikasi_count, kecamatan, sample_umkm_id, created_at, updated_at)
            SELECT 
                ROUND(ST_Latitude(location), 2) as grid_lat,
                ROUND(ST_Longitude(location), 2) as grid_lng,
                COUNT(*) as jumlah_umkm,
                SUM(CASE WHEN status_klaim = 'terverifikasi' THEN 1 ELSE 0 END) as terverifikasi_count,
                SUBSTRING_INDEX(GROUP_CONCAT(DISTINCT kecamatan ORDER BY kecamatan SEPARATOR ', '), ', ', 1) as kecamatan,
                MIN(id) as sample_umkm_id,
                NOW(),
                NOW()
            FROM umkm
            WHERE status = 'active'
            GROUP BY grid_lat, grid_lng
        ");

        // PATCH: bump versi cache supaya SEMUA response /api/umkm/clusters yang lama
        // (masih ke-cache sampai 10 menit ke depan) langsung dianggap basi begitu grid
        // baru ini kelar dibangun -- tanpa nunggu TTL habis dan tanpa perlu cache driver
        // yang support tags. Lihat UmkmClusterController::cacheVersion().
        $newVersion = ((int) Cache::get('umkm_cluster_cache_ver', 1)) + 1;
        Cache::forever('umkm_cluster_cache_ver', $newVersion);

        $duration = round((microtime(true) - $startTime) * 1000, 2);
        $totalRows = DB::table('umkm_grid_cluster')->count();
        $totalUmkm = DB::table('umkm_grid_cluster')->sum('jumlah_umkm');

        $this->info("Grid cluster berhasil diperbarui ({$duration}ms). Total {$totalRows} grid sel mencakup {$totalUmkm} UMKM. Cache version -> {$newVersion}.");
        return Command::SUCCESS;
    }
}
