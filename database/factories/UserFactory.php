<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'username' => fake()->unique()->userName(), // Diubah dari email
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            // Kolom email_verified_at tidak perlu diisi di sini karena nullable
        ];
    }
}