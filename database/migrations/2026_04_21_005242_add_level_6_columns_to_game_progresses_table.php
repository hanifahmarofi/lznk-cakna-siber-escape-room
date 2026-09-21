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
            // Adding the new Room 6 tracking columns
            $table->boolean('level_6_completed')->default(false);
            $table->integer('level_6_score')->default(0);
            $table->integer('level_6_correct')->default(0);
            $table->integer('level_6_incorrect')->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('game_progresses', function (Blueprint $table) {
            // Drop them if we ever rollback
            $table->dropColumn([
                'level_6_completed', 
                'level_6_score', 
                'level_6_correct', 
                'level_6_incorrect'
            ]);
        });
    }
};
