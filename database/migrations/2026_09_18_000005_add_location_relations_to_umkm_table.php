<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('umkm', function (Blueprint $table) {
            $table->foreignId('kecamatan_id')->nullable()->after('kecamatan')->constrained('kecamatans')->nullOnDelete();
            $table->foreignId('kelurahan_id')->nullable()->after('kelurahan_desa')->constrained('kelurahans')->nullOnDelete();

            $table->index('kecamatan_id', 'idx_umkm_kecamatan_id');
            $table->index('kelurahan_id', 'idx_umkm_kelurahan_id');
        });
    }

    public function down(): void
    {
        Schema::table('umkm', function (Blueprint $table) {
            $table->dropForeign(['kecamatan_id']);
            $table->dropForeign(['kelurahan_id']);
            $table->dropIndex('idx_umkm_kecamatan_id');
            $table->dropIndex('idx_umkm_kelurahan_id');
            $table->dropColumn(['kecamatan_id', 'kelurahan_id']);
        });
    }
};
