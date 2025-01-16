<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin Digikom',
            'email' => 'admin@digikom.com',
            'password' => Hash::make('digikomweb'),
            'id_roles' => 1
        ]);

        User::factory()->create([
            'name' => 'Asisten Digikom',
            'email' => 'asisten@gmail.com',
            'password' => Hash::make('password123'),
            'id_roles' => 2
        ]);

        User::factory()->create([
            'name' => 'John Doe',
            'email' => 'johndoe@gmail.com',
            'password' => Hash::make('password123'),
            'id_roles' => 3
        ]);

        User::factory()->create([
            'name' => 'User Kedua',
            'email' => 'user2@gmail.com',
            'password' => Hash::make('password123'),
            'id_roles' => 3
        ]);

        User::factory()->create([
            'name' => 'User Ketiga',
            'email' => 'user3   @gmail.com',
            'password' => Hash::make('password123'),
            'id_roles' => 3
        ]);
    }
}
