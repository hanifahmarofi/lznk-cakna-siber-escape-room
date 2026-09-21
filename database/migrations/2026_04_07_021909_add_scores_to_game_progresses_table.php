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
            $table->integer('level_1_score')->default(0)->after('level_1_completed');
            $table->integer('level_2_score')->default(0)->after('level_2_completed');
            $table->integer('level_3_score')->default(0)->after('level_3_completed');
            $table->integer('level_4_score')->default(0)->after('level_4_completed');
            $table->integer('level_5_score')->default(0)->after('level_5_completed');
            $table->integer('total_score')->default(0)->after('user_id');
        });
    }

    public function down(): void
    {
        Schema::table('game_progresses', function (Blueprint $table) {
            $table->dropColumn(['level_1_score', 'level_2_score', 'level_3_score', 'level_4_score', 'level_5_score', 'total_score']);
        });
    }
};
