<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Akun Admin
        User::updateOrCreate(
            ['email' => 'admin@manajemenkopi.test'],
            [
                'name'              => 'Administrator',
                'password'          => Hash::make('password'),
                'role'              => User::ROLE_ADMIN,
                'email_verified_at' => now(),
            ]
        );

        // Akun Sales (Demo)
        User::updateOrCreate(
            ['email' => 'sales@manajemenkopi.test'],
            [
                'name'              => 'Sales Demo',
                'password'          => Hash::make('password'),
                'role'              => User::ROLE_SALES,
                'email_verified_at' => now(),
            ]
        );
    }
}
