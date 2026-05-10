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
        \App\Models\User::create([
            'name' => 'Admin User',
            'nama' => 'Admin',
            'email' => 'admin@tintaku.com',
            'role' => 1, // Super Admin
            'password' => \Hash::make('password'),
        ]);

        \App\Models\User::create([
            'name' => 'Regular Admin',
            'nama' => 'Admin Staff',
            'email' => 'staff@tintaku.com',
            'role' => 0, // Admin
            'password' => \Hash::make('password'),
        ]);
    }
}
