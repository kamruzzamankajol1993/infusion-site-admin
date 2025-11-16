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
        Schema::create('vps_page', function (Blueprint $table) {
            $table->id();
            $table->string('hero_title')->default('Cheapest RDP/VPS');
            $table->json('hero_features'); // Stores ["Dedicated IP", "Dedicated CPU", ...]
            $table->string('hero_button_text')->default('Get Now');
            $table->string('hero_button_link')->default('#');
            $table->string('hero_image')->nullable(); // 500x400

            // Section Titles
            $table->string('category_1_title')->default('Browser RDP');
            $table->string('category_2_title')->default('Starter RDP Plan');
            $table->string('category_3_title')->default('Private RDP Plan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vps_page');
    }
};
