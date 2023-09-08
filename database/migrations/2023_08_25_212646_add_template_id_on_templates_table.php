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

          Schema::table('templates', function (Blueprint $table) {

            $table->bigInteger('template_id');
        
        });
      

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //

        Schema::table('templates', function (Blueprint $table) {

            $table->dropColumn('template_id');
        
        });

        
    }
};
