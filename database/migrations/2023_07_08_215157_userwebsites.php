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
        Schema::create('user_websites', function (Blueprint $table) {
            $table->id();
            $table->string('website_name');
            
            $table->string('website_url');
            $table->string('type');
            $table->string('is_authenticated');
            $table->string('authentication_key');
            $table->string("request_url")->nullable();

            $table->unsignedBigInteger('owner_id');
            $table->foreign('owner_id')->references('id')->on('users');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_websites');
    }
};
