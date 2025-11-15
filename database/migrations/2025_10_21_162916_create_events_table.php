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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->date('start_date');
            $table->date('end_date')->nullable(); // Can be null if it's a single-day event
            $table->string('time')->nullable(); // Flexible time format (e.g., "10:00 AM", "2 PM - 5 PM")
            $table->longText('description')->nullable();
            $table->boolean('status')->default(true); // true = Active/Published, false = Draft/Inactive
            $table->string('image')->nullable(); // Path to image (1200x750 px)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
