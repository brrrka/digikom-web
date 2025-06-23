<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modul extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_praktikums',
        'modul_ke',
        'title',
        'deskripsi',  // Tambahan field
        'file_path',
        'link_video',
        'images',     // Tambahan field
    ];

    public function praktikum()
    {
        return $this->belongsTo(Praktikum::class, 'id_praktikums');
    }

    protected $casts = [
        'file_path' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Accessor untuk mendapatkan URL file
    public function getFileUrlAttribute()
    {
        if ($this->file_path) {
            return \Storage::url($this->file_path);
        }
        return null;
    }

    // Accessor untuk mendapatkan URL gambar
    public function getImageUrlAttribute()
    {
        if ($this->images) {
            return \Storage::url($this->images);
        }
        return null;
    }

    // Scope untuk filter berdasarkan praktikum
    public function scopeByPraktikum($query, $praktikumId)
    {
        if ($praktikumId) {
            return $query->where('id_praktikums', $praktikumId);
        }
        return $query;
    }

    // Scope untuk pencarian
    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('deskripsi', 'like', '%' . $search . '%');
            });
        }
        return $query;
    }

    // Method untuk mendapatkan ekstensi file
    public function getFileExtension()
    {
        if ($this->file_path) {
            return pathinfo($this->file_path, PATHINFO_EXTENSION);
        }
        return null;
    }

    // Method untuk mendapatkan ukuran file dalam format yang readable
    public function getFileSizeFormatted()
    {
        if ($this->file_path) {
            try {
                if (\Storage::exists('public/' . $this->file_path)) {
                    $bytes = \Storage::size('public/' . $this->file_path);
                    $units = ['B', 'KB', 'MB', 'GB'];
                    $i = 0;
                    while ($bytes >= 1024 && $i < count($units) - 1) {
                        $bytes /= 1024;
                        $i++;
                    }
                    return round($bytes, 2) . ' ' . $units[$i];
                }
            } catch (\Exception $e) {
                // File might not exist or storage error
                return '0 B';
            }
        }
        return '0 B';
    }
}
