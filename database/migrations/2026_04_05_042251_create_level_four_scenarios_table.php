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
        Schema::create('level_four_scenarios', function (Blueprint $table) {
            $table->id();
            $table->string('url');
            $table->string('title');
            $table->text('clues'); // To hold the "Petunjuk (satu baris satu)"
            $table->string('image_path'); // This will store the actual uploaded file location
            $table->boolean('has_ssl')->default(false);
            $table->boolean('is_phishing')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('level_four_scenarios');
    }
};
