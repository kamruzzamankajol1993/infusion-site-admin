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
        Schema::create('training_enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_id')->constrained('trainings')->onDelete('cascade');
            $table->string('name');
            $table->string('designation')->nullable();
            $table->string('organization')->nullable();
            $table->string('experience')->nullable()->comment('Years of Professional Experience');
            $table->string('highest_degree')->nullable()->comment('Highest Degree with Discipline');
            $table->text('address')->nullable();
            $table->string('email');
            $table->string('mobile');
            $table->string('telephone')->nullable();
            $table->string('fax')->nullable();
            $table->string('payment_method', 50)->nullable()->comment('cheque, cash');
            $table->string('status', 50)->default('pending')->comment('pending, confirmed, cancelled');
            $table->timestamps();

            // Add indexes for common search fields
            $table->index('email');
            $table->index('mobile');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_enrollments');
    }
};
