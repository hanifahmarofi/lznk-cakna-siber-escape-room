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
        Schema::create('mainframe_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('total_minutes')->default(5);
            $table->integer('seconds_per_question')->default(10);
            $table->integer('streak_threshold_seconds')->default(6);
            $table->integer('streak_bonus_percent')->default(20);
            $table->integer('max_mistakes')->default(3);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mainframe_settings');
    }
};
