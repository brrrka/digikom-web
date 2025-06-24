<?php

namespace Database\Seeders;

use App\Models\Artikel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ArtikelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            Artikel::create([
                'id_users' => 2,
                'title' => "Artikel Contoh $i",
                'content' => "Ini adalah isi artikel contoh ke-$i. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus lacinia odio vitae vestibulum vestibulum.",
                'image' => 'images/sample-image-' . $i . '.jpg',
                'status' => 'published',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }    }
}
