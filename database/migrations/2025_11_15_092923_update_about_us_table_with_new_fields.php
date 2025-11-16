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
        Schema::table('about_us', function (Blueprint $table) {
            // Drop old columns
            $table->dropColumn([
                'mission_title',
                'mission_description',
                'vision_title',
                'vision_description',
                'objectives_title',
                'objectives_description',
                'brief_description',
                'organogram_image',
            ]);

            // Add new columns
            $table->text('our_story')->nullable()->after('id');
            $table->string('team_image')->nullable()->after('our_story'); // 600x400
            $table->text('mission')->nullable()->after('team_image');
            $table->text('vision')->nullable()->after('mission');
            $table->string('mission_vision_image')->nullable()->after('vision'); // 600x400
            $table->text('founder_quote')->nullable()->after('mission_vision_image');
            $table->string('founder_image')->nullable()->after('founder_quote'); // 400x500
            $table->string('founder_name')->nullable()->after('founder_image');
            $table->string('founder_designation')->nullable()->after('founder_name');
            $table->string('trade_license')->nullable()->after('founder_designation');
            $table->string('bin')->nullable()->after('trade_license');
            $table->string('tin')->nullable()->after('bin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('about_us', function (Blueprint $table) {
            // Drop new columns
            $table->dropColumn([
                'our_story',
                'team_image',
                'mission',
                'vision',
                'mission_vision_image',
                'founder_quote',
                'founder_image',
                'founder_name',
                'founder_designation',
                'trade_license',
                'bin',
                'tin',
            ]);

            // Re-add old columns (if you need to rollback)
            $table->string('mission_title')->nullable();
            $table->text('mission_description')->nullable();
            $table->string('vision_title')->nullable();
            $table->text('vision_description')->nullable();
            $table->string('objectives_title')->nullable();
            $table->text('objectives_description')->nullable();
            $table->text('brief_description')->nullable();
            $table->string('organogram_image')->nullable();
        });
    }
};