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
        // 1. Membuat User Admin (Kontraktor)
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Admin Kontraktor',
                // 'email' => 'admin@example.com', // BARIS INI HARUS DIHAPUS
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        // 2. Membuat User Biasa (Owner)
        User::updateOrCreate(
            ['username' => 'owner'],
            [
                'name' => 'Owner Proyek',
                // 'email' => 'owner@example.com', // BARIS INI HARUS DIHAPUS
                'password' => Hash::make('owner123'),
                'role' => 'user',
            ]
        );
    }
}