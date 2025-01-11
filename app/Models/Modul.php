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
        'deskripsi',
        'file_path',
        'images',
    ];

    public function praktikum()
    {
        return $this->belongsTo(Praktikum::class, 'id_praktikums');
    }
}
