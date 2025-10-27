<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'superadmin',
                'email' => 'superadmin@gmail.com',
                'contact' => '09933685061',
                'company' => 'PITBI',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'admin',
                'email' => 'admin@gmail.com',
                'contact' => '09933685062',
                'company' => 'PITBI',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'incubatee',
                'email' => 'incubatee@gmail.com',
                'contact' => '09933685063',
                'company' => 'PITBI',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'investor',
                'email' => 'investor@gmail.com',
                'contact' => '09933685064',
                'company' => 'PITBI',
                'password' => Hash::make('password'),
            ],
        ];

        foreach ($users as $user) {
            User::firstOrCreate(
                ['email' => $user['email']], // ← unique check
                $user                        // ← values to create if not found
            );
        }
    }
}
