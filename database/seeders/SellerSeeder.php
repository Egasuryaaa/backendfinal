<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SellerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat user seller test
        $seller = User::firstOrCreate(
            ['email' => 'seller@iwakmart.com'],
            [
                'name' => 'Seller Test',
                'password' => Hash::make('password'),
                'phone' => '081234567890',
            ]
        );

        // Berikan role seller
        $seller->assignRole('seller');
    }
}
