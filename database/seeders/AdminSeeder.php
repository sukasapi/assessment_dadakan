<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 3 admin users
        User::create([
            'name' => 'Admin Satu',
            'email' => 'admin1@cataa.hcmdss.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Admin Dua',
            'email' => 'admin2@cataa.hcmdss.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Admin Tiga',
            'email' => 'admin3@cataa.hcmdss.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);
    }
}

