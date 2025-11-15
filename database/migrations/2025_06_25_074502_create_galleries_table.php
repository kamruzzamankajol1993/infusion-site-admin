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
        Schema::create('galleries', function (Blueprint $table) {
            $table->id();
             $table->string('short_description')->nullable();
            $table->enum('type', ['image', 'video'])->default('image'); // Type: image or video
            $table->string('image_file')->nullable(); // Path for image (1500x990 px), null if video
            $table->string('youtube_link')->nullable(); // YouTube URL, null if image
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('galleries');
    }
};
