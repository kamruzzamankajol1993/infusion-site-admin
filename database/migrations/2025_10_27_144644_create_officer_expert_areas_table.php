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
        Schema::create('expert_areas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('officer_id')
                  ->constrained('officers') // Link to officers table
                  ->onDelete('cascade'); // If officer deleted, remove expert area
            $table->string('expert_area'); // The text for the expert area
            $table->timestamps(); // Optional: created_at/updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expert_areas');
    }
};