<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Jalankan semua seeder dalam urutan yang benar
        $this->call([
            RoleSeeder::class,      // Roles pertama (jika ada)
            AdminSeeder::class,     // Admin dan Users
            ProductSeeder::class,   // Categories dan Products
        ]);

        $this->command->info('🎉 Database seeding completed successfully!');
        $this->command->info('');
        $this->command->info('📋 Default Accounts Created:');
        $this->command->info('👤 Admin: admin@iwakmart.com / admin123');
        $this->command->info('👤 Test User: test@iwakmart.com / test123');
        $this->command->info('👤 John Doe: john@example.com / password123');
        $this->command->info('👤 Jane Smith: jane@example.com / password123');
        $this->command->info('');
        $this->command->info('🏪 Sample Data:');
        $this->command->info('📦 4 Categories created');
        $this->command->info('🐟 10 Products created');
        $this->command->info('');
        $this->command->info('✅ Ready for testing!');
    }
}
