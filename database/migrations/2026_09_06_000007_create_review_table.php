<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('review', function (Blueprint $table) {
            $table->id();
            $table->foreignId('umkm_id')->constrained('umkm')->cascadeOnDelete();
            $table->unsignedTinyInteger('rating');
            $table->text('komentar')->nullable();
            $table->string('nama_reviewer', 100)->nullable();
            $table->timestamps();

            $table->index('umkm_id', 'idx_umkm');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('review');
    }
};
