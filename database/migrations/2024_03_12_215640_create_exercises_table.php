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
        Schema::create('exercises', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('media')->nullable();
            $table->foreignId('coach_id')
                ->nullable()
                ->references('id')
                ->on('users')
                ->nullOnDelete();

            $table->foreignId('type_id')
                ->nullable()
                ->references('id')
                ->on('exercise_types')
                ->nullOnDelete();
            $table->enum('muscle', [
                'abs',
                'quads',
                'glutes',
                'triceps',
                'biceps',
                'back',
                'chest',
            ]);
            $table->foreignId('equipment_id')->nullable()->references('id')
                ->on('exercise_equipment')->nullOnDelete();
            $table->string('duration')->nullable();
            $table->longText('description')->nullable();

            $table->enum('status', ['pending', 'approved', 'declined'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exercises');
    }
};
