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
        Schema::table('game_progresses', function (Blueprint $table) {
            // Adds a JSON column to store step-by-step history
            $table->json('custom_room_history')->nullable()->after('custom_room_scores');
        });
    }

    public function down(): void
    {
        Schema::table('game_progresses', function (Blueprint $table) {
            $table->dropColumn('custom_room_history');
        });
    }
};
