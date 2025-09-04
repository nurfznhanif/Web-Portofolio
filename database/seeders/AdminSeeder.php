<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Nurfauzan Hanif',
            'email' => 'nrfznhnf@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'), // Ganti dengan password yang aman
        ]);
    }
}
