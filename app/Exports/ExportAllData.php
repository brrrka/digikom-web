<?php

namespace App\Exports;

use App\Models\Inventaris;
use App\Models\Peminjaman;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ExportAllData implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            new ExportInventaris(),
            new ExportPeminjaman(),
        ];
    }
}
