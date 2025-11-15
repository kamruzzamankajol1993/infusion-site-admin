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
        Schema::create('officer_social_links', function (Blueprint $table) {
            $table->id();
            // Foreign key to the officers table
            $table->foreignId('officer_id')
                  ->constrained('officers')
                  ->onDelete('cascade'); // If the officer is deleted, delete their links

            $table->string('title'); // e.g., "LinkedIn", "Twitter", "Facebook"
            $table->string('link');  // The full URL
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('officer_social_links');
    }
};
