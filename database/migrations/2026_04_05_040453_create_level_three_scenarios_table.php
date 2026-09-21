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
        Schema::create('level_three_scenarios', function (Blueprint $table) {
            $table->id();
            $table->string('platform'); // whatsapp, sms, telegram, call
            $table->string('sender_name');
            $table->text('message');
            $table->text('explanation'); // Penjelasan
            $table->boolean('is_threat')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('level_three_scenarios');
    }
};
