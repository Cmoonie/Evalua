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
        Schema::create('components', function (Blueprint $table) {
            $table->id(); // Primary keyyyy
            $table->foreignId('competency_id')->constrained()->onDelete('cascade'); // FK van competentie
            $table->string('name', 32); // Naam van de component
            $table->text('description'); // Beschrijving (niet nullable want vul maar in hoor, geef de docenten extra werk)
            $table->timestamps(); // Datum en tijd enzo
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('components');
    }
};
