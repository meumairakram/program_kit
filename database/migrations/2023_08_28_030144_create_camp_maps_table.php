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
        Schema::create('camp_maps', function (Blueprint $table) {
            $table->id();
            $table->string('campaign_id'); 
            $table->string('field_header');
            $table->string('template_variable');
            $table->string('val_type')->nullable();
            $table->string('meta')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('camp_maps');
    }
};
