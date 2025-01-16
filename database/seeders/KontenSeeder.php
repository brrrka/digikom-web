<?php

namespace Database\Seeders;

use App\Models\Konten;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KontenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Konten::insert([
            [
                'title' => 'Tutorial MultiSIM',
                'deskripsi' => 'Tutorial paling ampuh belajar multsim, langsung bisa',
                'video_id' => 'dQw4w9WgXcQ',
            ],
        ]);
    }
}
