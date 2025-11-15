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
        Schema::create('notices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')
                  ->constrained('notice_categories') // Link to notice_categories table
                  ->onDelete('cascade'); // Delete notices if category is deleted
            $table->string('title');
            $table->date('date'); // Notice date
            $table->string('pdf_file'); // Path to the uploaded PDF file
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notices');
    }
};
