<?php

namespace Database\Seeders;

use App\Models\Modul;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModulSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Modul::insert([
            [
                'id_praktikums' => 1,
                'modul_ke' => 1,
                'title' => 'Gerbang Logika',
                'file_path' => 'modul_1.pdf',
            ],
            [
                'id_praktikums' => 2,
                'modul_ke' => 4,
                'title' => 'Wombat 1',
                'file_path' => 'modul_4.pdf',
            ],
            [
                'id_praktikums' => 3,
                'modul_ke' => 2,
                'title' => 'Processor',
                'file_path' => 'modul_2.pdf',
            ],
        ]);
    }
}
