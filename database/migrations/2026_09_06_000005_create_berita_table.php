<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('berita', function (Blueprint $table) {
            $table->id();
            $table->string('judul', 255);
            $table->string('slug', 255)->unique();
            $table->longText('konten');
            $table->string('thumbnail', 255)->nullable();
            $table->enum('kategori', ['berita', 'pengumuman', 'tips'])->default('berita');
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->index('published_at', 'idx_published');
            $table->index('kategori', 'idx_kategori');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('berita');
    }
};
