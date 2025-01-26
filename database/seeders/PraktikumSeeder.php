<?php

namespace Database\Seeders;

use App\Models\Praktikum;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PraktikumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Praktikum::insert([
            [
                'name' => 'Praktikum Logika Digital',
                'slug' => 'praktikum-logika-digital',
            ],
            [
                'name' => 'Organisasi dan Arsitektur Komputer 1',
                'slug' => 'organisasi-dan-arsitektur-komputer-1',
            ],
            [
                'name' => 'Organisasi dan Arsitektur Komputer 2',
                'slug' => 'organisasi-dan-arsitektur-komputer-2',
            ]
        ]);
    }
}
