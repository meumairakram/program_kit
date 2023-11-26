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

        Schema::table("campaign_exec_status", function(Blueprint $table) {
            $table->bigInteger('found_records')->default(0);
            $table->bigInteger('created_records')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table("campaign_exec_status", function(Blueprint $table) {
            $table->bigInteger('found_records')->default(null);
            $table->bigInteger('created_records')->default(null);
        });
    }
};
