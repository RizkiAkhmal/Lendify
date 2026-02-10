<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '081234567890',
            'address' => 'Jl. Admin No. 1, Jakarta',
            'status' => 'active',
        ]);

        // Petugas
        User::create([
            'name' => 'Petugas Lab',
            'email' => 'petugas@example.com',
            'password' => Hash::make('password'),
            'role' => 'petugas',
            'phone' => '081234567891',
            'address' => 'Jl. Petugas No. 2, Jakarta',
            'status' => 'active',
        ]);

        // Peminjam 1
        User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'password' => Hash::make('password'),
            'role' => 'peminjam',
            'phone' => '081234567892',
            'address' => 'Jl. Peminjam No. 3, Jakarta',
            'status' => 'active',
        ]);

        // Peminjam 2
        User::create([
            'name' => 'Siti Aminah',
            'email' => 'siti@example.com',
            'password' => Hash::make('password'),
            'role' => 'peminjam',
            'phone' => '081234567893',
            'address' => 'Jl. Peminjam No. 4, Jakarta',
            'status' => 'active',
        ]);
    }
}
