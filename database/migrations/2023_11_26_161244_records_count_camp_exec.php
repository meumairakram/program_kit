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
            $table->bigInteger('found_records');
            $table->bigInteger('created_records');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table("campaign_exec_status", function(Blueprint $table) {
            $table->dropColumn('found_records');
            $table->dropColumn('created_records');
        });
    }
};
