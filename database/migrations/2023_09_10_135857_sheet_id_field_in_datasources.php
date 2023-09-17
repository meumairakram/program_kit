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
        Schema::table('user_datasources', function (Blueprint $table) {
            //
            
            $table->string('sheet_id');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_datasources', function (Blueprint $table) {
            //

            $table->dropColumn('sheet_id');
        });
    }
};
