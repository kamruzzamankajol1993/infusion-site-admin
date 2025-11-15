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
        // Pivot table for many-to-many relationship
        Schema::create('officer_officer_category', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('officer_id')
                  ->constrained('officers')
                  ->onDelete('cascade'); // If officer is deleted, remove this link
                  
            $table->foreignId('officer_category_id')
                  ->constrained('officer_categories') // Links to your existing table
                  ->onDelete('cascade'); // If category is deleted, remove this link

            // Optional: prevent duplicate officer-category pairs
            $table->unique(['officer_id', 'officer_category_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('officer_officer_category');
    }
};