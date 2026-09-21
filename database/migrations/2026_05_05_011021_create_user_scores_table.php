<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('user_scores', function (Blueprint $table) {
            $table->id();
            
            // Link to the specific staff member
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Link to the specific module/room they played
            $table->foreignId('room_id')->constrained('rooms')->onDelete('cascade');
            
            // The points they earned
            $table->integer('score')->default(0);
            
            // JSON array storing every question they answered and choice they made
            $table->json('history')->nullable();
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_scores');
    }
};