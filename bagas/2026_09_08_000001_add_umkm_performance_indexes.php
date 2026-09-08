<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * PATCH (performance): tabel `umkm` sebelumnya PUNYA index untuk kategori_id,
 * kecamatan, status_klaim, dan spatial index location -- tapi TIDAK punya index
 * untuk kolom `status`. Padahal scope Umkm::active() -> where('status', 'active')
 * dipanggil di HAMPIR SEMUA query peta (viewport, cluster, count header) dan tanpa
 * index ini MySQL harus full-table-scan kolom status di setiap request.
 *
 * Ditambahkan juga composite index (status, kecamatan) karena kombinasi filter ini
 * (UMKM aktif + kecamatan tertentu) adalah query pattern paling sering dipakai baik
 * di peta maupun listing UMKM biasa.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('umkm', function (Blueprint $table) {
            $table->index('status', 'idx_status');
            $table->index(['status', 'kecamatan'], 'idx_status_kecamatan');
        });
    }

    public function down(): void
    {
        Schema::table('umkm', function (Blueprint $table) {
            $table->dropIndex('idx_status');
            $table->dropIndex('idx_status_kecamatan');
        });
    }
};
