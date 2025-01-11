<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Praktikum extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
    ];

    public function modul()
    {
        return $this->hasMany(Modul::class, 'id_praktikums');
    }
}
