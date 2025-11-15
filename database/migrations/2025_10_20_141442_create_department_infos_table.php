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
        Schema::create('department_infos', function (Blueprint $table) {
            $table->id();

            // Foreign key for the Officer (one-to-many)
            $table->foreignId('officer_id')
                  ->constrained('officers')
                  ->onDelete('cascade'); // If officer is deleted, delete this info
            
            // Foreign key for Designation (assuming 'designations' table)
            $table->foreignId('designation_id')
                  ->nullable()
                  ->constrained('designations')
                  ->onDelete('set null'); // Keep info, but null the designation
            
            // Foreign key for Department (assuming 'departments' table)
            $table->foreignId('department_id')
                  ->nullable()
                  ->constrained('departments')
                  ->onDelete('set null'); // Keep info, but null the department

            $table->string('additional_text')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('department_infos');
    }
};