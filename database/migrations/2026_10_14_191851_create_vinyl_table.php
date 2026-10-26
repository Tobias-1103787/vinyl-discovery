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
        //Vinyl table
        Schema::create('vinyl', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('label_id')->constrained();
            $table->string('format');
            $table->string('external_link')->nullable();
            $table->unsignedBigInteger('discogs_id')->nullable()->index();
            $table->string('cover_url', 500)->nullable();
            $table->date('release_date');
            $table->foreignId('created_by')->constrained('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vinyl');
    }
};
