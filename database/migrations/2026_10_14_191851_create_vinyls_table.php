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
        //Vinyls table
        Schema::create('vinyls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('label_id');
            $table->foreignId('artist_connection_id');
            $table->foreignId('genre_connection_id');
            $table->string('format');
            $table->string('external_link');
            $table->timestamp('release_date');
            $table->foreignId('created_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vinyls');
    }
};
