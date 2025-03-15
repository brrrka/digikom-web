<?php

namespace Database\Seeders;

use App\Models\Asisten;
use App\Models\Inventaris;
use App\Models\Konten;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            InventarisSeeder::class,
            PraktikumSeeder::class,
            ModulSeeder::class,
            KontenSeeder::class,
            ArtikelSeeder::class,
            AsistenSeeder::class,
        ]);
    }
}
