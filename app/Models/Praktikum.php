<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Praktikum extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'image',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // PERBAIKAN: Ganti nama method dari 'modul' menjadi 'moduls'
    public function moduls()
    {
        return $this->hasMany(Modul::class, 'id_praktikums');
    }

    // Untuk backward compatibility, tetap sediakan method 'modul'
    public function modul()
    {
        return $this->moduls();
    }

    // Accessor untuk mendapatkan URL gambar
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return \Storage::url($this->image);
        }
        return null;
    }

    // Scope untuk pencarian
    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where('name', 'like', '%' . $search . '%');
        }
        return $query;
    }

    // Method untuk mengecek apakah praktikum bisa dihapus
    public function canBeDeleted()
    {
        return $this->moduls()->count() === 0;
    }

    // Method untuk menghitung total modul
    public function getModulsCountAttribute()
    {
        return $this->moduls()->count();
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($praktikum) {
            $praktikum->slug = Str::slug($praktikum->name);
        });

        static::updating(function ($praktikum) {
            // Update slug only if name changed
            if ($praktikum->isDirty('name')) {
                $praktikum->slug = Str::slug($praktikum->name);
            }
        });
    }
}
