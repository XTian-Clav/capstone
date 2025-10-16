<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        // Example user
        User::create([
            'name' => 'superadmin',
            'email' => 'superadmin@gmail.com',
            'contact' => '09933685061',
            'address' => 'Puerto Princesa, Palawan',
            'birthdate' => '12-16-1999',
            'birthplace' => 'Davao City, Philippines',
            'password' => Hash::make('password'),
        ]);
    }
}