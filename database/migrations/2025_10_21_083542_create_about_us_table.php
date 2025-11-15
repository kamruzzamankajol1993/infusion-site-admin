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
        Schema::create('about_us', function (Blueprint $table) {
            $table->id();
            $table->string('mission_title');
            $table->longText('mission_description');
            $table->string('vision_title');
            $table->longText('vision_description');
            $table->string('objectives_title');
            $table->longText('objectives_description');
            $table->longText('brief_description');
            $table->string('organogram_image')->nullable(); // Path to the imag
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('about_us');
    }
};
