<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@assessment.com',
            'password' => Hash::make('pass123'),
            'role' => 'admin',
        ]);

        // Create participant users
        User::create([
            'name' => 'Ahmad Rizki',
            'email' => 'ahmad.rizki@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        User::create([
            'name' => 'Siti Nurhaliza',
            'email' => 'siti.nurhaliza@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi.santoso@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        User::create([
            'name' => 'Dewi Sartika',
            'email' => 'dewi.sartika@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        User::create([
            'name' => 'Eko Prasetyo',
            'email' => 'eko.prasetyo@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);
    }
}
