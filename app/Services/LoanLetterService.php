<?php

namespace App\Services;

use App\Models\DetailPeminjaman;
use App\Models\Peminjaman;
use App\Models\User;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Exception;

class LoanLetterService
{
    public function generateLoanLetter(Peminjaman $peminjaman)
    {
        try {
            // Pilih template berdasarkan durasi peminjaman
            $templatePath = $peminjaman->jangka === 'pendek'
                ? public_path('templates/SURAT_PEMINJAMAN_ALAT_DIGIKOM_JANGKA_PENDEK.docx')
                : public_path('templates/SURAT_PEMINJAMAN_ALAT_DIGIKOM_JANGKA_PANJANG.docx');

            // Periksa apakah template ada
            if (!file_exists($templatePath)) {
                throw new Exception("Template file tidak ditemukan di path: {$templatePath}");
            }

            // Buat template processor
            $templateProcessor = new TemplateProcessor($templatePath);

            // Ambil data user
            $user = User::find($peminjaman->id_users);
            if (!$user) {
                throw new Exception("User dengan ID {$peminjaman->id_users} tidak ditemukan");
            }

            // Basic replacements
            $templateProcessor->setValue('tanggal_surat', \Carbon\Carbon::now()->isoFormat('DD MMMM YYYY'));
            $templateProcessor->setValue('nama_peminjam', $user->name);
            $templateProcessor->setValue('nim_peminjam', $user->nim ?? '-');
            $templateProcessor->setValue('no_hp_peminjam', $user->no_telp ?? '-');
            $templateProcessor->setValue('alasan_peminjaman', $peminjaman->alasan);

            // Ambil semua detail peminjaman dengan inventaris
            $detailPeminjaman = DetailPeminjaman::where('id_peminjaman', $peminjaman->id)
                ->with('inventaris')
                ->get();

            // Periksa apakah ada detail peminjaman
            if ($detailPeminjaman->isEmpty()) {
                throw new Exception("Tidak ada detail peminjaman untuk peminjaman ID: {$peminjaman->id}");
            }

            // Persiapkan data tabel - sekarang dengan banyak item
            $items = [];
            foreach ($detailPeminjaman as $index => $detail) {
                if (!$detail->inventaris) {
                    throw new Exception("Inventaris tidak ditemukan untuk detail peminjaman ID: {$detail->id}");
                }

                $items[] = [
                    'nomor_barang' => $index + 1,
                    'nama_barang' => $detail->inventaris->nama,
                    'jumlah_barang' => $detail->kuantitas,
                    'tanggal_peminjaman' => \Carbon\Carbon::parse($peminjaman->tanggal_peminjaman)->format('d/m/Y'),
                    'tanggal_selesai' => \Carbon\Carbon::parse($peminjaman->tanggal_selesai)->format('d/m/Y'),
                ];
            }

            // Clone row dan set values
            $templateProcessor->cloneRowAndSetValues('nomor_barang', $items);

            // Buat direktori jika belum ada
            Storage::makeDirectory('public/loan_letters');

            // Simpan dokumen
            $outputPath = storage_path('app/public/loan_letters/surat_peminjaman_P00' . $peminjaman->id . '.docx');
            $templateProcessor->saveAs($outputPath);

            // Periksa apakah file berhasil dibuat
            if (!file_exists($outputPath)) {
                throw new Exception("Gagal menyimpan file surat di: {$outputPath}");
            }

            // Update peminjaman dengan path dokumen
            $peminjaman->bukti_path = 'loan_letters/surat_peminjaman_P00' . $peminjaman->id . '.docx';
            $peminjaman->save();

            return [
                'success' => true,
                'path' => $peminjaman->bukti_path
            ];
        } catch (Exception $e) {
            // Log error untuk debugging
            Log::error('Gagal membuat surat peminjaman: ' . $e->getMessage(), [
                'peminjaman_id' => $peminjaman->id,
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
                'detail' => config('app.debug') ? [
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ] : null
            ];
        }
    }
}
