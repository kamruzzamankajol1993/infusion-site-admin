<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('officer_officer_category', function (Blueprint $table) {
            $table->integer('order_column')->default(0)->after('officer_category_id');
        });
    }
    public function down(): void {
        Schema::table('officer_officer_category', function (Blueprint $table) {
            $table->dropColumn('order_column');
        });
    }
};