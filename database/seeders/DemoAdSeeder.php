<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Location;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoAdSeeder extends Seeder
{
    public function run(): void
    {
        // Get the test seller
        $seller = User::where('username', 'testseller')->first();

        if (!$seller) {
            $this->command->error("Seller 'testseller' not found. Please run UserSeeder first.");
            return;
        }

        // Available categories and their corresponding ad data
        $adData = [
            [
                'slug' => 'mobile-phones',
                'title' => 'iPhone 13 Pro Max - 256GB Excellent Condition',
                'description' => 'Selling my iPhone 13 Pro Max. Used for exactly a year with a case and screen protector. Battery health 89%. Comes with original box and cable.',
                'price' => 750000.00,
                'condition_state' => 'used',
                'brand' => 'Apple',
                'image_url' => 'https://images.unsplash.com/photo-1510557880182-3d4d3cba35a5?w=500&q=80',
            ],
            [
                'slug' => 'electronics',
                'title' => 'MacBook Pro M1 2021 - 16GB RAM',
                'description' => 'Fast and reliable MacBook Pro with M1 chip. Ideal for programming, video editing, and heavy tasks. Silver color, barely zero scratches.',
                'price' => 1250000.00,
                'condition_state' => 'refurbished',
                'brand' => 'Apple',
                'image_url' => 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=500&q=80',
            ],
            [
                'slug' => 'vehicles',
                'title' => 'Toyota Camry 2018 LE - Nigerian Used',
                'description' => 'Engine is sound, gear selects perfectly, AC chills like Moscow. Just buy and drive.',
                'price' => 6500000.00,
                'condition_state' => 'used',
                'brand' => 'Toyota',
                'image_url' => 'https://images.unsplash.com/photo-1621007947382-3d0739818816?w=500&q=80',
            ],
            [
                'slug' => 'fashion',
                'title' => 'Men\'s Genuine Leather Custom Jacket',
                'description' => 'Brand new pure leather winter and party biker jacket. Available in sizes L and XL.',
                'price' => 45000.00,
                'condition_state' => 'new',
                'brand' => 'Customized',
                'image_url' => 'https://images.unsplash.com/photo-1551028719-00167b16eac5?w=500&q=80',
            ],
            [
                'slug' => 'furniture',
                'title' => 'Premium Royal 6-Seater Dining Set',
                'description' => 'High-quality mahogany wood dining set with comfortable foam padded chairs. Best for family size dining.',
                'price' => 420000.00,
                'condition_state' => 'new',
                'brand' => 'Royal Furniture',
                'image_url' => 'https://images.unsplash.com/photo-1617806118233-18e1c0955f0d?w=500&q=80',
            ]
        ];

        $locationId = Location::inRandomOrder()->value('id') ?? 1;

        foreach ($adData as $data) {
            $category = Category::where('slug', $data['slug'])->first();

            if ($category) {
                // Check if already exists so we don't duplicate on multi-runs
                $existingId = DB::table('ads')
                    ->where('user_id', $seller->id)
                    ->where('title', $data['title'])
                    ->value('id');

                if (!$existingId) {
                    $adId = DB::table('ads')->insertGetId([
                        'user_id' => $seller->id,
                        'category_id' => $category->id,
                        'location_id' => $locationId,
                        'title' => $data['title'],
                        'description' => $data['description'],
                        'price' => $data['price'],
                        'currency' => 'NGN',
                        'condition_state' => $data['condition_state'],
                        'brand' => $data['brand'],
                        'status' => 'active',
                        'is_featured' => false,
                        'is_urgent' => false,
                        'views_count' => rand(10, 150),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    DB::table('ad_images')->insert([
                        'ad_id' => $adId,
                        'image_url' => $data['image_url'],
                        'is_primary' => true,
                        'created_at' => now(),
                    ]);
                }
            }
        }
    }
}
