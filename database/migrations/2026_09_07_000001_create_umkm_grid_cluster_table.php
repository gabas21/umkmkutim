<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('umkm_grid_cluster', function (Blueprint $table) {
            $table->id();
            $table->decimal('grid_lat', 9, 4);
            $table->decimal('grid_lng', 9, 4);
            $table->unsignedInteger('jumlah_umkm');
            $table->unsignedInteger('terverifikasi_count')->default(0);
            $table->string('kecamatan', 100)->nullable();
            $table->unsignedBigInteger('sample_umkm_id')->nullable();
            $table->timestamps();

            $table->unique(['grid_lat', 'grid_lng'], 'uq_grid');
            $table->index('grid_lat');
            $table->index('grid_lng');
            $table->index('kecamatan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('umkm_grid_cluster');
    }
};
