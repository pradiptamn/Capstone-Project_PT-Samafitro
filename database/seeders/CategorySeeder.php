<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Read the categories from the JSON file
        $jsonPath = database_path('data/categories.json');
        $jsonData = json_decode(file_get_contents($jsonPath), true);

        if (!$jsonData || !isset($jsonData['category'])) {
            $this->command->error('Could not read categories.json file or invalid format');
            return;
        }

        $categories = $jsonData['category'];
        $count = 0;

        foreach ($categories as $categoryData) {
            // Check if category already exists to avoid duplicates
            $existingCategory = Category::find($categoryData['id']);

            if (!$existingCategory) {
                Category::create([
                    'id' => $categoryData['id'],
                    'name' => $categoryData['name'],
                ]);
                $count++;
            }
        }

        $this->command->info("Successfully seeded {$count} categories");
    }
}
