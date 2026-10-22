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
            $table->foreignId('label_id')->constrained();
            $table->string('format');
            $table->string('external_link');
            $table->timestamp('release_date');
            $table->foreignId('created_by')->constrained();
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
