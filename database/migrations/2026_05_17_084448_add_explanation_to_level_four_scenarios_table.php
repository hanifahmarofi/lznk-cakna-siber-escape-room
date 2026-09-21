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
    Schema::table('level_four_scenarios', function (Blueprint $table) {
        // Adds the new column right after 'clues'
        $table->text('explanation')->nullable()->after('clues'); 
    });
}

public function down()
{
    Schema::table('level_four_scenarios', function (Blueprint $table) {
        // Removes the column if you ever need to rollback
        $table->dropColumn('explanation');
    });
}
};
