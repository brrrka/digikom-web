<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Artikel extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_users',
        'title',
        'content',
        'image',
        'status'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'id_users');
    }
}