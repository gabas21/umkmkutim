<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('survey_kepuasan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelaku_usaha_id')->nullable()->constrained('pelaku_usaha')->onDelete('set null');
            $table->string('nama_responden', 255)->nullable();
            $table->string('pekerjaan', 100)->nullable();
            $table->enum('modul', ['umkm', 'bazar', 'pelatihan', 'layanan_umum'])->default('layanan_umum');
            $table->unsignedTinyInteger('nilai_kemudahan')->default(5);
            $table->unsignedTinyInteger('nilai_kecepatan')->default(5);
            $table->unsignedTinyInteger('nilai_keramahan')->default(5);
            $table->unsignedTinyInteger('nilai_kemanfaatan')->default(5);
            $table->text('saran_teks')->nullable();
            $table->timestamps();

            $table->index('modul', 'idx_survey_modul');
            $table->index('created_at', 'idx_survey_created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('survey_kepuasan');
    }
};
