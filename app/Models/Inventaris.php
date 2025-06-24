<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Inventaris extends Model
{
    use HasFactory;

    protected $table = 'inventaris';

    protected $fillable = [
        'nama',
        'kuantitas',
        'status',
        'deskripsi',
        'images',
        'total_dipinjam'
    ];

    protected $casts = [
        'kuantitas' => 'integer',
        'total_dipinjam' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // PERBAIKAN: Jangan tambahkan 'tersedia' ke appends karena ini bukan kolom database
    // protected $appends = ['tersedia']; // JANGAN gunakan ini

    // Relasi dengan DetailPeminjaman
    public function detailPeminjaman()
    {
        return $this->hasMany(DetailPeminjaman::class, 'id_inventaris');
    }

    // Relasi dengan Peminjaman melalui DetailPeminjaman
    public function peminjaman()
    {
        return $this->belongsToMany(Peminjaman::class, 'detail_peminjaman', 'id_inventaris', 'id_peminjaman')
            ->withPivot('kuantitas');
    }

    // Accessor untuk kuantitas tersedia - JANGAN update database dengan nilai ini
    public function getTersediaAttribute()
    {
        return max(0, $this->kuantitas - ($this->total_dipinjam ?? 0));
    }

    // Accessor untuk mendapatkan URL gambar
    public function getImageUrlAttribute()
    {
        if ($this->images) {
            return \Storage::url($this->images);
        }
        return null;
    }

    // Scope untuk filter berdasarkan status
    public function scopeStatus($query, $status)
    {
        if ($status) {
            return $query->where('status', $status);
        }
        return $query;
    }

    // Scope untuk pencarian
    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                    ->orWhere('deskripsi', 'like', '%' . $search . '%');
            });
        }
        return $query;
    }

    // Scope untuk inventaris yang tersedia untuk dipinjam
    public function scopeAvailableForLoan($query, $minQuantity = 1)
    {
        return $query->where('status', 'tersedia')
            ->whereRaw('(kuantitas - COALESCE(total_dipinjam, 0)) >= ?', [$minQuantity]);
    }

    // Method untuk mengecek apakah inventaris bisa dihapus
    public function canBeDeleted()
    {
        return !$this->detailPeminjaman()
            ->whereHas('peminjaman', function ($query) {
                $query->whereIn('status', ['disetujui', 'dipinjam', 'jatuh tenggat']);
            })
            ->exists();
    }

    // Method untuk mengecek apakah kuantitas bisa dikurangi
    public function canReduceQuantityTo($newQuantity)
    {
        $this->recalculateTotalDipinjam();
        return $newQuantity >= ($this->total_dipinjam ?? 0);
    }

    // Method untuk recalculate total dipinjam berdasarkan data aktual
    public function recalculateTotalDipinjam()
    {
        $total = $this->detailPeminjaman()
            ->whereHas('peminjaman', function ($query) {
                $query->whereIn('status', ['disetujui', 'dipinjam', 'jatuh tenggat']);
            })
            ->sum('kuantitas');

        // Update total_dipinjam hanya jika berbeda
        if ($this->total_dipinjam !== $total) {
            $this->total_dipinjam = max(0, $total);
            $this->saveQuietly();
        }

        return $this;
    }

    // Method untuk update status berdasarkan ketersediaan
    public function updateStatusBasedOnAvailability()
    {
        $available = $this->kuantitas - ($this->total_dipinjam ?? 0);

        if ($available <= 0 && $this->status === 'tersedia') {
            $this->updateQuietly(['status' => 'tidak tersedia']);
        } elseif ($available > 0 && $this->status === 'tidak tersedia') {
            $this->updateQuietly(['status' => 'tersedia']);
        }

        return $this;
    }

    // Method untuk validasi sebelum peminjaman
    public function validateForLoan($requestedQuantity)
    {
        $this->recalculateTotalDipinjam();
        $available = $this->kuantitas - ($this->total_dipinjam ?? 0);

        if ($this->status !== 'tersedia') {
            throw new \Exception("Inventaris '{$this->nama}' sedang tidak tersedia.");
        }

        if ($available < $requestedQuantity) {
            throw new \Exception("Stok '{$this->nama}' tidak mencukupi. Tersedia: {$available}, diminta: {$requestedQuantity}");
        }

        return true;
    }

    // Method untuk increment total_dipinjam secara atomic
    public function incrementTotalDipinjam($quantity)
    {
        DB::transaction(function () use ($quantity) {
            $this->lockForUpdate();
            $this->recalculateTotalDipinjam();

            $newTotal = ($this->total_dipinjam ?? 0) + $quantity;

            if ($newTotal > $this->kuantitas) {
                throw new \Exception("Tidak dapat meminjam {$quantity} unit. Stok tersedia: " . ($this->kuantitas - ($this->total_dipinjam ?? 0)));
            }

            $this->total_dipinjam = $newTotal;
            $this->saveQuietly();
            $this->updateStatusBasedOnAvailability();
        });

        return $this;
    }

    // Method untuk decrement total_dipinjam secara atomic
    public function decrementTotalDipinjam($quantity)
    {
        DB::transaction(function () use ($quantity) {
            $this->lockForUpdate();
            $this->recalculateTotalDipinjam();

            $newTotal = max(0, ($this->total_dipinjam ?? 0) - $quantity);
            $this->total_dipinjam = $newTotal;
            $this->saveQuietly();
            $this->updateStatusBasedOnAvailability();
        });

        return $this;
    }

    // Method untuk mendapatkan peminjaman aktif
    public function getActiveLoanAttribute()
    {
        return $this->detailPeminjaman()
            ->whereHas('peminjaman', function ($query) {
                $query->whereIn('status', ['disetujui', 'dipinjam', 'jatuh tenggat']);
            })
            ->with(['peminjaman.user'])
            ->get();
    }

    // Method untuk mendapatkan riwayat peminjaman
    public function getLoanHistoryAttribute()
    {
        return $this->detailPeminjaman()
            ->with(['peminjaman.user'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    // PERBAIKAN: Method untuk mendapatkan stok tersedia real-time
    public static function getStockInfo($inventarisId)
    {
        $inventaris = self::find($inventarisId);
        if (!$inventaris) {
            return null;
        }

        $inventaris->recalculateTotalDipinjam();
        $tersedia = $inventaris->kuantitas - ($inventaris->total_dipinjam ?? 0);

        return [
            'id' => $inventaris->id,
            'nama' => $inventaris->nama,
            'kuantitas' => $inventaris->kuantitas,
            'total_dipinjam' => $inventaris->total_dipinjam ?? 0,
            'tersedia' => max(0, $tersedia),
            'status' => $inventaris->status,
            'is_available' => $inventaris->status === 'tersedia' && $tersedia > 0
        ];
    }

    // Boot method yang lebih aman
    protected static function boot()
    {
        parent::boot();

        static::created(function ($inventaris) {
            if ($inventaris->total_dipinjam === null) {
                $inventaris->total_dipinjam = 0;
                $inventaris->saveQuietly();
            }
        });

        static::updating(function ($inventaris) {
            if ($inventaris->isDirty('kuantitas')) {
                $inventaris->recalculateTotalDipinjam();

                if ($inventaris->kuantitas < ($inventaris->total_dipinjam ?? 0)) {
                    throw new \Exception("Kuantitas tidak boleh kurang dari total yang sedang dipinjam (" . ($inventaris->total_dipinjam ?? 0) . ").");
                }
            }
        });

        static::updated(function ($inventaris) {
            if ($inventaris->isDirty(['kuantitas', 'total_dipinjam'])) {
                $inventaris->updateStatusBasedOnAvailability();
            }
        });

        static::deleting(function ($inventaris) {
            if (!$inventaris->canBeDeleted()) {
                throw new \Exception("Tidak dapat menghapus inventaris yang memiliki peminjaman aktif.");
            }
        });
    }
}
