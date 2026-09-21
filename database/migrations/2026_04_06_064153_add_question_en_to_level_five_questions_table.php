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
        Schema::table('level_five_questions', function (Blueprint $table) {
            // Adds an optional English question column right after the original one
            $table->text('question_en')->nullable()->after('question');
        });
    }

    public function down(): void
    {
        Schema::table('level_five_questions', function (Blueprint $table) {
            $table->dropColumn('question_en');
        });
    }
};
