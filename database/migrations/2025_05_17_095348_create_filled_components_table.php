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
        Schema::create('filled_components', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->foreignId('component_id')->constrained()->onDelete('cascade'); // FK van component
            $table->foreignId('grade_level_id')->constrained()->onDelete('cascade'); // FK van het beoordelingsniveau
            $table->foreignId('filled_form_id')->constrained()->onDelete('cascade'); // FK van het ingevulde formulier
            $table->text('comment')->nullable(); // Omdat de docenten niet altijd tijd hebben om commentaar te geven
            $table->timestamps(); // Tijd, datum etc
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('filled_components');
    }
};
