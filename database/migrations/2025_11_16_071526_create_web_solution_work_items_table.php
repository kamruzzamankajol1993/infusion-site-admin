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
        Schema::create('web_solution_work_items', function (Blueprint $table) {
            $table->id();
           $table->foreignId('category_id')->constrained('web_solution_work_categories')->onDelete('cascade');
            $table->string('image'); // 400x300
            $table->string('visit_link')->default('#');
            $table->integer('order')->default(0)->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('web_solution_work_items');
    }
};
