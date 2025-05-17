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
        Schema::create('forms', function (Blueprint $table) {
            $table->id(); // Primary key natuurlijk
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // De FK van user
            $table->string('title', 32); // De titel van het formulier
            $table->string('subject', 32); // Het vak waar het formulier over gaat
            $table->text('description'); // Beschrijving van het formulier
            $table->timestamps(); // Datums etc
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forms');
    }
};
