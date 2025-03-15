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
        'total_dipinjam',
        'status',
    ];

    public function detailPeminjaman()
    {
        return $this->hasMany(DetailPeminjaman::class, 'id_inventaris');
    }

    public function peminjamans()
    {
        return $this->belongsToMany(Peminjaman::class, 'detail_peminjaman', 'id_inventaris', 'id_peminjaman')
            ->withPivot('kuantitas');
    }

    public function getAvailableQuantityAttribute()
    {
        return $this->kuantitas - $this->total_dipinjam;
    }
}
