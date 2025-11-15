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
        Schema::table('engage_sections', function (Blueprint $table) {
            // Add the new column, making it nullable
            $table->text('sort_description')->nullable()->after('image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('engage_sections', function (Blueprint $table) {
            $table->dropColumn('sort_description');
        });
    }
};