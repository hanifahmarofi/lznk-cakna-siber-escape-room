<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('title_en')->nullable();
            $table->text('description')->nullable();
            $table->integer('pass_mark')->default(50);
            $table->text('description_en')->nullable();
            
            // Video Fields
            $table->text('video_url')->nullable();
            $table->string('video_path')->nullable();
            $table->text('video_description')->nullable();
            $table->integer('video_duration')->default(15);
            
            $table->text('scenario')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rooms');
    }
};