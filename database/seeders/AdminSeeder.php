<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data to avoid duplicates
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        User::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Create Admin User
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@iwakmart.com',
            'email_verified_at' => now(),
            'password' => Hash::make('admin123'),
            'phone' => '081234567890',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Assign admin role jika menggunakan Spatie Permission
        if (class_exists('Spatie\Permission\Models\Role')) {
            $admin->assignRole('admin');
        }

        // Create Default Users
        $users = [
            [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'phone' => '081234567891',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'phone' => '081234567892',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Test User',
                'email' => 'test@iwakmart.com',
                'email_verified_at' => now(),
                'password' => Hash::make('test123'),
                'phone' => '081234567893',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($users as $userData) {
            $user = User::create($userData);

            // Assign customer role jika menggunakan Spatie Permission
            if (class_exists('Spatie\Permission\Models\Role')) {
                $user->assignRole('customer');
            }
        }

        // Create Seller User
        $seller = User::create([
            'name' => 'Seller Demo',
            'email' => 'seller@iwakmart.com',
            'email_verified_at' => now(),
            'password' => Hash::make('seller123'),
            'phone' => '081234567894',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Assign seller role jika menggunakan Spatie Permission
        if (class_exists('Spatie\Permission\Models\Role')) {
            $seller->assignRole('seller');
        }

        $this->command->info('✅ Admin and Users seeded successfully!');
        $this->command->info('📧 Admin Login: admin@iwakmart.com / admin123');
        $this->command->info('📧 Seller Login: seller@iwakmart.com / seller123');
        $this->command->info('📧 Test User: test@iwakmart.com / test123');
    }
}
