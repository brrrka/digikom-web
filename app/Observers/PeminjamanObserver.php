<?php

namespace App\Observers;

use App\Jobs\GenerateLoanLetter;
use App\Models\Peminjaman;
use App\Services\LoanLetterService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
        // Simpan status lama dan baru untuk penggunaan selanjutnya
        $oldStatus = $peminjaman->getOriginal('status');
        $newStatus = $peminjaman->status;

        // Jika status tidak berubah, tidak perlu melakukan apapun
        if ($oldStatus === $newStatus) {
            return;
        }

        // Logika yang sudah ada untuk generate surat dan menghapus bukti
        if ($newStatus === 'disetujui' && $oldStatus !== 'disetujui') {
            GenerateLoanLetter::dispatch($peminjaman);
        }

        if ($newStatus === 'dipinjam' && $oldStatus !== 'dipinjam') {
            if ($peminjaman->bukti_path) {
                Storage::delete('public/' . $peminjaman->bukti_path);
                $peminjaman->bukti_path = null;
                $peminjaman->save();
            }
        }

        // Logika baru untuk memperbarui inventaris
        try {
            DB::beginTransaction();

            // Load relations jika belum dimuat
            if (!$peminjaman->relationLoaded('detailPeminjaman')) {
                $peminjaman->load('detailPeminjaman.inventaris');
            }

            // Logika pembaruan inventaris berdasarkan transisi status
            switch ($newStatus) {
                case 'disetujui':
                    // Hanya jika status sebelumnya adalah 'diajukan'
                    if ($oldStatus === 'diajukan') {
                        $this->handleApproved($peminjaman);
                    }
                    break;

                case 'dipinjam':
                    // Transisi dari 'disetujui' ke 'dipinjam' tidak perlu mengubah inventaris
                    // karena barang sudah dianggap terpinjam pada tahap 'disetujui'
                    break;

                case 'dikembalikan':
                    // Kembalikan barang ke inventaris jika status sebelumnya adalah 'dipinjam', 'disetujui', atau 'jatuh tenggat'
                    if (in_array($oldStatus, ['dipinjam', 'disetujui', 'jatuh tenggat'])) {
                        $this->handleReturned($peminjaman);
                    }
                    break;

                case 'jatuh tenggat':
                    // Tidak perlu mengubah inventaris, karena barang masih dianggap terpinjam
                    break;

                case 'ditolak':
                    // Jika peminjaman ditolak dan sebelumnya sudah 'disetujui',
                    // kembalikan barang ke inventaris
                    if ($oldStatus === 'disetujui') {
                        $this->handleReturned($peminjaman);
                    }
                    break;
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating inventory on peminjaman status change: ' . $e->getMessage());
        }
    }

    /**
     * Menangani pembaruan inventaris saat peminjaman disetujui
     */
    private function handleApproved(Peminjaman $peminjaman)
    {
        foreach ($peminjaman->detailPeminjaman as $detail) {
            $inventaris = $detail->inventaris;

            // Validasi ketersediaan barang
            $tersedia = $inventaris->kuantitas - $inventaris->total_dipinjam;
            if ($tersedia < $detail->kuantitas) {
                throw new \Exception("Barang '{$inventaris->nama}' tidak tersedia dalam jumlah yang cukup.");
            }

            // Update nilai total_dipinjam
            $inventaris->total_dipinjam += $detail->kuantitas;

            // Update status inventaris jika semua barang terpinjam
            if ($inventaris->kuantitas <= $inventaris->total_dipinjam) {
                $inventaris->status = 'tidak tersedia';
            }

            $inventaris->save();
        }
    }

    /**
     * Menangani pembaruan inventaris saat peminjaman dikembalikan atau ditolak
     */
    private function handleReturned(Peminjaman $peminjaman)
    {
        foreach ($peminjaman->detailPeminjaman as $detail) {
            $inventaris = $detail->inventaris;

            // Kurangi total_dipinjam
            $inventaris->total_dipinjam -= $detail->kuantitas;

            // Pastikan total_dipinjam tidak negatif
            if ($inventaris->total_dipinjam < 0) {
                $inventaris->total_dipinjam = 0;
            }

            // Update status inventaris kembali menjadi tersedia
            if ($inventaris->total_dipinjam < $inventaris->kuantitas) {
                $inventaris->status = 'tersedia';
            }

            $inventaris->save();
        }
    }
}
