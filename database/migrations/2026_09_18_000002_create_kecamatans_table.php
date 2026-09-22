<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kecamatans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->nullable();
            $table->text('geojson')->nullable();
            $table->unsignedBigInteger('peta_file_id')->nullable();
            $table->timestamps();
            $table->index('peta_file_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kecamatans');
    }
};
