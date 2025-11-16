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
        Schema::create('facebook_pricing_packages', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // e.g., Basic Package
            $table->string('price'); // e.g., 999
            $table->text('features'); // Storing features as a JSON array or newline-separated string
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
        Schema::dropIfExists('facebook_pricing_packages');
    }
};
