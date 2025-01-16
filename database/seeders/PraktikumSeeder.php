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
                'name' => 'Praktikum Logika Digital'
            ],
            [
                'name' => 'Organisasi dan Arsitektur Komputer 1'
            ],
            [
                'name' => 'Organisasi dan Arsitektur Komputer 2'
            ]
        ]);
    }
}
