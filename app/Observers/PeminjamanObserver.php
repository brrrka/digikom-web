<?php

namespace App\Observers;

use App\Models\Peminjaman;
use App\Models\Inventaris;
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
        $oldStatus = $peminjaman->getOriginal('status');
        $newStatus = $peminjaman->status;

        // Jika status tidak berubah, tidak perlu melakukan apapun
        if ($oldStatus === $newStatus) {
            return;
        }

        Log::info("Status peminjaman berubah", [
            'peminjaman_id' => $peminjaman->id,
            'old_status' => $oldStatus,
            'new_status' => $newStatus
        ]);

        // Auto generate surat ketika status berubah ke 'disetujui'
        if ($newStatus === 'disetujui' && $oldStatus !== 'disetujui') {
            $this->handleLetterGeneration($peminjaman);
        }

        // Hapus bukti surat ketika status berubah ke 'dipinjam'
        if ($newStatus === 'dipinjam' && $oldStatus !== 'dipinjam') {
            $this->handleLetterDeletion($peminjaman);
        }

        // Update inventaris berdasarkan perubahan status
        $this->updateInventoryStatus($peminjaman, $oldStatus, $newStatus);
    }

    /**
     * Handle the Peminjaman "created" event.
     */
    public function created(Peminjaman $peminjaman): void
    {
        Log::info("Peminjaman baru dibuat", [
            'peminjaman_id' => $peminjaman->id,
            'status' => $peminjaman->status
        ]);

        // Jika peminjaman dibuat langsung dengan status 'disetujui' (dari admin)
        if ($peminjaman->status === 'disetujui') {
            // Generate surat
            $this->handleLetterGeneration($peminjaman);

            // Update inventaris untuk peminjaman yang langsung disetujui
            $this->handleApproved($peminjaman);
        }
    }

    /**
     * Handle letter generation with optimization and error handling
     */
    private function handleLetterGeneration(Peminjaman $peminjaman)
    {
        try {
            // Check if generation is already in progress
            if ($this->letterService->isGenerationInProgress($peminjaman->id)) {
                Log::info("Letter generation already in progress for peminjaman ID: {$peminjaman->id}");
                return;
            }

            // Set generation lock
            $this->letterService->setGenerationLock($peminjaman->id);

            // Generate letter with optimized service
            $result = $this->letterService->generateLoanLetter($peminjaman);

            if ($result['success']) {
                Log::info("Surat peminjaman berhasil di-generate untuk ID: {$peminjaman->id}", [
                    'path' => $result['path'],
                    'message' => $result['message'] ?? ''
                ]);
            } else {
                Log::error("Gagal generate surat peminjaman untuk ID: {$peminjaman->id}", $result);
            }
        } catch (\Exception $e) {
            Log::error("Error generating loan letter for peminjaman ID {$peminjaman->id}: " . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
        } finally {
            // Always release the lock
            $this->letterService->releaseGenerationLock($peminjaman->id);
        }
    }

    /**
     * Handle letter deletion when status changes to 'dipinjam'
     */
    private function handleLetterDeletion(Peminjaman $peminjaman)
    {
        if ($peminjaman->bukti_path) {
            try {
                Storage::delete('public/' . $peminjaman->bukti_path);
                $peminjaman->bukti_path = null;
                $peminjaman->saveQuietly(); // Gunakan saveQuietly untuk menghindari trigger observer lagi

                Log::info("Bukti surat dihapus untuk peminjaman ID: {$peminjaman->id}");
            } catch (\Exception $e) {
                Log::error("Error deleting bukti for peminjaman ID {$peminjaman->id}: " . $e->getMessage());
            }
        }
    }

    /**
     * PERBAIKAN: Update inventory status based on peminjaman status changes
     */
    private function updateInventoryStatus(Peminjaman $peminjaman, $oldStatus, $newStatus)
    {
        try {
            DB::beginTransaction();

            // Load relations jika belum dimuat
            if (!$peminjaman->relationLoaded('detailPeminjaman')) {
                $peminjaman->load('detailPeminjaman.inventaris');
            }

            // Logika pembaruan inventaris berdasarkan transisi status
            switch ($newStatus) {
                case 'disetujui':
                    if ($oldStatus === 'diajukan') {
                        $this->handleApproved($peminjaman);
                    }
                    break;

                case 'dikembalikan':
                    if (in_array($oldStatus, ['dipinjam', 'disetujui', 'jatuh tenggat'])) {
                        $this->handleReturned($peminjaman);
                    }
                    break;

                case 'ditolak':
                    if ($oldStatus === 'disetujui') {
                        // Jika ditolak setelah disetujui, kembalikan stok
                        $this->handleReturned($peminjaman);
                    }
                    // Jika ditolak dari diajukan, tidak perlu update stok
                    break;

                case 'dipinjam':
                case 'jatuh tenggat':
                    // Status ini tidak mengubah stok inventaris
                    break;
            }

            DB::commit();

            Log::info("Inventory updated successfully for peminjaman ID: {$peminjaman->id}", [
                'old_status' => $oldStatus,
                'new_status' => $newStatus
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating inventory on peminjaman status change: ' . $e->getMessage(), [
                'peminjaman_id' => $peminjaman->id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'trace' => $e->getTraceAsString()
            ]);

            // Re-throw exception agar bisa ditangani di level yang lebih tinggi
            throw $e;
        }
    }

    /**
     * PERBAIKAN: Handle inventory when peminjaman is approved
     */
    private function handleApproved(Peminjaman $peminjaman)
    {
        foreach ($peminjaman->detailPeminjaman as $detail) {
            $inventaris = $detail->inventaris;

            // Refresh data inventaris untuk memastikan data terbaru
            $inventaris->refresh();

            // Recalculate total_dipinjam untuk memastikan akurasi
            $inventaris->recalculateTotalDipinjam();

            // Validasi ketersediaan barang
            $tersedia = $inventaris->kuantitas - ($inventaris->total_dipinjam ?? 0);

            if ($tersedia < $detail->kuantitas) {
                throw new \Exception("Barang '{$inventaris->nama}' tidak tersedia dalam jumlah yang cukup. Tersedia: {$tersedia}, Diminta: {$detail->kuantitas}");
            }

            // PERBAIKAN: Gunakan method atomic untuk update
            $inventaris->incrementTotalDipinjam($detail->kuantitas);

            Log::debug("Inventory updated for approved loan", [
                'inventaris_id' => $inventaris->id,
                'nama' => $inventaris->nama,
                'quantity_borrowed' => $detail->kuantitas,
                'total_borrowed' => $inventaris->fresh()->total_dipinjam,
                'status' => $inventaris->fresh()->status
            ]);
        }
    }

    /**
     * PERBAIKAN: Handle inventory when peminjaman is returned or rejected
     */
    private function handleReturned(Peminjaman $peminjaman)
    {
        foreach ($peminjaman->detailPeminjaman as $detail) {
            $inventaris = $detail->inventaris;

            // Refresh data inventaris untuk memastikan data terbaru
            $inventaris->refresh();

            // Recalculate total_dipinjam untuk memastikan akurasi
            $inventaris->recalculateTotalDipinjam();

            // PERBAIKAN: Gunakan method atomic untuk update
            $inventaris->decrementTotalDipinjam($detail->kuantitas);

            Log::debug("Inventory updated for returned loan", [
                'inventaris_id' => $inventaris->id,
                'nama' => $inventaris->nama,
                'quantity_returned' => $detail->kuantitas,
                'total_borrowed' => $inventaris->fresh()->total_dipinjam,
                'status' => $inventaris->fresh()->status
            ]);
        }
    }
}
