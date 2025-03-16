<?php

namespace App\Exports;

use App\Models\Inventaris;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class ExportInventaris implements FromCollection, WithHeadings, WithTitle
{
    public function collection()
    {
        return Inventaris::all();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama',
            'Deskripsi',
            'Kuantitas',
            'Total Dipinjam',
            'Images',
            'Status',
            'Created At',
            'Updated At',
        ];
    }
    public function title(): string
    {
        return 'Inventaris';
    }
}
