<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('info_galleries', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('image_path'); // Untuk simpan lokasi fail gambar
            $table->longText('description'); // longText supaya admin boleh letak banyak perenggan
            $table->boolean('is_active')->default(true); // Supaya admin boleh 'hide' poster tanpa memadamnya
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('info_galleries');
    }
};
