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
        Schema::create('uk_pricing_packages', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // e.g., Digital
            $table->string('price'); // e.g., 3,999
            $table->json('features'); // Stores [{"text": "Feature...", "included": true/false}]
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
        Schema::dropIfExists('uk_pricing_packages');
    }
};
