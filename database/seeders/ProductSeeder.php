<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductPackage;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fetch category IDs for mapping
        $cats = Category::pluck('id', 'name');

        $products = [
            // --- VPS & RDP ---
            [
                'name' => 'USA Admin RDP - 16GB RAM',
                'cat' => 'USA RDP',
                'price' => 1200.00,
                'stock' => 50,
                'packages' => [
                    ['variation_name' => '1 Month Validity', 'additional_price' => 0],
                    ['variation_name' => '3 Months Validity', 'additional_price' => 2200],
                ]
            ],
            [
                'name' => 'Cheap NL Browser RDP',
                'cat' => 'Netherlands RDP',
                'price' => 300.00,
                'stock' => 100,
                'packages' => []
            ],
            [
                'name' => 'UK Residential VPS - 4 Core',
                'cat' => 'UK RDP',
                'price' => 2500.00,
                'stock' => 20,
                'packages' => [
                    ['variation_name' => 'Standard IP', 'additional_price' => 0],
                    ['variation_name' => 'Dedicated Static IP', 'additional_price' => 500],
                ]
            ],

            // --- Social Media ---
            [
                'name' => 'Verified Facebook Business Manager',
                'cat' => 'Facebook',
                'price' => 3500.00,
                'stock' => 10,
                'packages' => [
                    ['variation_name' => '250$ Daily Limit', 'additional_price' => 0],
                    ['variation_name' => 'Unlimited Daily Limit', 'additional_price' => 1500],
                ]
            ],
            [
                'name' => 'Instagram Aged Account (2018)',
                'cat' => 'Instagram',
                'price' => 450.00,
                'stock' => 200,
                'packages' => []
            ],
            [
                'name' => 'TikTok Ads Account (Agency)',
                'cat' => 'TikTok',
                'price' => 1500.00,
                'stock' => 15,
                'packages' => []
            ],
            [
                'name' => 'LinkedIn Premium Business Account',
                'cat' => 'LinkedIn',
                'price' => 800.00,
                'stock' => 30,
                'packages' => [
                    ['variation_name' => '6 Months', 'additional_price' => 0],
                    ['variation_name' => '1 Year', 'additional_price' => 700],
                ]
            ],

            // --- Banking ---
            [
                'name' => 'Binance Verified Plus Account',
                'cat' => 'Crypto Wallets',
                'price' => 5000.00,
                'stock' => 5,
                'packages' => []
            ],
            [
                'name' => 'Redotpay Virtual Visa Card',
                'cat' => 'Virtual Cards (VCC)',
                'price' => 1200.00,
                'stock' => 100,
                'packages' => [
                    ['variation_name' => 'Loaded with $5', 'additional_price' => 600],
                    ['variation_name' => 'Loaded with $10', 'additional_price' => 1200],
                ]
            ],
            [
                'name' => 'Wise Business Account (Green)',
                'cat' => 'Bank Accounts',
                'price' => 8500.00,
                'stock' => 3,
                'packages' => []
            ],

            // --- Email ---
            [
                'name' => 'Bulk Gmail Accounts (New)',
                'cat' => 'Gmail',
                'price' => 15.00,
                'stock' => 1000,
                'packages' => [
                    ['variation_name' => 'Pack of 10', 'additional_price' => 130],
                    ['variation_name' => 'Pack of 50', 'additional_price' => 600],
                ]
            ],
            [
                'name' => 'Google Voice Number (USA)',
                'cat' => 'Gmail',
                'price' => 450.00,
                'stock' => 50,
                'packages' => []
            ],

            // --- Streaming ---
            [
                'name' => 'Netflix 4K UHD Shared',
                'cat' => 'Netflix',
                'price' => 250.00,
                'stock' => 50,
                'packages' => [
                    ['variation_name' => '1 Profile (1 Month)', 'additional_price' => 0],
                    ['variation_name' => 'Private Account (1 Month)', 'additional_price' => 800],
                ]
            ],
            [
                'name' => 'Spotify Premium Individual',
                'cat' => 'Spotify',
                'price' => 150.00,
                'stock' => 100,
                'packages' => [
                    ['variation_name' => '3 Months Upgrade', 'additional_price' => 0],
                    ['variation_name' => 'Lifetime Upgrade', 'additional_price' => 250],
                ]
            ],
             [
                'name' => 'Amazon Prime Video Private',
                'cat' => 'Amazon Prime',
                'price' => 300.00,
                'stock' => 40,
                'packages' => []
            ],

            // --- Software ---
            [
                'name' => 'NordVPN Premium Account',
                'cat' => 'VPN Services',
                'price' => 200.00,
                'stock' => 60,
                'packages' => [
                    ['variation_name' => 'Random Expiry (min 1 year)', 'additional_price' => 0],
                ]
            ],
            [
                'name' => 'Canva Pro Team Invite',
                'cat' => 'Design Tools',
                'price' => 99.00,
                'stock' => 500,
                'packages' => []
            ],
            [
                'name' => 'Semrush Guru 14 Days Trial',
                'cat' => 'SEO Tools',
                'price' => 150.00,
                'stock' => 20,
                'packages' => []
            ],
            [
                'name' => 'Windows 11 Pro Retail Key',
                'cat' => 'Windows Keys',
                'price' => 500.00,
                'stock' => 100,
                'packages' => []
            ],
            [
                'name' => 'Kaspersky Total Security 1 Year',
                'cat' => 'Software & Tools',
                'price' => 650.00,
                'stock' => 30,
                'packages' => [
                    ['variation_name' => '1 Device', 'additional_price' => 0],
                    ['variation_name' => '3 Devices', 'additional_price' => 400],
                ]
            ],
            [
                'name' => 'Adobe Creative Cloud All Apps (1 Month)',
                'cat' => 'Design Tools',
                'price' => 1200.00,
                'stock' => 10,
                'packages' => []
            ],
        ];

        foreach ($products as $index => $item) {
            // Find category ID safely
            $categoryId = null;
            if (isset($cats[$item['cat']])) {
                $categoryId = $cats[$item['cat']];
            } else {
                // Fallback to a main category if specific subcat not found
                $parentName = explode(' ', $item['cat'])[0]; // e.g., "USA" from "USA RDP"
                $categoryId = Category::where('name', 'LIKE', "%$parentName%")->value('id');
            }

            $product = Product::create([
                'category_id' => $categoryId,
                'name' => $item['name'],
                'slug' => Str::slug($item['name']),
                'description' => '<p>This is a high-quality digital product. Instant delivery available for <b>' . $item['name'] . '</b>.</p><ul><li>Verified & Secure</li><li>24/7 Support</li><li>Best Price Guarantee</li></ul>',
                // Placeholder image logic (you might want to use real paths later)
                'image' => null, 
                'sku' => 'SKU-' . strtoupper(Str::random(6)) . '-' . ($index + 1),
                'stock_quantity' => $item['stock'],
                'buying_price' => $item['price'] * 0.7, // Mock buying price
                'selling_price' => $item['price'],
                'discount_price' => ($index % 3 == 0) ? $item['price'] * 0.9 : null, // Discount every 3rd item
                'is_top_selling_product' => ($index % 4 == 0), // Mark some as top selling
                'status' => true,
                'order' => $index + 1,
            ]);

            // Create Packages if array exists and is not empty
            if (!empty($item['packages'])) {
                foreach ($item['packages'] as $pkg) {
                    ProductPackage::create([
                        'product_id' => $product->id,
                        'variation_name' => $pkg['variation_name'],
                        'additional_price' => $pkg['additional_price'],
                    ]);
                }
            }
        }
    }
}