<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        $locations = [
            ['name' => 'Lagos',   'slug' => 'lagos',  'type' => 'state'],
            ['name' => 'Abuja',   'slug' => 'abuja',  'type' => 'state'],
            ['name' => 'Rivers',  'slug' => 'rivers', 'type' => 'state'],
            ['name' => 'Ogun',    'slug' => 'ogun',   'type' => 'state'],
            ['name' => 'Oyo',     'slug' => 'oyo',    'type' => 'state'],
        ];

        foreach ($locations as $loc) {
            Location::updateOrCreate(['slug' => $loc['slug']], $loc);
        }
    }
}
