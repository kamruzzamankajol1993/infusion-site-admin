<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Coupon;
use Carbon\Carbon;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $coupons = [
            [
                'code' => 'WELCOME10',
                'type' => 'percent',
                'amount' => 10.00,
                'expire_date' => Carbon::now()->addYear(), // Valid for 1 year
                'status' => true,
            ],
            [
                'code' => 'FLAT500',
                'type' => 'fixed',
                'amount' => 500.00,
                'expire_date' => Carbon::now()->addMonths(6), // Valid for 6 months
                'status' => true,
            ],
            [
                'code' => 'SUMMER2025',
                'type' => 'percent',
                'amount' => 25.00,
                'expire_date' => Carbon::create(2025, 8, 31), // Specific date
                'status' => true,
            ],
            [
                'code' => 'EXPIRED50',
                'type' => 'percent',
                'amount' => 50.00,
                'expire_date' => Carbon::now()->subDay(), // Already expired
                'status' => true,
            ],
            [
                'code' => 'INACTIVE100',
                'type' => 'fixed',
                'amount' => 100.00,
                'expire_date' => Carbon::now()->addMonth(),
                'status' => false, // Manually inactive
            ],
            [
                'code' => 'EID2025',
                'type' => 'fixed',
                'amount' => 1000.00,
                'expire_date' => Carbon::create(2025, 12, 31),
                'status' => true,
            ],
        ];

        foreach ($coupons as $coupon) {
            // Check if coupon exists to avoid duplicate errors on re-seeding
            if (!Coupon::where('code', $coupon['code'])->exists()) {
                Coupon::create($coupon);
            }
        }
    }
}