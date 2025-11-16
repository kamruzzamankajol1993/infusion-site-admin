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
        Schema::create('graphic_design_page', function (Blueprint $table) {
            $table->id();
            // Hero Section
            $table->string('hero_title')->nullable();
            $table->text('hero_description')->nullable();
            $table->string('hero_button_text')->nullable();
            
            // 360 Intro Section
            $table->string('intro_image')->nullable(); // 600x450
            $table->string('intro_title')->nullable();
            $table->text('intro_description')->nullable();
            $table->string('intro_button_text')->nullable();

            // Consultant Section
            $table->string('consultant_title')->nullable();
            $table->text('consultant_description')->nullable();
            $table->string('consultant_button_text')->nullable();

            // Growth Checklist Section
            $table->string('checklist_title')->nullable(); // Renamed from 'growth_title'
            $table->text('checklist_description')->nullable(); // Renamed from 'growth_description'
            
            // Marketing Solutions Section
            $table->string('solutions_subtitle')->nullable();
            $table->string('solutions_title')->nullable();
            $table->text('solutions_description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('graphic_design_page');
    }
};
