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
        //

        Schema::table('user_datasources', function(Blueprint $table) {

            $table->string('file_path')->nullable()->change();
            $table->string('sheet_id')->nullable()->change();
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('user_datasources', function(Blueprint $table) {

            $table->string('file_path')->nullable(false)->change();
            $table->string('sheet_id')->nullable(false)->change();
        
        });
    }
};
