<?php

namespace Database\Seeders;

use App\Models\Asisten;
use App\Models\Inventaris;
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
            PraktikumSeeder::class,
            ModulSeeder::class,
        ]);
    }
}
