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
            // Add the new column after 'name'
            $table->unsignedBigInteger('parent_id')->nullable()->after('name');

            // Add the foreign key constraint
            // This links 'parent_id' to 'id' on the same table.
            // 'onDelete('set null')' means if a parent category is deleted,
            // its children will become top-level categories (parent_id = NULL).
            $table->foreign('parent_id')
                  ->references('id')
                  ->on('officer_categories')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('officer_categories', function (Blueprint $table) {
            // Drop the foreign key first
            $table->dropForeign(['parent_id']);
            // Then drop the column
            $table->dropColumn('parent_id');
        });
    }
};