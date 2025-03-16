<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;
    protected $table = 'peminjamans';
    protected $fillable = [
        'id_users',
        'tanggal_peminjaman',
        'tanggal_selesai',
        'tanggal_pengembalian',
        'jangka',
        'alasan',
        'status',
        'bukti_path'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_users');
    }

    public function detailPeminjaman()
    {
        return $this->hasMany(DetailPeminjaman::class, 'id_peminjaman');
    }

    public function inventaris()
    {
        return $this->belongsToMany(Inventaris::class, 'detail_peminjaman', 'id_peminjaman', 'id_inventaris')
            ->withPivot('kuantitas');
    }
}