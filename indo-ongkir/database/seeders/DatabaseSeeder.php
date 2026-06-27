<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; // Tambahkan import ini

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Membuat akun admin utama
        User::factory()->create([
            'name' => 'Admin Test',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'), // Menggunakan Hash::make lebih clean
        ]);

        // 2. Tambahan (Opsional): Membuat 10 akun user dummy random buat testing data
        // User::factory(10)->create();
    }
}