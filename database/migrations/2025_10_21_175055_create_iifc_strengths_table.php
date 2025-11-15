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
            $table->integer('ongoing_project')->default(0);
            $table->integer('complete_projects')->default(0);
            $table->integer('countries')->default(0); // Renamed from 'countrier'
            $table->integer('years_in_business')->default(0);
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
