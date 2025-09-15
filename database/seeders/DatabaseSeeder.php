<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Jalankan RoleSeeder terlebih dahulu
        $this->call([
            RoleSeeder::class,
        ]);

        // User::factory(10)->create();

        // Buat user test
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        
        // Berikan role 'pembeli' ke user test
        $user->assignRole('pembeli');
        
        // Buat user admin
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);
        
        // Berikan role 'admin' ke user admin
        $admin->assignRole('admin');
        
        // Buat user penjual
        $seller = User::create([
            'name' => 'Seller User',
            'email' => 'seller@example.com',
            'password' => Hash::make('password'),
        ]);
        
        // Berikan role 'penjual_biasa' ke user penjual
        $seller->assignRole('penjual_biasa');
        
        // Buat user pengepul
        $pengepul = User::create([
            'name' => 'Pengepul User',
            'email' => 'pengepul@example.com',
            'password' => Hash::make('password'),
            'phone' => '081234567890'
        ]);
        
        // Berikan role 'pengepul' ke user pengepul
        $pengepul->assignRole('pengepul');
        
        // Buat user pemilik tambak
        $pemilikTambak = User::create([
            'name' => 'Pemilik Tambak User',
            'email' => 'pemilik.tambak@example.com',
            'password' => Hash::make('password'),
            'phone' => '081234567891'
        ]);
        
        // Berikan role 'pemilik_tambak' ke user pemilik tambak
        $pemilikTambak->assignRole('pemilik_tambak');
    }
}
