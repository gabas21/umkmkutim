<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bazars', function (Blueprint $table) {
            $table->id();
            $table->string('nama_bazar', 255);
            $table->string('slug', 255)->unique();
            $table->longText('deskripsi');
            $table->string('lokasi', 255);
            $table->string('kecamatan', 100)->default('Sangatta Utara');
            $table->text('alamat_lengkap')->nullable();
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->integer('kuota_peserta')->default(50);
            $table->string('banner_url', 255)->nullable();
            $table->enum('status', ['upcoming', 'ongoing', 'closed', 'selesai'])->default('upcoming');
            $table->string('penyelenggara', 255)->default('Dinas Koperasi & UKM Kab. Kutai Timur');
            $table->string('kontak_person', 100)->nullable();
            $table->text('fasilitas')->nullable();
            $table->timestamps();

            $table->index('status', 'idx_bazar_status');
            $table->index('tanggal_mulai', 'idx_bazar_tanggal');
        });

        Schema::create('bazar_pesertas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bazar_id')->constrained('bazars')->onDelete('cascade');
            $table->foreignId('pelaku_usaha_id')->nullable()->constrained('pelaku_usaha')->onDelete('set null');
            $table->string('nama_pemilik', 255);
            $table->string('nama_usaha', 255);
            $table->string('kategori_produk', 100);
            $table->string('nomor_hp', 30);
            $table->string('email', 100)->nullable();
            $table->text('deskripsi_produk')->nullable();
            $table->text('catatan')->nullable();
            $table->enum('status', ['pending', 'diterima', 'ditolak'])->default('pending');
            $table->timestamps();

            $table->index(['bazar_id', 'status'], 'idx_peserta_bazar_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bazar_pesertas');
        Schema::dropIfExists('bazars');
    }
};
