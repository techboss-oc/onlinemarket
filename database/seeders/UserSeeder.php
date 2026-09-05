<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'email'      => 'admin@onlinemarket.ng',
                'password'   => Hash::make('admin123'),
                'role'       => 'admin',
                'is_verified'=> 1,
            ]
        );

        // Seller user
        User::updateOrCreate(
            ['username' => 'testseller'],
            [
                'email'      => 'seller@onlinemarket.ng',
                'password'   => Hash::make('seller123'),
                'role'       => 'seller',
                'is_verified'=> 1,
            ]
        );

        // Buyer user
        User::updateOrCreate(
            ['username' => 'testbuyer'],
            [
                'email'      => 'buyer@onlinemarket.ng',
                'password'   => Hash::make('buyer123'),
                'role'       => 'buyer',
                'is_verified'=> 1,
            ]
        );
    }
}
