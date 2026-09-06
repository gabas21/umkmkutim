<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pelaku_usaha', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 255);
            $table->string('email', 100)->unique();
            $table->string('password', 255);
            $table->string('nomor_telepon', 20)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->enum('status', ['active', 'pending', 'banned'])->default('pending');
            $table->rememberToken();
            $table->timestamps();

            $table->index('email', 'idx_email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pelaku_usaha');
    }
};
