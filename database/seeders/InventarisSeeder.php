<?php

namespace Database\Seeders;

use App\Models\Inventaris;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InventarisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Inventaris::insert([
            [
                'nama' => 'ESP32',
                'deskripsi' => 'Mikrokontroller Keren.',
                'kuantitas' => 5,
                'images' => 'laptop-lenovo.png',
                'status' => 'tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Proyektor Epson',
                'deskripsi' => 'Proyektor untuk presentasi.',
                'kuantitas' => 1,
                'images' => 'proyektor-epson.png',
                'status' => 'tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Mainan Lego',
                'deskripsi' => 'Router untuk koneksi jaringan.',
                'kuantitas' => 0,
                'images' => 'router-mikrotik.png',
                'status' => 'tidak tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
