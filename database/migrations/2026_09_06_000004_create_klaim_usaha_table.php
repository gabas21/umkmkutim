<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('klaim_usaha', function (Blueprint $table) {
            $table->id();
            $table->foreignId('umkm_id')->constrained('umkm')->cascadeOnDelete();
            $table->foreignId('pelaku_usaha_id')->constrained('pelaku_usaha')->cascadeOnDelete();
            $table->string('dokumen_ktp', 255);
            $table->string('dokumen_bukti_usaha', 255)->nullable();
            $table->text('catatan_pemohon')->nullable();
            $table->enum('status', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu');
            $table->text('catatan_admin')->nullable();
            $table->foreignId('diverifikasi_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('diverifikasi_pada')->nullable();
            $table->timestamps();

            $table->index('status', 'idx_status');
            $table->index('umkm_id', 'idx_umkm');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('klaim_usaha');
    }
};
