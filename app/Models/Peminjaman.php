<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjamans';

    protected $fillable = [
        'id_users',
        'tanggal_peminjaman',
        'tanggal_selesai',
        'tanggal_pengembalian',
        'jangka',
        'alasan',
        'status',
        'bukti_path',
        'catatan'
    ];

    protected $casts = [
        'tanggal_peminjaman' => 'date',
        'tanggal_selesai' => 'date',
        'tanggal_pengembalian' => 'datetime',
    ];

    protected $dates = [
        'tanggal_peminjaman',
        'tanggal_selesai',
        'tanggal_pengembalian',
        'created_at',
        'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_users');
    }

    public function detailPeminjaman()
    {
        return $this->hasMany(DetailPeminjaman::class, 'id_peminjaman');
    }

    public function inventaris()
    {
        return $this->belongsToMany(Inventaris::class, 'detail_peminjaman', 'id_peminjaman', 'id_inventaris')
            ->withPivot('kuantitas');
    }

    /**
     * Get status badge color for UI
     */
    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            'diajukan' => 'bg-yellow-100 text-yellow-800',
            'disetujui' => 'bg-green-100 text-green-800',
            'dipinjam' => 'bg-blue-100 text-blue-800',
            'dikembalikan' => 'bg-gray-100 text-gray-800',
            'jatuh tenggat' => 'bg-red-100 text-red-800',
            'ditolak' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    /**
     * Get formatted status text
     */
    public function getStatusTextAttribute()
    {
        return match ($this->status) {
            'diajukan' => 'Diajukan',
            'disetujui' => 'Disetujui',
            'dipinjam' => 'Dipinjam',
            'dikembalikan' => 'Dikembalikan',
            'jatuh tenggat' => 'Jatuh Tenggat',
            'ditolak' => 'Ditolak',
            default => 'Unknown'
        };
    }

    /**
     * Check if peminjaman is overdue
     */
    public function getIsOverdueAttribute()
    {
        if ($this->status === 'dipinjam' && $this->tanggal_selesai) {
            return $this->tanggal_selesai->isPast();
        }
        return false;
    }

    /**
     * Get days remaining or overdue
     */
    public function getDaysRemainingAttribute()
    {
        if ($this->status === 'dipinjam' && $this->tanggal_selesai) {
            return $this->tanggal_selesai->diffInDays(now(), false);
        }
        return null;
    }

    /**
     * Get total quantity of items borrowed
     */
    public function getTotalQuantityAttribute()
    {
        return $this->detailPeminjaman->sum('kuantitas');
    }

    /**
     * Get duration of loan in days
     */
    public function getDurationDaysAttribute()
    {
        if ($this->tanggal_peminjaman && $this->tanggal_selesai) {
            return $this->tanggal_peminjaman->diffInDays($this->tanggal_selesai);
        }
        return 0;
    }

    /**
     * Scope for filtering by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for filtering by user
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('id_users', $userId);
    }

    /**
     * Scope for filtering by date range
     */
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('tanggal_peminjaman', [$startDate, $endDate]);
    }

    /**
     * Scope for overdue loans
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', 'dipinjam')
            ->where('tanggal_selesai', '<', now());
    }

    /**
     * Scope for active loans (dipinjam or jatuh tenggat)
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['dipinjam', 'jatuh tenggat']);
    }

    /**
     * Boot method untuk register observer
     */
    protected static function boot()
    {
        parent::boot();

        // Register observer jika belum terdaftar
        static::observe(\App\Observers\PeminjamanObserver::class);
    }
}
