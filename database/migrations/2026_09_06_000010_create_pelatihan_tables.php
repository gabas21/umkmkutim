<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pelatihans', function (Blueprint $table) {
            $table->id();
            $table->string('judul', 255);
            $table->string('slug', 255)->unique();
            $table->longText('deskripsi');
            $table->text('materi_ringkas')->nullable();
            $table->string('penyelenggara', 255)->default('Dinas Koperasi & UKM Kab. Kutai Timur');
            $table->string('instruktur', 255)->nullable();
            $table->string('lokasi', 255)->default('Gedung Diklat Bukit Pelangi, Sangatta');
            $table->enum('mode', ['offline', 'online', 'hybrid'])->default('offline');
            $table->dateTime('tanggal_mulai');
            $table->dateTime('tanggal_selesai');
            $table->integer('kuota')->default(40);
            $table->decimal('biaya', 12, 2)->default(0.00);
            $table->string('link_zoom', 255)->nullable();
            $table->string('banner_url', 255)->nullable();
            $table->enum('status', ['upcoming', 'ongoing', 'selesai', 'closed'])->default('upcoming');
            $table->text('syarat_peserta')->nullable();
            $table->timestamps();

            $table->index('status', 'idx_pelatihan_status');
            $table->index('tanggal_mulai', 'idx_pelatihan_tanggal');
        });

        Schema::create('pelatihan_pesertas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelatihan_id')->constrained('pelatihans')->onDelete('cascade');
            $table->foreignId('pelaku_usaha_id')->nullable()->constrained('pelaku_usaha')->onDelete('set null');
            $table->string('nama_peserta', 255);
            $table->string('nama_usaha', 255)->nullable();
            $table->string('email', 100);
            $table->string('nomor_hp', 30);
            $table->string('instansi', 255)->nullable();
            $table->text('motivasi')->nullable();
            $table->enum('status', ['terdaftar', 'hadir', 'tidak_hadir'])->default('terdaftar');
            $table->timestamps();

            $table->index(['pelatihan_id', 'status'], 'idx_peserta_pelatihan_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pelatihan_pesertas');
        Schema::dropIfExists('pelatihans');
    }
};
