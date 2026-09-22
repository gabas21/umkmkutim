<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('type', ['pelatihan', 'bazar']);
            $table->dateTime('start_date');
            $table->dateTime('end_date')->nullable();
            $table->string('location')->nullable();
            $table->text('description')->nullable();
            $table->unsignedInteger('quota')->nullable();
            $table->enum('status', ['draft', 'open', 'closed'])->default('draft');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
