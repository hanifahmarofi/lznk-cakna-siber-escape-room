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
    Schema::create('mini_games', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        // game_type will be: 'scramble', 'domino', 'connect', 'video_abcd', or 'chess'
        $table->string('game_type'); 
        $table->integer('base_score')->default(100);
        $table->text('instruction')->nullable();
        
        // The magic column! This will hold game-specific settings.
        // Example for Video ABCD: {"video_url": "...", "options": ["A", "B", "C", "D"], "correct": "A"}
        // Example for Chess: {"statement": "...", "is_true": true, "steps": 2}
        $table->json('game_data'); 
        
        $table->boolean('is_active')->default(true);
        $table->integer('sort_order')->default(0);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mini_games');
    }
};
