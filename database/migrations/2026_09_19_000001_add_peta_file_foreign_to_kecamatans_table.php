<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kecamatans', function (Blueprint $table) {
            $table->foreign('peta_file_id')->references('id')->on('peta_files')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('kecamatans', function (Blueprint $table) {
            $table->dropForeign(['peta_file_id']);
        });
    }
};
