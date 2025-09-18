<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TestFarmOwnerSeeder extends Seeder
{
    public function run()
    {
        // Create or update test farm owner
        User::updateOrCreate(
            ['email' => 'pemilik@iwakmart.com'],
            [
                'name' => 'Pemilik Tambak Test',
                'password' => Hash::make('password123'),
                'phone' => '08123456789',
                'role' => 'pemilik_tambak'
            ]
        );

        $this->command->info('✅ Test farm owner created/updated!');
        $this->command->info('📧 Email: pemilik@iwakmart.com');
        $this->command->info('🔑 Password: password123');
        $this->command->info('👤 Role: pemilik_tambak');
    }
}