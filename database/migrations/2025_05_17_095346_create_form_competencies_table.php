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
        Schema::create('form_competencies', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->foreignId('form_id')->constrained()->onDelete('cascade'); // FK van het formulier
            $table->foreignId('competency_id')->constrained()->onDelete('cascade'); // FK van comtetentie
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_competencies');
    }
};
