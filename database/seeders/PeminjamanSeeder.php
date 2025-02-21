<?php

namespace Database\Seeders;

use App\Models\Peminjaman;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PeminjamanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Peminjaman::insert([
            [
                'id_users' => 2,
                'id_inventaris' => 1,
                'kuantitas' => 1,
                'tanggal_peminjaman' => '2025-01-01',
                'tanggal_selesai' => '2025-01-05',
                'status' => 'diajukan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_users' => 2, 
                'id_inventaris' => 2,
                'kuantitas' => 1,
                'tanggal_peminjaman' => '2025-01-03',
                'tanggal_selesai' => '2025-01-07',
                'status' => 'dipinjam',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}