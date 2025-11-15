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
        Schema::table('system_information', function (Blueprint $table) {
            // 1. Add the new columns
            $table->string('rectangular_logo')->nullable()->after('logo');
            $table->string('address_two')->nullable()->after('address');
            $table->string('phone_two', 11)->nullable()->after('phone');
            $table->string('email_two')->nullable()->after('email');

            // 2. Drop the old, unnecessary columns
            $table->dropColumn([
                'white_logo',
                'branch_id',
                'keyword',
                'tax',
                'charge',
               
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('system_information', function (Blueprint $table) {
            // 1. Re-add the dropped columns if we roll back
             $table->string('white_logo')->nullable();
             $table->string('branch_id')->nullable();
             $table->string('keyword')->nullable();
             $table->string('tax')->nullable();
             $table->string('charge')->nullable();
         

            // 2. Drop the columns that were added in this migration
            $table->dropColumn([
                'rectangular_logo',
                'address_two',
                'phone_two',
                'email_two'
            ]);
        });
    }
};
