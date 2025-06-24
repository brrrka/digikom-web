<?php
// app/Exports/InventarisExport.php

namespace App\Exports;

use App\Models\Inventaris;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Illuminate\Support\Facades\Log;

class InventarisExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithColumnWidths,
    WithStyles,
    ShouldAutoSize,
    WithTitle
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        try {
            $query = Inventaris::query();

            // Handle selected IDs export
            if (!empty($this->filters['selected_ids'])) {
                $query->whereIn('id', $this->filters['selected_ids']);
            } else {
                // Apply other filters
                if (!empty($this->filters['search'])) {
                    $search = $this->filters['search']; // PERBAIKAN: Extract variable dulu
                    $query->where(function ($q) use ($search) {
                        $q->where('nama', 'like', '%' . $search . '%')
                            ->orWhere('deskripsi', 'like', '%' . $search . '%');
                    });
                }

                if (!empty($this->filters['status'])) {
                    $query->where('status', $this->filters['status']);
                }
            }

            $inventaris = $query->orderBy('nama')->get();

            Log::info('Export inventaris', [
                'count' => $inventaris->count(),
                'filters' => $this->filters
            ]);

            return $inventaris;
        } catch (\Exception $e) {
            Log::error('Export error in collection method', [
                'error' => $e->getMessage(),
                'filters' => $this->filters
            ]);

            // Return empty collection if error
            return collect([]);
        }
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Nama Inventaris',
            'Kuantitas',
            'Status',
            'Deskripsi',
            'Total Dipinjam',
            'Tersedia',
            'Tanggal Dibuat',
            'Terakhir Diupdate'
        ];
    }

    /**
     * @param mixed $inventaris
     * @return array
     */
    public function map($inventaris): array
    {
        try {
            // Safely calculate available quantity
            $totalDipinjam = $inventaris->total_dipinjam ?? 0;
            $tersedia = max(0, $inventaris->kuantitas - $totalDipinjam);

            return [
                $inventaris->id ?? '',
                $inventaris->nama ?? '',
                $inventaris->kuantitas ?? 0,
                ucfirst($inventaris->status ?? ''),
                $inventaris->deskripsi ?: '-',
                $totalDipinjam,
                $tersedia,
                $inventaris->created_at ? $inventaris->created_at->format('d/m/Y H:i') : '-',
                $inventaris->updated_at ? $inventaris->updated_at->format('d/m/Y H:i') : '-'
            ];
        } catch (\Exception $e) {
            Log::error('Export mapping error', [
                'inventaris_id' => $inventaris->id ?? 'unknown',
                'error' => $e->getMessage()
            ]);

            // Return safe default values
            return [
                $inventaris->id ?? '',
                $inventaris->nama ?? 'Error',
                0,
                'Error',
                'Error saat export',
                0,
                0,
                '-',
                '-'
            ];
        }
    }

    /**
     * @return array
     */
    public function columnWidths(): array
    {
        return [
            'A' => 8,   // ID
            'B' => 25,  // Nama
            'C' => 12,  // Kuantitas
            'D' => 15,  // Status
            'E' => 30,  // Deskripsi
            'F' => 15,  // Total Dipinjam
            'G' => 12,  // Tersedia
            'H' => 18,  // Created
            'I' => 18,  // Updated
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        try {
            $lastRow = $sheet->getHighestRow();

            return [
                // Header style
                1 => [
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'],
                        'size' => 12
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '4F46E5']
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER
                    ]
                ],

                // All cells border
                'A1:I' . $lastRow => [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'CCCCCC']
                        ]
                    ]
                ],

                // Data rows alignment
                'A2:I' . $lastRow => [
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_CENTER
                    ]
                ],

                // Number columns center alignment
                'A2:A' . $lastRow => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]],
                'C2:C' . $lastRow => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]],
                'D2:D' . $lastRow => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]],
                'F2:F' . $lastRow => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]],
                'G2:G' . $lastRow => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]],
            ];
        } catch (\Exception $e) {
            Log::error('Export styling error', ['error' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Data Inventaris';
    }
}
