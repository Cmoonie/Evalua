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
    { // TUSSENTABEL
        Schema::create('component_levels', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->foreignId('component_id')->constrained()->onDelete('cascade'); // FK van component
            $table->foreignId('grade_level_id')->constrained()->onDelete('cascade'); // FK van beoordelingsniveau
            $table->text('description'); // Beschrijving van onvoldoende, goed en voldoende
            $table->timestamps(); // Tijd en datum
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('component_levels');
    }
};
