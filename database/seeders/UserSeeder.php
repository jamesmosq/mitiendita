<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name'     => 'Admin Latiendita',
            'email'    => 'admin@latiendita.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        // Cliente de prueba
        User::create([
            'name'     => 'Cliente Demo',
            'email'    => 'cliente@latiendita.com',
            'password' => Hash::make('password'),
            'role'     => 'customer',
        ]);

        // 10 clientes aleatorios
        User::factory(10)->create(['role' => 'customer']);
    }
}