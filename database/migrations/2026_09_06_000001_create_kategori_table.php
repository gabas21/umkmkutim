<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kategori', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nama', 100);
            $table->string('slug', 100)->unique();
            $table->string('icon', 50)->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kategori');
    }
};
