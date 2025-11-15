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
        Schema::create('training_skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_id')
                  ->constrained('trainings') // Link to trainings table
                  ->onDelete('cascade'); // Delete skills if training is deleted
            $table->string('skill_name'); // Changed column name for clarity
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_skills');
    }
};
