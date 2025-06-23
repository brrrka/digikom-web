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
        'kuantitas',
        'status',
        'deskripsi',
        'images'
    ];

    protected $casts = [
        'kuantitas' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relasi dengan DetailPeminjaman
    public function detailPeminjaman()
    {
        return $this->hasMany(DetailPeminjaman::class, 'id_inventaris');
    }

    // Relasi dengan Peminjaman melalui DetailPeminjaman
    public function peminjaman()
    {
        return $this->belongsToMany(Peminjaman::class, 'detail_peminjaman', 'id_inventaris', 'id_peminjaman')
            ->withPivot('kuantitas');
    }

    // Scope untuk filter berdasarkan status
    public function scopeStatus($query, $status)
    {
        if ($status) {
            return $query->where('status', $status);
        }
        return $query;
    }

    // Scope untuk pencarian
    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                    ->orWhere('deskripsi', 'like', '%' . $search . '%');
            });
        }
        return $query;
    }

    // Accessor untuk mendapatkan URL gambar
    public function getImageUrlAttribute()
    {
        if ($this->images) {
            return \Storage::url($this->images);
        }
        return null;
    }

    // Method untuk mengecek apakah inventaris bisa dihapus
    public function canBeDeleted()
    {
        return $this->detailPeminjaman()->count() === 0;
    }

    // Method untuk menghitung total yang sedang dipinjam
    public function getTotalDipinjam()
    {
        return $this->detailPeminjaman()
            ->whereHas('peminjaman', function ($query) {
                $query->where('status', 'dipinjam');
            })
            ->sum('kuantitas');
    }

    // Method untuk menghitung kuantitas tersedia
    public function getKuantitasTersedia()
    {
        return $this->kuantitas - $this->getTotalDipinjam();
    }
}
