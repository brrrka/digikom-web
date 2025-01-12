<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['roles' => 'superadmin']);
        Role::create(['roles' => 'asisten']);
        Role::create(['roles' => 'user']);
    }
}
