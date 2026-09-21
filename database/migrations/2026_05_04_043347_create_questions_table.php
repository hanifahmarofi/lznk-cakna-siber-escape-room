<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            // Foreign key to rooms table
            $table->foreignId('room_id')->constrained('rooms')->onDelete('cascade');
            
            $table->text('text');
            $table->text('text_en')->nullable();
            $table->integer('level')->default(1);
            
            // Video Fields for specific questions
            $table->string('video_url')->nullable();
            $table->string('video_path')->nullable();
            $table->text('video_description')->nullable();
            $table->integer('video_duration')->nullable();
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('questions');
    }
};