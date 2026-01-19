<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $securityQuestion = 'Apa makanan favorit Anda?';
        $commonAnswer = Hash::make('password');
        $commonPassword = Hash::make('password');

        // 1. Create Admin
        User::firstOrCreate(
            ['email' => 'admin@samafitro.com'],
            [
                'name' => 'Administrator Utama',
                'password' => $commonPassword,
                'role' => 'admin',
                'phone' => '08110000001',
                'ktp_number' => '3171000000000001',
                'security_question' => $securityQuestion,
                'security_answer' => $commonAnswer,
            ]
        );

        // 2. Create Manager
        User::firstOrCreate(
            ['email' => 'manager@samafitro.com'],
            [
                'name' => 'Manager Operasional',
                'password' => $commonPassword,
                'role' => 'manager',
                'phone' => '08110000002',
                'ktp_number' => '3171000000000002',
                'security_question' => $securityQuestion,
                'security_answer' => $commonAnswer,
            ]
        );

        // 3. Create Sales (2 Orang)
        foreach (range('A', 'B') as $index => $letter) {
            User::firstOrCreate(
                ['email' => "sales.$letter@samafitro.com"],
                [
                    'name' => "Sales Staff $letter",
                    'password' => $commonPassword,
                    'role' => 'sales',
                    'phone' => '0812000010' . ($index + 1),
                    'ktp_number' => '317100000000100' . ($index + 1),
                    'security_question' => $securityQuestion,
                    'security_answer' => $commonAnswer,
                ]
            );
        }

        // 4. Create Courier (3 Orang)
        foreach (range('A', 'C') as $index => $letter) {
            User::firstOrCreate(
                ['email' => "courier.$letter@samafitro.com"],
                [
                    'name' => "Courier $letter",
                    'password' => $commonPassword,
                    'role' => 'courier',
                    'phone' => '0813000020' . ($index + 1),
                    'ktp_number' => '317100000000200' . ($index + 1),
                    'security_question' => $securityQuestion,
                    'security_answer' => $commonAnswer,
                ]
            );
        }

        // 5. Create Regular Users (5 Orang)
        foreach (range('A', 'E') as $index => $letter) {
            User::firstOrCreate(
                ['email' => "user.$letter@gmail.com"],
                [
                    'name' => "Pelanggan $letter",
                    'password' => $commonPassword,
                    'role' => 'user',
                    'phone' => '0815000030' . ($index + 1),
                    'ktp_number' => '317100000000300' . ($index + 1),
                    'security_question' => $securityQuestion,
                    'security_answer' => $commonAnswer,
                ]
            );
        }
    }
}
