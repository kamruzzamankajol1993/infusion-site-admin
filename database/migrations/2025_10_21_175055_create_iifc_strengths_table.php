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
        Schema::create('iifc_strengths', function (Blueprint $table) {
            $table->id();
            $table->integer('projects')->default(0);
            $table->integer('products')->default(0);
            $table->integer('experts')->default(0);
            $table->integer('countries')->default(0); // Renamed from 'countrier'
            $table->integer('happy_clients')->default(0);
            $table->integer('yrs_experienced')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('iifc_strengths');
    }
};
