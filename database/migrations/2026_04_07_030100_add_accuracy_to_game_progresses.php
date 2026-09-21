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
            for ($i = 1; $i <= 5; $i++) {
                $table->integer("level_{$i}_correct")->default(0)->after("level_{$i}_score");
                $table->integer("level_{$i}_incorrect")->default(0)->after("level_{$i}_correct");
            }
        });
    }

    public function down(): void
    {
        Schema::table('game_progresses', function (Blueprint $table) {
            for ($i = 1; $i <= 5; $i++) {
                $table->dropColumn(["level_{$i}_correct", "level_{$i}_incorrect"]);
            }
        });
    }
};
