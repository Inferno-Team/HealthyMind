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
        Schema::create('coach_timelines', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('coach_id')->nullable()->references('id')->on('users')->nullOnDelete();
            $table->foreignId('goal_plan_disease_id')->references('id')->on('goal_plan_diseases');
            $table->longText('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coach_timelines');
    }
};
