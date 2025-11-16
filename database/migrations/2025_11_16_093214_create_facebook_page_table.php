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
        Schema::create('facebook_page', function (Blueprint $table) {
            $table->id();
            // Header
            $table->string('header_title')->default('FACEBOOK PAGE SETUP');
            
            // Intro Section
            $table->string('intro_title')->nullable();
            $table->text('intro_description')->nullable();
            $table->string('intro_image')->nullable(); // 500x300

            // Pricing Section
            $table->string('pricing_title')->default('Pricing Table');
            $table->text('pricing_description')->nullable();
            
            // More Services Section
            $table->string('more_services_title')->default('More Facebook Services');
            $table->text('more_services_description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facebook_page');
    }
};
