<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Read the products from the JSON file
        $jsonPath = database_path('data/products.json');
        $jsonData = json_decode(file_get_contents($jsonPath), true);

        if (!$jsonData || !isset($jsonData['products'])) {
            $this->command->error('Could not read products.json file or invalid format');
            return;
        }

        $products = $jsonData['products'];
        $count = 0;

        foreach ($products as $productData) {
            // Check if product already exists to avoid duplicates
            $existingProduct = Product::find($productData['id']);

            if (!$existingProduct) {
                Product::create([
                    'id' => $productData['id'],
                    'kategori_id' => $productData['kategori_id'],
                    'nama_produk' => $productData['nama_produk'],
                    'harga' => $productData['harga'],
                    'stok' => $productData['stok'],
                    'deskripsi' => $productData['deskripsi'],
                    'gambar' => $productData['gambar'],
                ]);
                $count++;
            }
        }

        $this->command->info("Successfully seeded {$count} products");
    }
}
