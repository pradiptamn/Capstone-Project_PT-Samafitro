<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Create admin user if it doesn't exist
        User::firstOrCreate(
            [
                'name' => 'Admin User',
                'email' => 'admin@samafitro.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'phone' => '08123456788',
                'ktp_number' => '1234567890123455',
                'security_question' => 'What is your favorite color?',
                'security_answer' => Hash::make('admin'),
            ]
        );

        // Seed categories
        $this->call([
            CategorySeeder::class,
            ProductSeeder::class,
        ]);
    }
}
