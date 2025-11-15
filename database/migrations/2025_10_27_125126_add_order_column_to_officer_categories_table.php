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
        Schema::table('officer_categories', function (Blueprint $table) {
            // Add the new column after 'parent_id' or 'name'
            $table->integer('order_column')->default(0)->after('parent_id'); // Or after('name') if no parent_id
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('officer_categories', function (Blueprint $table) {
            $table->dropColumn('order_column');
        });
    }
};