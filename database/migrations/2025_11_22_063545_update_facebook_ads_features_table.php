<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('facebook_ads_features', function (Blueprint $table) {
            $table->dropColumn('icon_name');
            $table->string('image')->after('id'); // Store image path
        });
    }

    public function down(): void
    {
        Schema::table('facebook_ads_features', function (Blueprint $table) {
            $table->dropColumn('image');
            $table->string('icon_name')->default('mdi:check');
        });
    }
};