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
            $templatePath = $peminjaman->jangka === 'pendek'
                ? public_path('templates/SURAT_PEMINJAMAN_ALAT_DIGIKOM_JANGKA_PENDEK.docx')
                : public_path('templates/SURAT_PEMINJAMAN_ALAT_DIGIKOM_JANGKA_PANJANG.docx');

            if (!file_exists($templatePath)) {
                throw new Exception("Template file tidak ditemukan di path: {$templatePath}");
            }

            $templateProcessor = new TemplateProcessor($templatePath);

            $user = User::find($peminjaman->id_users);
            if (!$user) {
                throw new Exception("User dengan ID {$peminjaman->id_users} tidak ditemukan");
            }

            \Carbon\Carbon::setLocale('id');
            $templateProcessor->setValue('tanggal_surat', \Carbon\Carbon::now()->translatedFormat('d F Y'));
            $templateProcessor->setValue('nama_peminjam', $user->name);
            $templateProcessor->setValue('nim_peminjam', $user->nim ?? '-');
            $templateProcessor->setValue('no_hp_peminjam', $user->no_telp ?? '-');
            $templateProcessor->setValue('alasan_peminjaman', $peminjaman->alasan);

            $detailPeminjaman = DetailPeminjaman::where('id_peminjaman', $peminjaman->id)
                ->with('inventaris')
                ->get();

            if ($detailPeminjaman->isEmpty()) {
                throw new Exception("Tidak ada detail peminjaman untuk peminjaman ID: {$peminjaman->id}");
            }

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

            $templateProcessor->cloneRowAndSetValues('nomor_barang', $items);

            Storage::makeDirectory('public/loan_letters');

            $outputPath = storage_path('app/public/loan_letters/surat_peminjaman_PD-' . $peminjaman->id . '.docx');
            $templateProcessor->saveAs($outputPath);

            if (!file_exists($outputPath)) {
                throw new Exception("Gagal menyimpan file surat di: {$outputPath}");
            }

            $peminjaman->bukti_path = 'loan_letters/surat_peminjaman_PD-' . $peminjaman->id . '.docx';
            $peminjaman->save();

            return [
                'success' => true,
                'path' => $peminjaman->bukti_path
            ];
        } catch (Exception $e) {
            Log::error('Gagal membuat surat peminjaman: ' . $e->getMessage(), [
                'peminjaman_id' => $peminjaman->id,
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            // Fallback: Simpan template mentah
            return $this->saveRawTemplate($peminjaman, $templatePath);
        }
    }

    /**
     * Simpan template mentah sebagai fallback
     */
    private function saveRawTemplate(Peminjaman $peminjaman, $templatePath)
    {
        try {
            Storage::makeDirectory('public/loan_letters');

            $rawTemplatePath = storage_path('app/public/loan_letters/surat_peminjaman_PD-' . $peminjaman->id . '_RAW.docx');
            copy($templatePath, $rawTemplatePath);

            if (!file_exists($rawTemplatePath)) {
                throw new Exception("Gagal menyimpan template mentah di: {$rawTemplatePath}");
            }

            $peminjaman->bukti_path = 'loan_letters/surat_peminjaman_PD-' . $peminjaman->id . '_RAW.docx';
            $peminjaman->save();

            return [
                'success' => true,
                'path' => $peminjaman->bukti_path,
                'message' => 'Template mentah berhasil disimpan karena terjadi kesalahan saat generate surat.'
            ];
        } catch (Exception $e) {
            Log::error('Gagal menyimpan template mentah: ' . $e->getMessage(), [
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
