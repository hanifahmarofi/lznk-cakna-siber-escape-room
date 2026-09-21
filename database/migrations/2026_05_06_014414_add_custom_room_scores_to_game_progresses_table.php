<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('game_progresses', function (Blueprint $table) {
            $table->json('custom_room_scores')->nullable()->after('completed_minigames');
        });
    }

    public function down(): void
    {
        Schema::table('game_progresses', function (Blueprint $table) {
            $table->dropColumn('custom_room_scores');
        });
    }
};