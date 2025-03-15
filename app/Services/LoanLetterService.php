<?php

namespace App\Services;

use App\Models\Peminjaman;
use App\Models\Inventaris;
use App\Models\User;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\Storage;

class LoanLetterService
{
    public function generateLoanLetter(Peminjaman $peminjaman)
    {
        // Choose template based on loan duration
        $templatePath = $peminjaman->jangka === 'pendek'
            ? public_path('templates/SURAT_PEMINJAMAN_ALAT_DIGIKOM_JANGKA_PENDEK.docx')
            : public_path('templates/SURAT_PEMINJAMAN_ALAT_DIGIKOM_JANGKA_PANJANG.docx');

        // Create template processor
        $templateProcessor = new TemplateProcessor($templatePath);

        // Get user data
        $user = User::find($peminjaman->id_users);

        // Basic replacements
        $templateProcessor->setValue('tanggal_surat', \Carbon\Carbon::now()->isoFormat('DD MMMM YYYY'));
        $templateProcessor->setValue('nama_peminjam', $user->name);
        $templateProcessor->setValue('nim_peminjam', $user->nim);
        $templateProcessor->setValue('no_hp_peminjam', $user->no_telp ?? '-');
        $templateProcessor->setValue('alasan_peminjaman', $peminjaman->alasan);

        // Get inventory item
        $inventaris = Inventaris::find($peminjaman->id_inventaris);

        // Prepare table data
        $items = [
            [
                'nomor_barang' => 1,
                'nama_barang' => $inventaris->nama,
                'jumlah_barang' => $peminjaman->kuantitas,
                'tanggal_peminjaman' => \Carbon\Carbon::parse($peminjaman->tanggal_peminjaman)->format('d/m/Y'),
                'tanggal_selesai' => \Carbon\Carbon::parse($peminjaman->tanggal_selesai)->format('d/m/Y'),
            ]
        ];

        // Clone row and set values
        $templateProcessor->cloneRowAndSetValues('nomor_barang', $items);

        // Create directory if it doesn't exist
        Storage::makeDirectory('public/loan_letters');

        // Save the document
        $outputPath = storage_path('app/public/loan_letters/surat_peminjaman_P00' . $peminjaman->id . '.docx');
        $templateProcessor->saveAs($outputPath);

        // Update the peminjaman with the document path
        $peminjaman->bukti_path = 'loan_letters/surat_peminjaman_P00' . $peminjaman->id . '.docx';
        $peminjaman->save();

        return $peminjaman->bukti_path;
    }
}
