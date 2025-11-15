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
        Schema::create('careers', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('company_name');
            $table->string('position');
            $table->longText('qualification'); // Use longText for potentially long descriptions
            $table->string('age');             // Store as string (e.g., "25-35 years", "Not exceeding 30 years")
            $table->string('experience');      // Store as string (e.g., "Minimum 2 years", "2-3 years")
            $table->string('job_location');
            $table->longText('description');   // Use longText for job details
            $table->date('application_deadline');
            $table->string('email');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('careers');
    }
};
