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
        Schema::create('facebook_more_services', function (Blueprint $table) {
            $table->id();
            $table->string('image'); // e.g., mdi:monitor-dashboard
            $table->string('title');
            $table->text('description');
            $table->string('link_text')->default('Buy Now &rarr;');
            $table->string('link_url')->default('#');
            $table->integer('order')->default(0)->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facebook_more_services');
    }
};
