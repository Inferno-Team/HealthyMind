<?php

use App\Models\Coach;
use App\Models\QuantityType;
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
        Schema::table('meals', function (Blueprint $table) {
            $table->foreignId('qty_type_id')
                ->after('qty')
                ->nullable()
                ->default(null)
                ->references('id')
                ->on('quantity_types')
                ->nullOnDelete();
            $table->foreignId('coach_id')
                ->after('qty_type_id')
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
        Schema::table('meals', function (Blueprint $table) {
            $table->dropForeignIdFor(Coach::class, 'coach_id');
            $table->dropForeignIdFor(QuantityType::class, 'qty_type_id');
        });
        Schema::dropColumns('meals', ['qty_type_id', 'coach_id']);
    }
};
