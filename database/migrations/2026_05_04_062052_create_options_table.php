<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('options', function (Blueprint $table) {
            $table->id();
            // Foreign key to the question this option belongs to
            $table->foreignId('question_id')->constrained('questions')->onDelete('cascade');
            
            $table->text('text');
            $table->string('text_en')->nullable();
            $table->integer('points')->default(0);
            $table->text('feedback')->nullable();
            
            // Branching Logic: Foreign key to the NEXT question
            $table->foreignId('next_question_id')->nullable()->constrained('questions')->onDelete('set null');
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('options');
    }
};
