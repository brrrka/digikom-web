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
            'nim' => '2211513026',
            'password' => Hash::make('digikomweb'),
            'id_roles' => 1
        ]);

        User::factory()->create([
            'name' => 'Asisten Digikom',
            'email' => 'asisten@gmail.com',
            'nim' => '2211513025',
            'password' => Hash::make('password123'),
            'id_roles' => 2
        ]);

        User::factory()->create([
            'name' => 'Berka Ganteng',
            'email' => 'user@gmail.com',
            'nim' => '2211513024',
            'password' => Hash::make('password123'),
            'id_roles' => 2
        ]);
    }
}
