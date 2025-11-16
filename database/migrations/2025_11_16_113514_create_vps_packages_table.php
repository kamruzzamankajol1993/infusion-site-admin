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
        Schema::create('vps_packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('vps_package_categories')->onDelete('cascade');
            $table->string('title'); // e.g., NL Browser RDP
            $table->string('price'); // e.g., 300
            $table->string('price_subtitle')->default('Starting at');
            $table->json('features'); // Stores [{"icon": "mdi:cpu...", "text": "Intel Xeon..."}]
            $table->string('button_text')->default('Buy Now');
            $table->string('button_link')->default('#');
            $table->boolean('is_stocked_out')->default(false);
            $table->integer('order')->default(0)->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vps_packages');
    }
};
