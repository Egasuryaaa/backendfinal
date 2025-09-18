<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\FishFarm;
use App\Models\Collector;
use App\Models\Appointment;
use App\Models\SellerLocation;
use Illuminate\Support\Facades\Hash;

class FishFarmCollectorSeeder extends Seeder
{
    public function run()
    {
        // Create test users for farmers and collectors
        $farmers = [
            [
                'name' => 'Budi Santoso',
                'email' => 'budi.farmer@iwakmart.com',
                'password' => Hash::make('password123'),
                'phone' => '08123456789'
            ],
            [
                'name' => 'Siti Rahayu',
                'email' => 'siti.farmer@iwakmart.com',
                'password' => Hash::make('password123'),
                'phone' => '08234567890'
            ],
            [
                'name' => 'Ahmad Wijaya',
                'email' => 'ahmad.farmer@iwakmart.com',
                'password' => Hash::make('password123'),
                'phone' => '08345678901'
            ]
        ];

        $collectors = [
            [
                'name' => 'Ibu Lastri',
                'email' => 'lastri.collector@iwakmart.com',
                'password' => Hash::make('password123'),
                'phone' => '08456789012'
            ],
            [
                'name' => 'Pak Agus',
                'email' => 'agus.collector@iwakmart.com',
                'password' => Hash::make('password123'),
                'phone' => '08567890123'
            ],
            [
                'name' => 'Sari Dewi',
                'email' => 'sari.collector@iwakmart.com',
                'password' => Hash::make('password123'),
                'phone' => '08678901234'
            ]
        ];

        // Create farmer users (or get existing ones)
        $farmerUsers = [];
        foreach ($farmers as $farmer) {
            $existingUser = User::where('email', $farmer['email'])->first();
            if ($existingUser) {
                // Update role for existing user
                $existingUser->update(['role' => 'pemilik_tambak']);
                $farmerUsers[] = $existingUser;
            } else {
                // Create new user with role
                $farmer['role'] = 'pemilik_tambak';
                $farmerUsers[] = User::create($farmer);
            }
        }

        // Create collector users (or get existing ones)
        $collectorUsers = [];
        foreach ($collectors as $collector) {
            $existingUser = User::where('email', $collector['email'])->first();
            if ($existingUser) {
                // Update role for existing user
                $existingUser->update(['role' => 'pengepul']);
                $collectorUsers[] = $existingUser;
            } else {
                // Create new user with role
                $collector['role'] = 'pengepul';
                $collectorUsers[] = User::create($collector);
            }
        }

        // Create test fish farms
        $fishFarms = [
            [
                'user_id' => $farmerUsers[0]->id,
                'nama' => 'Tambak Lele Budi',
                'banyak_bibit' => 10000,
                'lokasi_koordinat' => '-6.2088,106.8456', // Jakarta area
                'alamat' => 'Jl. Raya Tambak No. 123, Bekasi, Jawa Barat 17530',
                'jenis_ikan' => 'Lele',
                'luas_tambak' => 2000,
                'no_telepon' => '08123456789',
                'status' => 'aktif',
                'foto' => null
            ],
            [
                'user_id' => $farmerUsers[0]->id,
                'nama' => 'Tambak Nila Berkah',
                'banyak_bibit' => 8000,
                'lokasi_koordinat' => '-6.2188,106.8556',
                'alamat' => 'Jl. Tambak Indah No. 45, Bekasi, Jawa Barat 17531',
                'jenis_ikan' => 'Nila',
                'luas_tambak' => 1500,
                'no_telepon' => '08123456789',
                'status' => 'aktif',
                'foto' => null
            ],
            [
                'user_id' => $farmerUsers[1]->id,
                'nama' => 'Tambak Gurame Siti',
                'banyak_bibit' => 5000,
                'lokasi_koordinat' => '-6.1988,106.8356',
                'alamat' => 'Jl. Kolam Indah No. 67, Depok, Jawa Barat 16424',
                'jenis_ikan' => 'Gurame',
                'luas_tambak' => 1200,
                'no_telepon' => '08234567890',
                'status' => 'aktif',
                'foto' => null
            ],
            [
                'user_id' => $farmerUsers[1]->id,
                'nama' => 'Tambak Patin Modern',
                'banyak_bibit' => 12000,
                'lokasi_koordinat' => '-6.2288,106.8656',
                'alamat' => 'Jl. Aquaculture No. 89, Tangerang, Banten 15119',
                'jenis_ikan' => 'Patin',
                'luas_tambak' => 2500,
                'no_telepon' => '08234567890',
                'status' => 'aktif',
                'foto' => null
            ],
            [
                'user_id' => $farmerUsers[2]->id,
                'nama' => 'Tambak Mujair Ahmad',
                'banyak_bibit' => 7000,
                'lokasi_koordinat' => '-6.1788,106.8256',
                'alamat' => 'Jl. Ikan Segar No. 12, Bogor, Jawa Barat 16151',
                'jenis_ikan' => 'Mujair',
                'luas_tambak' => 1800,
                'no_telepon' => '08345678901',
                'status' => 'aktif',
                'foto' => null
            ]
        ];

        $createdFishFarms = [];
        foreach ($fishFarms as $fishFarm) {
            $createdFishFarms[] = FishFarm::create($fishFarm);
        }

        // Create test collectors
        $collectorsData = [
            [
                'user_id' => $collectorUsers[0]->id,
                'nama' => 'Pengepul Ikan Lastri',
                'deskripsi' => 'Pengepul ikan segar dengan kualitas terjamin. Melayani pembelian ikan lele, nila, dan mujair dengan harga terbaik.',
                'lokasi_koordinat' => '-6.2008,106.8406',
                'alamat' => 'Jl. Pasar Ikan No. 34, Jakarta Timur, DKI Jakarta 13220',
                'rate_harga_per_kg' => 25000,
                'no_telepon' => '08456789012',
                'status' => 'aktif',
                'kapasitas_maksimal' => 1000,
                'jam_operasional_mulai' => '06:00',
                'jam_operasional_selesai' => '18:00',
                'jenis_ikan_diterima' => json_encode(['Lele', 'Nila', 'Mujair']),
                'foto' => null
            ],
            [
                'user_id' => $collectorUsers[1]->id,
                'nama' => 'CV Ikan Segar Agus',
                'deskripsi' => 'Perusahaan pengepul ikan dengan jaringan distribusi luas. Menerima semua jenis ikan air tawar dengan sistem pembayaran cepat.',
                'lokasi_koordinat' => '-6.1908,106.8306',
                'alamat' => 'Jl. Industri Perikanan No. 78, Bekasi, Jawa Barat 17520',
                'rate_harga_per_kg' => 28000,
                'no_telepon' => '08567890123',
                'status' => 'aktif',
                'kapasitas_maksimal' => 2000,
                'jam_operasional_mulai' => '05:00',
                'jam_operasional_selesai' => '19:00',
                'jenis_ikan_diterima' => json_encode(['Lele', 'Nila', 'Gurame', 'Patin']),
                'foto' => null
            ],
            [
                'user_id' => $collectorUsers[2]->id,
                'nama' => 'Sari Ikan Premium',
                'deskripsi' => 'Spesialis pengepul ikan premium untuk restoran dan hotel. Mengutamakan kualitas dan kesegaran ikan.',
                'lokasi_koordinat' => '-6.2108,106.8506',
                'alamat' => 'Jl. Premium Food No. 56, Jakarta Selatan, DKI Jakarta 12560',
                'rate_harga_per_kg' => 32000,
                'no_telepon' => '08678901234',
                'status' => 'aktif',
                'kapasitas_maksimal' => 800,
                'jam_operasional_mulai' => '07:00',
                'jam_operasional_selesai' => '17:00',
                'jenis_ikan_diterima' => json_encode(['Gurame', 'Patin', 'Nila']),
                'foto' => null
            ]
        ];

        $createdCollectors = [];
        foreach ($collectorsData as $collectorData) {
            $createdCollectors[] = Collector::create($collectorData);
        }

        // Create a default seller location for appointments
        $sellerLocation = SellerLocation::create([
            'user_id' => $farmerUsers[0]->id,
            'nama_usaha' => 'Lokasi Tambak Utama',
            'deskripsi' => 'Lokasi utama untuk penjemputan hasil tambak',
            'alamat_lengkap' => 'Jl. Raya Tambak No. 123, Bekasi, Jawa Barat 17530',
            'provinsi' => 'Jawa Barat',
            'kota' => 'Bekasi',
            'kecamatan' => 'Bekasi Utara',
            'kode_pos' => '17530',
            'latitude' => -6.2088,
            'longitude' => 106.8456,
            'aktif' => true,
            'jam_operasional' => json_encode(['senin' => '06:00-18:00', 'selasa' => '06:00-18:00']),
            'telepon' => '08123456789',
            'jenis_penjual' => 'pembudidaya'
        ]);

        // Create sample appointments
        $appointments = [
            [
                'fish_farm_id' => $createdFishFarms[0]->id,
                'collector_id' => $createdCollectors[0]->id,
                'penjual_id' => $createdFishFarms[0]->user_id, // Add fish farm owner as penjual
                'pembeli_id' => $createdCollectors[0]->user_id, // Add collector as pembeli
                'lokasi_penjual_id' => $sellerLocation->id,
                'tanggal_janji' => now()->addDays(2)->format('Y-m-d H:i:s'),
                'estimated_weight' => 500,
                'price_per_kg' => 25000,
                'catatan' => 'Lele sudah siap panen, kualitas bagus',
                'status' => 'menunggu',
                'appointment_type' => 'pengepulan_ikan'
            ],
            [
                'fish_farm_id' => $createdFishFarms[1]->id,
                'collector_id' => $createdCollectors[1]->id,
                'penjual_id' => $createdFishFarms[1]->user_id, // Add fish farm owner as penjual
                'pembeli_id' => $createdCollectors[1]->user_id, // Add collector as pembeli
                'lokasi_penjual_id' => $sellerLocation->id,
                'tanggal_janji' => now()->addDays(1)->format('Y-m-d H:i:s'),
                'estimated_weight' => 400,
                'price_per_kg' => 28000,
                'catatan' => 'Nila ukuran konsumsi, siap dijemput',
                'status' => 'dikonfirmasi',
                'appointment_type' => 'pengepulan_ikan'
            ],
            [
                'fish_farm_id' => $createdFishFarms[2]->id,
                'collector_id' => $createdCollectors[2]->id,
                'penjual_id' => $createdFishFarms[2]->user_id, // Add fish farm owner as penjual
                'pembeli_id' => $createdCollectors[2]->user_id, // Add collector as pembeli
                'lokasi_penjual_id' => $sellerLocation->id,
                'tanggal_janji' => now()->subDays(1)->format('Y-m-d H:i:s'),
                'estimated_weight' => 300,
                'price_per_kg' => 32000,
                'catatan' => 'Gurame premium untuk restoran',
                'status' => 'selesai',
                'appointment_type' => 'pengepulan_ikan',
                'whatsapp_summary' => json_encode([
                    'tanggal' => now()->subDays(1)->format('Y-m-d'),
                    'berat_aktual' => 285,
                    'total_harga' => 9120000,
                    'status' => 'selesai'
                ])
            ]
        ];

        foreach ($appointments as $appointment) {
            Appointment::create($appointment);
        }

        $this->command->info('✅ Test data created successfully!');
        $this->command->info('👨‍🌾 Farmers created: ' . count($farmerUsers));
        $this->command->info('🐟 Fish farms created: ' . count($createdFishFarms));
        $this->command->info('🚛 Collectors created: ' . count($createdCollectors));
        $this->command->info('📅 Appointments created: ' . count($appointments));
        $this->command->info('');
        $this->command->info('🔐 Login credentials:');
        $this->command->info('Farmers:');
        foreach ($farmers as $farmer) {
            $this->command->info("  📧 {$farmer['email']} | 🔑 password123");
        }
        $this->command->info('');
        $this->command->info('Collectors:');
        foreach ($collectors as $collector) {
            $this->command->info("  📧 {$collector['email']} | 🔑 password123");
        }
    }
}
