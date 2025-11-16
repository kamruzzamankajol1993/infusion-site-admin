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
        Schema::create('web_solution_page', function (Blueprint $table) {
            $table->id();
            // Hero
            $table->string('hero_title')->nullable();
            $table->text('hero_description')->nullable();
            $table->string('hero_button_text')->nullable();
            // Intro
            $table->string('intro_image')->nullable(); // 600x450
            $table->string('intro_title')->nullable();
            $table->text('intro_description')->nullable();
            $table->string('intro_button_text')->nullable();
            // Pro Section
            $table->string('pro_title')->nullable();
            $table->text('pro_description')->nullable();
            $table->string('pro_button_text')->nullable();
            // Checklist Section
            $table->string('checklist_title')->nullable();
            $table->text('checklist_description')->nullable();
            // Includes Section
            $table->string('includes_subtitle')->nullable();
            $table->string('includes_title')->nullable();
            $table->text('includes_description')->nullable();
            // Providing Section
            $table->string('providing_subtitle')->nullable();
            $table->string('providing_title')->nullable();
            $table->text('providing_description')->nullable();
            // Previous Work Section
            $table->string('work_subtitle')->nullable();
            $table->string('work_title')->nullable();
            $table->text('work_description')->nullable();
            // CTA Banner
            $table->string('cta_title')->nullable();
            $table->text('cta_description')->nullable();
            $table->string('cta_button_text')->nullable();
            // Website Care
            $table->string('care_subtitle')->nullable();
            $table->string('care_title')->nullable();
            $table->text('care_description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('web_solution_page');
    }
};
