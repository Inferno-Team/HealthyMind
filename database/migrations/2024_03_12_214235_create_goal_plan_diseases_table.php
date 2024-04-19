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
        Schema::create('goal_plan_diseases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_id')->references('id')->on('plans')->cascadeOnDelete();
            $table->longText('goal_ids')->nullable();
            $table->longText('disease_ids')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goal_plan_diseases');
    }
};
