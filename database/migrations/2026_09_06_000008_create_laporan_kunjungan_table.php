<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_kunjungan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('umkm_id')->constrained('umkm')->cascadeOnDelete();
            $table->date('tanggal');
            $table->unsignedInteger('jumlah_dilihat')->default(0);
            $table->timestamps();

            $table->unique(['umkm_id', 'tanggal'], 'uq_umkm_tanggal');
            $table->index('tanggal', 'idx_tanggal');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_kunjungan');
    }
};
