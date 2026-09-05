<?php

namespace Database\Seeders;

use App\Models\MonetizationPackage;
use App\Models\PaymentSetting;
use Illuminate\Database\Seeder;

class MonetizationSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Payment Settings
        $providers = ['paystack', 'flutterwave'];
        foreach ($providers as $provider) {
            PaymentSetting::firstOrCreate([
                'provider_name' => $provider
            ], [
                'public_key' => '',
                'secret_key' => '',
                'is_active' => false,
                'is_test_mode' => true,
                'currency' => 'NGN'
            ]);
        }

        // 2. Default Packages
        $packages = [
            // TOP Ads
            ['type' => 'top_ad', 'name' => 'Top Ad - 1 Day', 'description' => 'Pin your ad to the top for 1 day', 'price' => 1000, 'duration_days' => 1, 'sort_order' => 1],
            ['type' => 'top_ad', 'name' => 'Top Ad - 3 Days', 'description' => 'Pin your ad to the top for 3 days', 'price' => 2500, 'duration_days' => 3, 'sort_order' => 2],
            ['type' => 'top_ad', 'name' => 'Top Ad - 7 Days', 'description' => 'Pin your ad to the top for 7 days', 'price' => 5000, 'duration_days' => 7, 'sort_order' => 3],
            ['type' => 'top_ad', 'name' => 'Top Ad - 30 Days', 'description' => 'Pin your ad to the top for a full month', 'price' => 18000, 'duration_days' => 30, 'sort_order' => 4],
            
            // Boost Ads
            ['type' => 'boost', 'name' => 'Basic Boost', 'description' => 'Bump to top once a day for 3 days', 'price' => 1500, 'duration_days' => 3, 'boost_count' => 3, 'refresh_frequency_hours' => 24, 'sort_order' => 5],
            ['type' => 'boost', 'name' => 'Standard Boost', 'description' => 'Bump to top twice a day for 7 days', 'price' => 4000, 'duration_days' => 7, 'boost_count' => 14, 'refresh_frequency_hours' => 12, 'sort_order' => 6],
            ['type' => 'boost', 'name' => 'Premium Boost', 'description' => 'Bump to top four times a day for 14 days', 'price' => 9000, 'duration_days' => 14, 'boost_count' => 56, 'refresh_frequency_hours' => 6, 'sort_order' => 7],
            
            // Featured Ads
            ['type' => 'featured', 'name' => 'Homepage Featured - 7 Days', 'description' => 'Show your ad in the VIP Featured section for a week', 'price' => 8000, 'duration_days' => 7, 'sort_order' => 8],
            
            // Seller Packages
            ['type' => 'seller_package', 'name' => 'Business Package', 'description' => 'Post up to 50 active listings with basic analytics', 'price' => 15000, 'duration_days' => 30, 'listing_limit' => 50, 'sort_order' => 9],
            ['type' => 'seller_package', 'name' => 'Premium Package', 'description' => 'Unlimited posts, priority support, and advanced analytics', 'price' => 35000, 'duration_days' => 30, 'listing_limit' => 999999, 'sort_order' => 10],
        ];

        foreach ($packages as $pkg) {
            MonetizationPackage::updateOrCreate([
                'type' => $pkg['type'],
                'name' => $pkg['name']
            ], $pkg);
        }
    }
}
