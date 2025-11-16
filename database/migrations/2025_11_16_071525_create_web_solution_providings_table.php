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
        Schema::create('web_solution_providings', function (Blueprint $table) {
            $table->id();
            $table->string('image'); // 300x200
            $table->string('title');
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
        Schema::dropIfExists('web_solution_providings');
    }
};
