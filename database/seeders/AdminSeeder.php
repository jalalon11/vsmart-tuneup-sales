<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Vincent Jalalon',
            'email' => 'vinzjalalon@gmail.com',
            'password' => Hash::make('deadlock113'),
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);
    }
} 