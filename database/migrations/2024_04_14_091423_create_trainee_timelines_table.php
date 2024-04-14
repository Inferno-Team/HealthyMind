<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('trainee_timelines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trainee_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreignId('timeline_id')->nullable()->references('id')->on('coach_timelines')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trainee_timelines');
    }
};
