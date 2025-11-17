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
        Schema::create('store_side_banners', function (Blueprint $table) {
            $table->id();
            $table->string('top_image')->nullable(); // 400x200
            $table->string('top_link')->default('#');
            $table->string('bottom_image')->nullable(); // 400x200
            $table->string('bottom_link')->default('#');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_side_banners');
    }
};
