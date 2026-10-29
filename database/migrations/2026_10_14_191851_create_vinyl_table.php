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
        Schema::create('vinyls', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('format');
            $table->string('external_link')->nullable();
            $table->unsignedBigInteger('discogs_id')->nullable()->unique();
            $table->string('cover_url', 500)->nullable();
            $table->boolean('intact')->default(true);
            $table->date('release_date')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
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
