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
        Schema::create('meals', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('type_id')->references('id')->on('meal_types');
            $table->unsignedBigInteger('qty');
            $table->foreignId('qty_type_id')
                ->nullable()
                ->default(null)
                ->references('id')
                ->on('quantity_types')
                ->nullOnDelete();
            $table->foreignId('coach_id')
                ->nullable()
                ->references('id')
                ->on('users')
                ->nullOnDelete();
            $table->longText("ingredients")->nullable();
            $table->longText("description")->nullable();
            $table->enum('status', ['pending', 'approved', 'declined'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meals');
    }
};
