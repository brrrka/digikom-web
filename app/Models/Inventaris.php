<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventaris extends Model
{
    use HasFactory;

    protected $table = 'inventaris';

    protected $fillable = [
        'nama',
        'deskripsi',
        'kuantitas',
        'images',
        'status',
    ];

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'id_inventaris');
    }
}