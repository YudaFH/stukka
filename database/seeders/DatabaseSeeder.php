<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Buat 1 Akun Admin Default
        User::updateOrCreate(
            ['email' => 'admin@stukka.com'],
            [
                'name' => 'Admin Stukka',
                'password' => Hash::make('password'),
                'usertype' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // Buat 1 Akun Karyawan Default
        User::updateOrCreate(
            ['email' => 'karyawan@stukka.com'],
            [
                'name' => 'Budi Santoso',
                'nip' => 'STK-2026-001',
                'position' => 'Event Crew & Technician',
                'password' => Hash::make('password'),
                'usertype' => 'karyawan',
                'email_verified_at' => now(),
            ]
        );

        // Buat 1 Akun User Biasa (Untuk ngetes)
        User::updateOrCreate(
            ['email' => 'user@gmail.com'],
            [
                'name' => 'User Biasa',
                'password' => Hash::make('password'),
                'usertype' => 'user',
                'email_verified_at' => now(),
            ]
        );
    }
}