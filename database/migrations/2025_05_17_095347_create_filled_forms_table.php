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
        Schema::create('filled_forms', function (Blueprint $table) {
            $table->id(); // Primary Keyy :)
            $table->foreignId('form_id')->constrained()->onDelete('cascade'); // FK van formulier
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // FK van gebruiker (eerste examinator)
            $table->string('student_name', 64); // 64 letters is hopelijk lang genoeg voor studentnaam
            $table->string('student_number', 64); // Studentnummer
            $table->string('assignment', 100); // Titel van de opdracht
            $table->string('business_name', 100)->nullable(); // Bedrijfsnaam
            $table->string('business_location', 100)->nullable(); // Bedrijfslocatie
            $table->date('start_date')->nullable(); // Begindatum opdracht
            $table->date('end_date')->nullable(); // Einddatum opdracht
            $table->string('examinator', 64)->nullable(); // Tweede examinator
            $table->string('comment')->nullable(); // Algemeen commentaar
            $table->timestamps(); // Datum, tijd, alles
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('filled_forms');
    }
};
