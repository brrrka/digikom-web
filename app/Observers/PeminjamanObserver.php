<?php

namespace App\Observers;

use App\Jobs\GenerateLoanLetter;
use App\Models\Peminjaman;
use App\Services\LoanLetterService;
use Illuminate\Support\Facades\Storage;

class PeminjamanObserver
{
    protected $letterService;

    public function __construct(LoanLetterService $letterService)
    {
        $this->letterService = $letterService;
    }

    /**
     * Handle the Peminjaman "updated" event.
     */
    public function updated(Peminjaman $peminjaman): void
    {
        if (
            $peminjaman->status === 'disetujui' &&
            $peminjaman->getOriginal('status') !== 'disetujui'
        ) {
            GenerateLoanLetter::dispatch($peminjaman);
        }

        if (
            $peminjaman->status === 'dipinjam' &&
            $peminjaman->getOriginal('status') !== 'dipinjam'
        ) {
            if ($peminjaman->bukti_path) {
                Storage::delete('public/' . $peminjaman->bukti_path);
                $peminjaman->bukti_path = null;
                $peminjaman->save();
            }
        }
    }
}
