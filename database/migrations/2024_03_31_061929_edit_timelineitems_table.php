<?php

use App\Models\Day;
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
        Schema::table('timeline_items', function (Blueprint $table) {
            $table->timestamp('event_date_start')->nullable()->after('item_id');
            $table->timestamp('event_date_end')->nullable()->after('event_date_start');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
