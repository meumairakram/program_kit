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
            //

            $table->unsignedBigInteger('owner_id');
            $table->foreign('owner_id')->references('id')->on('users');

        });

        Schema::table('users', function (Blueprint $table) {
            //

            $table->unsignedBigInteger('campaigns')->nullable();
            $table->foreign('campaigns')->references('id')->on('campaigns')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            //
            $table->dropForeign(['owner_id']);
        });

        Schema::table('users', function (Blueprint $table) {
            //
            $table->dropForeign(['campaigns']);
        });
    }
};
