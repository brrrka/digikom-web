<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'nim',
        'password',
        'id_roles',
        'no_telp'
    ];

    protected $attributes = [
        'id_roles' => 3
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'id_roles');
    }

    public function asisten()
    {
        return $this->hasMany(Asisten::class, 'id_users');
    }

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'id_users');
    }

    public function artikel()
    {
        return $this->hasMany(Artikel::class, 'id_users');
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
