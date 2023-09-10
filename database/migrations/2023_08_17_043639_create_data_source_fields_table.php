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
        Schema::create('data_source_fields', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('campaign_id'); 
            $table->text('data_source'); 
            $table->text('data_source_headers');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_source_fields');
    }
};
