<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Step 1: Convert existing string data to a JSON array format.
        // This query finds any non-empty `left_image` and wraps it in `["..."]`
        DB::table('hero_sections')
            ->whereNotNull('left_image')
            ->where('left_image', '!=', '')
            ->update([
                'left_image' => DB::raw("JSON_ARRAY(left_image)")
            ]);

        // Step 2: Now that the data is valid JSON, change the column type.
        Schema::table('hero_sections', function (Blueprint $table) {
            $table->json('left_image')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Step 1: Convert JSON array back to a single string.
        // This extracts the first element from the JSON array.
        DB::table('hero_sections')
            ->whereNotNull('left_image')
            ->update([
                // Using JSON_UNQUOTE and JSON_EXTRACT to get the first element as a string
                'left_image' => DB::raw("JSON_UNQUOTE(JSON_EXTRACT(left_image, '$[0]'))")
            ]);

        // Step 2: Change the column type back to string.
        Schema::table('hero_sections', function (Blueprint $table) {
            $table->string('left_image')->nullable()->change();
        });
    }
};