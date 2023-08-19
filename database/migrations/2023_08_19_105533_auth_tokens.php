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

        Schema::create("auth_tokens", function(Blueprint $table) {
        
            $table->id();   
            $table->unsignedBigInteger("owner_id");
            $table->string('auth_type');
            $table->string('key_type');
            $table->string('key_value');
            // $table->string('sheet_path');
            $table->timestamps();

            $table->foreign('owner_id')->references('id')->on('users');


        
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //

        Schema::dropIfExists("auth_tokens");
    }
};
