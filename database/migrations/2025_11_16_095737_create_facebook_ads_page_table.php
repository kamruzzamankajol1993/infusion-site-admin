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
        Schema::create('facebook_ads_page', function (Blueprint $table) {
            $table->id();
            // Hero
            $table->string('hero_title')->nullable();
            $table->text('hero_description')->nullable();
            $table->string('hero_button_text')->nullable()->default('Get Free Consultation');
            $table->string('hero_image')->nullable(); // 500x400

            // Stats Bar
            $table->string('stats_partner_logo')->nullable(); // 150x50
            $table->string('stats_partner_title')->default('Meta Business Partner');
            $table->string('stats_exp_number')->default('10+ Years');
            $table->string('stats_exp_title')->default('Facebook Ads Experience');
            $table->string('stats_client_number')->default('400+');
            $table->string('stats_client_title')->default('Client Successes');
            $table->string('stats_revenue_number')->default('$80 M');
            $table->string('stats_revenue_title')->default('Revenue Generated');
            
            // Campaign Section
            $table->string('campaign_section_title')->default('Which Type Campaign We Manage?');
            $table->string('campaign_image')->nullable(); // 500x500

            // Pricing Section
            $table->string('pricing_section_title')->default('CHOOSE YOUR PACKAGE');

            // FAQ Section
            $table->string('faq_section_title')->default('Facebook Advertising FAQs');

            // CTA Section
            $table->string('cta_title')->default('Unleash The Full Potential Of Your Facebook Marketing Campaigns');
            $table->string('cta_button_text')->default('GET FREE PROPOSAL');
            $table->string('cta_button_link')->default('#');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facebook_ads_page');
    }
};
