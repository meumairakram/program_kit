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

        Schema::create('campaign_exec_logs', function(Blueprint $table) {

            $table->id();

            $table->unsignedBigInteger('campaign_id');
            $table->string('ds_type');    //csv or gsheet

            $table->string('data_address');
            $table->string('exec_type');    // change or new
            $table->string('status');   //completed 

            $table->timestamps();

        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //

        Schema::dropIfExists('campaign_exec_logs');


    }
};
