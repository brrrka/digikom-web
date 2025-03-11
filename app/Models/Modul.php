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
        'link_video',
    ];

    public function praktikum()
    {
        return $this->belongsTo(Praktikum::class, 'id_praktikums');
    }

    protected $casts = [
        'file_path' => 'string',
    ];
}
