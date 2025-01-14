<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_users',
        'id_inventaris',
        'kuantitas',
        'tanggal_peminjaman',
        'tanggal_selesai',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_users');
    }

    public function inventaris()
    {
        return $this->belongsTo(Inventaris::class, 'id_inventaris');
    }
}
