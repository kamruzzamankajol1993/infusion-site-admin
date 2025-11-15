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
        Schema::create('job_applicants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_id')
                  ->constrained('careers') // Link to the careers (job postings) table
                  ->onDelete('cascade'); // If the job posting is deleted, delete applications
            $table->string('full_name');
            $table->string('email');
            $table->string('phone_number');
            $table->text('qualification'); // Use text for potentially longer qualification details
            $table->string('cv'); // Path to the uploaded PDF file
            $table->text('additional_information')->nullable(); // Optional field
            $table->text('total_year_of_experience')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_applicants');
    }
};
