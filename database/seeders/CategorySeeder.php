<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Social Media Accounts',
                'children' => ['Facebook', 'Instagram', 'Twitter/X', 'LinkedIn', 'TikTok', 'Reddit']
            ],
            [
                'name' => 'VPS & RDP',
                'children' => ['USA RDP', 'UK RDP', 'Netherlands RDP', 'Admin RDP', 'Private VPS']
            ],
            [
                'name' => 'Banking & Finance',
                'children' => ['Virtual Cards (VCC)', 'Crypto Wallets', 'Payment Gateways', 'Bank Accounts']
            ],
            [
                'name' => 'Email Services',
                'children' => ['Gmail', 'Outlook', 'Yahoo', 'Edu Emails']
            ],
            [
                'name' => 'Streaming & Entertainment',
                'children' => ['Netflix', 'Spotify', 'Amazon Prime', 'Disney+']
            ],
            [
                'name' => 'Software & Tools',
                'children' => ['VPN Services', 'SEO Tools', 'Design Tools', 'Windows Keys']
            ],
        ];

        foreach ($categories as $catData) {
            // Create Parent
            $parent = Category::create([
                'name' => $catData['name'],
                'slug' => Str::slug($catData['name']),
                'status' => true,
                'parent_id' => null,
            ]);

            // Create Children
            foreach ($catData['children'] as $childName) {
                Category::create([
                    'name' => $childName,
                    'slug' => Str::slug($childName),
                    'status' => true,
                    'parent_id' => $parent->id,
                ]);
            }
        }
    }
}