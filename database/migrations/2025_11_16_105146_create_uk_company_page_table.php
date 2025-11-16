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
        Schema::create('uk_company_page', function (Blueprint $table) {
            $table->id();
            // Hero
            $table->string('hero_subtitle_top');
            $table->string('hero_title');
            $table->text('hero_description')->nullable();
            $table->string('hero_button_text');
            $table->string('hero_button_link')->default('#');
            $table->string('hero_image')->nullable(); // 400x450

            // Carbon Badge
            $table->string('carbon_badge_text')->default('We Are Proud To Be A Certified Carbon Neutral Business 2024');

            // Pricing Section
            $table->string('pricing_title')->default('Quick Guide To Our Company Formation Packages');
            $table->text('pricing_description')->nullable();
            
            // Testimonial Section
            $table->string('testimonial_title')->default('What Our Customers Say');

            // Review Platform Section
            $table->string('review_title')->default('What Our Customers Say');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uk_company_page');
    }
};
