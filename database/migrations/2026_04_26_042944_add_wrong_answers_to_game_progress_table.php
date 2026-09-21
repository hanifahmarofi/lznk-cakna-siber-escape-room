<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Notice the 'es' at the end of game_progresses
        Schema::table('game_progresses', function (Blueprint $table) {
            $table->json('level_1_wrong_answers')->nullable();
            $table->json('level_2_wrong_answers')->nullable();
            $table->json('level_3_wrong_answers')->nullable();
            $table->json('level_4_wrong_answers')->nullable();
            $table->json('level_5_wrong_answers')->nullable();
        });
    }

    public function down()
    {
        Schema::table('game_progresses', function (Blueprint $table) {
            $table->dropColumn([
                'level_1_wrong_answers', 
                'level_2_wrong_answers', 
                'level_3_wrong_answers', 
                'level_4_wrong_answers', 
                'level_5_wrong_answers'
            ]);
        });
    }
};