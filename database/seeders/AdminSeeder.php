<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin User
        \App\Models\User::create([
            'name' => 'Admin User',
            'nama' => 'Admin',
            'email' => 'admin@tintaku.com',
            'role' => 1, // Admin
            'password' => \Hash::make('password'),
        ]);

        // Regular User / Pembeli
        \App\Models\User::create([
            'name' => 'Regular User',
            'nama' => 'User Biasa',
            'email' => 'staff@tintaku.com',
            'role' => 0, // Regular User
            'password' => \Hash::make('password'),
        ]);
    }
}
