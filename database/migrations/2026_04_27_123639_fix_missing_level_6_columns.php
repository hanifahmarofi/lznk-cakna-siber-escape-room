<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('game_progresses', function (Blueprint $table) {
            // Safely check and add each column ONLY if it doesn't already exist
            if (!Schema::hasColumn('game_progresses', 'level_6_correct')) {
                $table->integer('level_6_correct')->default(0)->after('level_6_score');
            }
            
            if (!Schema::hasColumn('game_progresses', 'level_6_incorrect')) {
                $table->integer('level_6_incorrect')->default(0)->after('level_6_score');
            }
            
            if (!Schema::hasColumn('game_progresses', 'level_6_wrong_answers')) {
                $table->json('level_6_wrong_answers')->nullable()->after('level_6_score');
            }
        });
    }

    public function down()
    {
        Schema::table('game_progresses', function (Blueprint $table) {
            if (Schema::hasColumn('game_progresses', 'level_6_correct')) {
                $table->dropColumn('level_6_correct');
            }
            if (Schema::hasColumn('game_progresses', 'level_6_incorrect')) {
                $table->dropColumn('level_6_incorrect');
            }
            if (Schema::hasColumn('game_progresses', 'level_6_wrong_answers')) {
                $table->dropColumn('level_6_wrong_answers');
            }
        });
    }
};