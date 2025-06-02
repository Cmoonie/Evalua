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
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // FK van gebruiker
            $table->string('student_name', 64); // 64 letters is hopelijk lang genoeg voor studentnaam
            $table->string('student_number', 64);
            $table->string('assignment', 100);
            $table->string('business_name', 100)->nullable();
            $table->string('business_location', 100)->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
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
