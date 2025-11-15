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
        Schema::create('service_keypoints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')
                  ->constrained('services') // Link to services table
                  ->onDelete('cascade'); // Delete keypoints if service is deleted
            $table->text('keypoint');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_keypoints');
    }
};
