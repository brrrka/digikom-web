<?php

namespace Database\Seeders;

use App\Models\Asisten;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AsistenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::where('id_roles', 2)->get();

        foreach ($users as $user) {
            Asisten::create([
                'id_users' => $user->id,
                'divisi' => fake()->randomElement(['IPI', 'Inti', 'Rnd', 'Multimedia']),
                'jabatan' => fake()->randomElement(['Koordinator', 'Anggota']),
                'angkatan' => fake()->numberBetween(2019, 2024),
                'images' => fake()->imageUrl(200, 200, 'person', true, 'Asisten'),
            ]);
        }
    }
}
