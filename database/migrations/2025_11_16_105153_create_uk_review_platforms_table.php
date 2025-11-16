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
        Schema::create('uk_review_platforms', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., Google
            $table->string('image'); // 200x60
            $table->string('rating_text'); // e.g., Rated 5.0 Out Of 5.0
            $table->string('review_link')->default('#');
            $table->integer('order')->default(0)->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uk_review_platforms');
    }
};
