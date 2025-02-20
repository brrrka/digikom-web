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

    public function modul()
    {
        return $this->hasMany(Modul::class, 'id_praktikums');
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($praktikum) {
            $praktikum->slug = Str::slug($praktikum->name);
        });
    }
}
