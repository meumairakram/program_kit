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

        Schema::table('campaigns', function (Blueprint $table) {

            $table->bigInteger('data_source_id');
            $table->bigInteger('website_id');

        });

        //
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //

          Schema::table('campaigns', function (Blueprint $table) {

            $table->dropColumn('data_source_id');
            $table->dropColumn('website_id');
                

        });
    }
};
