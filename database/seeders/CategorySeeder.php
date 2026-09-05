<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Vehicles',       'slug' => 'vehicles',      'icon' => 'directions_car'],
            ['name' => 'Real Estate',    'slug' => 'real-estate',   'icon' => 'real_estate_agent'],
            ['name' => 'Mobile Phones',  'slug' => 'mobile-phones', 'icon' => 'smartphone'],
            ['name' => 'Electronics',    'slug' => 'electronics',   'icon' => 'laptop_mac'],
            ['name' => 'Fashion',        'slug' => 'fashion',       'icon' => 'styler'],
            ['name' => 'Furniture',      'slug' => 'furniture',     'icon' => 'chair'],
            ['name' => 'Jobs',           'slug' => 'jobs',          'icon' => 'work'],
            ['name' => 'Services',       'slug' => 'services',      'icon' => 'design_services'],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(['slug' => $cat['slug']], $cat);
        }
    }
}
