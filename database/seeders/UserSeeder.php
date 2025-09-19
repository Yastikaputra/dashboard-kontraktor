<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Membuat atau memperbarui Admin
        User::updateOrCreate(
            ['username' => 'admin'], // Kunci untuk mencari
            [
                'name' => 'Administrator',
                'email' => 'admin@example.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        // Membuat atau memperbarui User Biasa
        User::updateOrCreate(
            ['username' => 'user1'], // Kunci untuk mencari
            [
                'name' => 'User Biasa',
                'email' => 'user1@example.com',
                'password' => Hash::make('user123'),
                'role' => 'user',
            ]
        );
    }
}

