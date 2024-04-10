<?php

use App\Models\Coach;
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
        Schema::table('exercises', function (Blueprint $table) {

            $table->foreignId('coach_id')
                ->after('gif_url')
                ->nullable()
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exercises', function (Blueprint $table) {
            $table->dropForeignIdFor(Coach::class, 'coach_id');
        });
        Schema::dropColumns('exercises', ['coach_id']);
    }
};
