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
        //Genre_connection table
        Schema::create('genre_connection', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vinyl_id');
            $table->foreignId('genre_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('genre_connection');
    }
};
