<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin Garda',
            'email' => 'admin@garda.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Pimpinan Garda',
            'email' => 'pimpinan@garda.com',
            'password' => Hash::make('password'),
            'role' => 'pimpinan',
        ]);
    }
}
