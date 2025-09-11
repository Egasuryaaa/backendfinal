<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data to avoid duplicates
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Product::truncate();
        Category::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Get seller user (harus ada seller untuk buat products)
        $seller = User::where('email', 'seller@iwakmart.com')->first();
        if (!$seller) {
            $this->command->error('❌ Seller user not found! Please run AdminSeeder first.');
            return;
        }

        // Create Categories
        $categories = [
            [
                'nama' => 'Ikan Segar',
                'slug' => 'ikan-segar',
                'deskripsi' => 'Berbagai macam ikan segar hasil tangkapan hari ini',
                'aktif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Ikan Olahan',
                'slug' => 'ikan-olahan',
                'deskripsi' => 'Produk olahan ikan siap saji',
                'aktif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Seafood',
                'slug' => 'seafood',
                'deskripsi' => 'Hasil laut segar seperti udang, cumi, kepiting',
                'aktif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Frozen',
                'slug' => 'frozen',
                'deskripsi' => 'Produk beku berkualitas tinggi',
                'aktif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($categories as $categoryData) {
            Category::create($categoryData);
        }

        // Create Sample Products
        $products = [
            // Ikan Segar
            [
                'nama' => 'Ikan Nila Segar',
                'slug' => 'ikan-nila-segar',
                'deskripsi' => 'Ikan nila segar hasil budidaya organik, sangat cocok untuk digoreng atau dibakar',
                'harga' => 25000,
                'stok' => 100,
                'kategori_id' => 1,
                'penjual_id' => $seller->id,
                'gambar' => json_encode(['https://via.placeholder.com/300x200?text=Ikan+Nila']),
                'berat' => 0.5, // 500 gram = 0.5 kg
                'jenis_ikan' => 'segar',
                'spesies_ikan' => 'Nila',
                'aktif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Ikan Gurame Segar',
                'slug' => 'ikan-gurame-segar',
                'deskripsi' => 'Ikan gurame segar pilihan, daging tebal dan tidak amis',
                'harga' => 45000,
                'stok' => 50,
                'kategori_id' => 1,
                'penjual_id' => $seller->id,
                'gambar' => json_encode(['https://via.placeholder.com/300x200?text=Ikan+Gurame']),
                'berat' => 0.8,
                'jenis_ikan' => 'segar',
                'spesies_ikan' => 'Gurame',
                'aktif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Ikan Lele Segar',
                'slug' => 'ikan-lele-segar',
                'deskripsi' => 'Ikan lele segar dari kolam sendiri, bebas dari bahan kimia',
                'harga' => 18000,
                'stok' => 150,
                'kategori_id' => 1,
                'penjual_id' => $seller->id,
                'gambar' => json_encode(['https://via.placeholder.com/300x200?text=Ikan+Lele']),
                'berat' => 0.3,
                'jenis_ikan' => 'segar',
                'spesies_ikan' => 'Lele',
                'aktif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Ikan Olahan
            [
                'nama' => 'Ikan Asin Jambal',
                'slug' => 'ikan-asin-jambal',
                'deskripsi' => 'Ikan asin jambal kualitas premium, cocok untuk lauk sehari-hari',
                'harga' => 35000,
                'stok' => 80,
                'kategori_id' => 2,
                'penjual_id' => $seller->id,
                'gambar' => json_encode(['https://via.placeholder.com/300x200?text=Ikan+Asin']),
                'berat' => 0.25,
                'jenis_ikan' => 'olahan',
                'spesies_ikan' => 'Jambal',
                'aktif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Kerupuk Ikan',
                'slug' => 'kerupuk-ikan',
                'deskripsi' => 'Kerupuk ikan buatan rumahan, renyah dan gurih',
                'harga' => 15000,
                'stok' => 200,
                'kategori_id' => 2,
                'penjual_id' => $seller->id,
                'gambar' => json_encode(['https://via.placeholder.com/300x200?text=Kerupuk+Ikan']),
                'berat' => 0.1,
                'jenis_ikan' => 'olahan',
                'spesies_ikan' => 'Mixed',
                'aktif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Seafood
            [
                'nama' => 'Udang Segar',
                'slug' => 'udang-segar',
                'deskripsi' => 'Udang segar hasil tangkapan nelayan lokal, ukuran besar',
                'harga' => 75000,
                'stok' => 30,
                'kategori_id' => 3,
                'penjual_id' => $seller->id,
                'gambar' => json_encode(['https://via.placeholder.com/300x200?text=Udang+Segar']),
                'berat' => 0.5,
                'jenis_ikan' => 'segar',
                'spesies_ikan' => 'Udang',
                'aktif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Cumi Segar',
                'slug' => 'cumi-segar',
                'deskripsi' => 'Cumi-cumi segar, daging kenyal dan manis',
                'harga' => 60000,
                'stok' => 25,
                'kategori_id' => 3,
                'penjual_id' => $seller->id,
                'gambar' => json_encode(['https://via.placeholder.com/300x200?text=Cumi+Segar']),
                'berat' => 0.4,
                'jenis_ikan' => 'segar',
                'spesies_ikan' => 'Cumi',
                'aktif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Frozen
            [
                'nama' => 'Ikan Salmon Fillet Frozen',
                'slug' => 'ikan-salmon-fillet-frozen',
                'deskripsi' => 'Fillet salmon premium beku, kaya omega-3',
                'harga' => 150000,
                'stok' => 20,
                'kategori_id' => 4,
                'penjual_id' => $seller->id,
                'gambar' => json_encode(['https://via.placeholder.com/300x200?text=Salmon+Fillet']),
                'berat' => 0.3,
                'jenis_ikan' => 'beku',
                'spesies_ikan' => 'Salmon',
                'aktif' => true,
                'unggulan' => true, // produk unggulan
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Nugget Ikan Frozen',
                'slug' => 'nugget-ikan-frozen',
                'deskripsi' => 'Nugget ikan siap goreng, praktis dan lezat',
                'harga' => 25000,
                'stok' => 100,
                'kategori_id' => 4,
                'penjual_id' => $seller->id,
                'gambar' => json_encode(['https://via.placeholder.com/300x200?text=Nugget+Ikan']),
                'berat' => 0.25,
                'jenis_ikan' => 'beku',
                'spesies_ikan' => 'Mixed',
                'aktif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($products as $productData) {
            Product::create($productData);
        }

        $this->command->info('✅ Categories and Products seeded successfully!');
        $this->command->info('📦 Created 4 categories and 10 products');
        $this->command->info('🐟 All products assigned to seller: ' . $seller->name);
    }
}
