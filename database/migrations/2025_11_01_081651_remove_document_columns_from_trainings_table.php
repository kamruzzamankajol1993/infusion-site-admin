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
        Schema::table('trainings', function (Blueprint $table) {
            $table->dropColumn([
                'document_one',
                'document_two',
                'document_three',
                'document_four',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trainings', function (Blueprint $table) {
            $table->string('document_one')->nullable()->after('training_fee');
            $table->string('document_two')->nullable()->after('document_one');
            $table->string('document_three')->nullable()->after('document_two');
            $table->string('document_four')->nullable()->after('document_three');
        });
    }
};