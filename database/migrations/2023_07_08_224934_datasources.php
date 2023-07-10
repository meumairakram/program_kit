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

        Schema::create('user_datasources', function (Blueprint $table) {

            $table->id();
            $table->string('name');
            $table->string('type');     // csv - more airtable, google sheets, excel in future
            $table->boolean('requires_mapping');
            $table->integer('records_count');
            $table->timestamp('last_synced');
            $table->integer('owner_id');
            
            $table->foreign('owner_id')->references('id')->on('users');
            $table->timestamps();

        });
        //
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('user_datasources');
    }
};
