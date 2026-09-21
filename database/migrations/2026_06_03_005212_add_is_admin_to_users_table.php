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
        Schema::table('users', function (Blueprint $table) {
            // We use ->after() to place it neatly next to current_lives in the database viewer
            $table->boolean('is_admin')->default(0)->after('current_lives');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // This safely removes ONLY the is_admin column if you ever need to rollback
            $table->dropColumn('is_admin');
        });
    }
};
