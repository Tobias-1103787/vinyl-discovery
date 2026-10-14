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
        //Artist_connection table
        Schema::create('artist_connection', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vinyl_id');
            $table->foreignId('artist_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artist_connection');
    }
};
