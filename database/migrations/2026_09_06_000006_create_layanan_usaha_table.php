<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('layanan_usaha', function (Blueprint $table) {
            $table->id();
            $table->foreignId('umkm_id')->constrained('umkm')->cascadeOnDelete();
            $table->string('nama_layanan', 255);
            $table->text('deskripsi')->nullable();
            $table->decimal('harga_mulai', 15, 2)->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();

            $table->index('umkm_id', 'idx_umkm');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('layanan_usaha');
    }
};
