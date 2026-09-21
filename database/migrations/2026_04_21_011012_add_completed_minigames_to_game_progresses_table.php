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
            // This JSON column will store an array of completed game IDs (e.g. [1, 3, 5])
            $table->json('completed_minigames')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('game_progresses', function (Blueprint $table) {
            //
        });
    }
};
