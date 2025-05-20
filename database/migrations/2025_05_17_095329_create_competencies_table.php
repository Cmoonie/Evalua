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
        Schema::create('competencies', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('name', 32); // Naam van de competentie
            $table->text('domain_description'); // Domeinbeschrijving (deze en die eronder wil de Stakeholder laten zien bij elke competentie)
            $table->text('rating_scale'); // Beoordelingsschaal
            $table->text('complexity'); // Complexiteit
            $table->timestamps(); // Datum en tijd enzo
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('competencies');
    }
};
