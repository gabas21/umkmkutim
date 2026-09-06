<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('umkm', function (Blueprint $table) {
            $table->id();
            $table->string('nama_usaha', 255);
            $table->string('slug', 255)->unique();
            $table->unsignedInteger('kategori_id');
            $table->text('deskripsi')->nullable();
            $table->text('alamat');
            $table->string('kecamatan', 100);
            $table->string('kelurahan_desa', 100)->nullable();
            $table->geometry('location', subtype: 'point', srid: 4326);
            $table->string('telepon', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('instagram', 100)->nullable();
            $table->string('website', 255)->nullable();
            $table->string('foto_utama', 255)->nullable();
            $table->json('foto_galeri')->nullable();
            $table->json('jam_operasional')->nullable();
            $table->decimal('rating', 3, 2)->default(0.00);
            $table->unsignedInteger('jumlah_review')->default(0);
            $table->unsignedInteger('jumlah_dilihat')->default(0);
            $table->enum('sumber_data', ['import', 'mandiri'])->default('import');
            $table->enum('status_klaim', ['belum_diklaim', 'menunggu_verifikasi', 'terverifikasi'])->default('belum_diklaim');
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->timestamps();

            $table->spatialIndex('location', 'idx_location');
            $table->index('kategori_id', 'idx_kategori');
            $table->index('kecamatan', 'idx_kecamatan');
            $table->index('status_klaim', 'idx_status_klaim');
            $table->foreign('kategori_id', 'fk_umkm_kategori')->references('id')->on('kategori')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('umkm');
    }
};
