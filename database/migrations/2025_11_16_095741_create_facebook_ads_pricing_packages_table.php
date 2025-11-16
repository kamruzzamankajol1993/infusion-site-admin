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
        Schema::create('facebook_ads_pricing_packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('facebook_ads_pricing_categories')->onDelete('cascade');
            $table->string('title');
            $table->string('price'); // e.g., 150
            $table->string('price_suffix')->nullable(); // e.g., Per Ad/USD
            $table->text('features'); // Storing features as JSON
            $table->string('button_text')->default('Order Now');
            $table->string('button_link')->default('#');
            $table->integer('order')->default(0)->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facebook_ads_pricing_packages');
    }
};
