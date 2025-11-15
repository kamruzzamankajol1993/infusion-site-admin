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
        Schema::table('job_applicants', function (Blueprint $table) {
            // Add new fields after existing ones, e.g., after qualification
            $table->date('date_of_birth')->nullable()->after('qualification');
            $table->text('educational_background')->nullable()->after('date_of_birth');
            $table->text('working_experience')->nullable()->after('educational_background');
            $table->text('address')->nullable()->after('working_experience');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_applicants', function (Blueprint $table) {
            $table->dropColumn([
                'date_of_birth',
                'educational_background',
                'working_experience',
                'address'
            ]);
        });
    }
};