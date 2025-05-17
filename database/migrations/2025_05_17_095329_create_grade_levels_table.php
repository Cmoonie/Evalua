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
        Schema::create('grade_levels', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('name', 32); // Naam van het beoordelingsniveau (bijv goed, voldoende, onvoldoende)
            $table->integer('points'); // Het aantal punten wat daar aan vast hangt
            $table->timestamps(); // Tijd en datum
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grade_levels');
    }
};
