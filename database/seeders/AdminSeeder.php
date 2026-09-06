<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@umkmkutim.go.id'],
            [
                'name' => 'Administrator Diskop & UMKM Kutai Timur',
                'role' => 'admin',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );
    }
}
