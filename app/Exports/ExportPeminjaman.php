<?php

namespace App\Exports;

use App\Models\Peminjaman;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExportPeminjaman implements FromCollection, WithHeadings, WithTitle, WithStyles
{
    public function collection()
    {
        return Peminjaman::with(['user', 'detailPeminjaman.inventaris'])->get()->map(function ($peminjaman) {
            // Format detail peminjaman dengan baris baru
            $detailPeminjaman = $peminjaman->detailPeminjaman->map(function ($detail) {
                return $detail->inventaris->nama . ' (' . $detail->kuantitas . ')';
            })->implode("\n"); // Gunakan "\n" untuk baris baru

            return [
                'ID' => $peminjaman->id,
                'Nama Peminjam' => $peminjaman->user->name,
                'NIM' => $peminjaman->user->nim,
                'Tanggal Peminjaman' => $peminjaman->tanggal_peminjaman,
                'Tanggal Selesai' => $peminjaman->tanggal_selesai,
                'Tanggal Pengembalian' => $peminjaman->tanggal_pengembalian,
                'Alasan' => $peminjaman->alasan,
                'Status' => $peminjaman->status,
                'Detail Peminjaman' => $detailPeminjaman, // Detail dengan baris baru
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Peminjam',
            'NIM',
            'Tanggal Peminjaman',
            'Tanggal Selesai',
            'Tanggal Pengembalian',
            'Alasan',
            'Status',
            'Detail Peminjaman',
        ];
    }

    public function title(): string
    {
        return 'Peminjaman';
    }

    // Mengatur style untuk cell
    public function styles(Worksheet $sheet)
    {
        return [
            // Mengatur wrap text untuk kolom "Detail Peminjaman" (kolom I)
            'I' => [
                'alignment' => [
                    'wrapText' => true, // Aktifkan wrap text
                ],
            ],
        ];
    }
}
