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
        Schema::create('trainings', function (Blueprint $table) {
            $table->id();
            
            // $table->foreignId('category_id')->constrained('training_categories')->onDelete('cascade'); // Removed
            
            $table->string('title')->unique();
            $table->longText('description')->nullable();
            
            // New Content Fields
            $table->longText('learn_from_training')->nullable();
            $table->longText('who_should_attend')->nullable();
            $table->longText('methodology')->nullable();
            
            // New Info Fields
            $table->string('training_time')->nullable()->comment('e.g., 9:00 AM - 5:00 PM');
            $table->string('training_venue')->nullable()->comment('e.g., Online or Office Address');
            
            // Date & Fee Fields
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->date('deadline_for_registration')->nullable();
            $table->decimal('training_fee', 10, 2)->nullable();

            // $table->longText('requirement')->nullable(); // Removed

            // Document Fields
            $table->string('document_one')->nullable()->comment('Path to PDF file');
            $table->string('document_two')->nullable()->comment('Path to PDF file');
            $table->string('document_three')->nullable()->comment('Path to PDF file');
            $table->string('document_four')->nullable()->comment('Path to PDF file');

            // Image & Status
            $table->string('image')->nullable()->comment('Path to image (600x400 px)');
            
            // Status can be: upcoming, running, postponed, complete
            $table->string('status', 50)->default('upcoming')->index(); 
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trainings');
    }
};