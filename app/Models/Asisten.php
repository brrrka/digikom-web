<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asisten extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_users',
        'divisi',
        'jabatan',
        'angkatan',
        'images',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_users')->where('id_roles', 2);
    }
}
