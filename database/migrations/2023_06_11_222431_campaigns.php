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

        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description');
            $table->string('type');    // wordpress //webflow  // bubble
            $table->unsignedBigInteger('wp_template_id');
            $table->string('post_type');
            $table->string('status');   // ready   //in_progress   //complete
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
        Schema::dropIfExists('campaigns');
    }
};
