<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('web_solution_includes', function (Blueprint $table) {
            $table->dropColumn('icon_name'); // Remove old column
            $table->string('image')->after('id'); // Add new column
        });
    }

    public function down(): void
    {
        Schema::table('web_solution_includes', function (Blueprint $table) {
            $table->dropColumn('image');
            $table->string('icon_name')->default('mdi:check-circle');
        });
    }
};