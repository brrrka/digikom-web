<?php
// app/Imports/InventarisImport.php

namespace App\Imports;

use App\Models\Inventaris;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class InventarisImport implements
    ToModel,
    WithHeadingRow,
    WithValidation,
    SkipsOnError,
    SkipsOnFailure,
    WithBatchInserts,
    WithChunkReading
{
    use Importable, SkipsErrors, SkipsFailures;

    protected $importResults = [
        'success' => 0,
        'errors' => 0,
        'updated' => 0,
        'created' => 0,
        'skipped' => 0,
        'error_details' => []
    ];

    public function model(array $row)
    {
        try {
            // Clean and validate data - support both export format and import template format
            $nama = $this->cleanString(
                $row['nama_inventaris'] ??
                $row['nama inventaris'] ??  // Export format
                $row['nama'] ??
                ''
            );
            $kuantitas = $this->cleanNumber($row['kuantitas'] ?? 0);
            $status = $this->cleanStatus($row['status'] ?? 'tersedia');
            $deskripsi = $this->cleanString($row['deskripsi'] ?? '');

            // Skip if nama is empty
            if (empty($nama)) {
                $this->importResults['skipped']++;
                return null;
            }

            // Check if inventaris already exists
            $existingInventaris = Inventaris::where('nama', $nama)->first();

            if ($existingInventaris) {
                // Update existing - but keep total_dipinjam intact
                $existingInventaris->update([
                    'kuantitas' => $kuantitas,
                    'status' => $status,
                    'deskripsi' => $deskripsi,
                ]);

                // Recalculate to ensure consistency
                $existingInventaris->recalculateTotalDipinjam();

                $this->importResults['updated']++;
                return null; // Don't create new model
            } else {
                // Create new
                $this->importResults['created']++;
                return new Inventaris([
                    'nama' => $nama,
                    'kuantitas' => $kuantitas,
                    'status' => $status,
                    'deskripsi' => $deskripsi,
                    'total_dipinjam' => 0, // Default value
                ]);
            }
        } catch (\Exception $e) {
            $this->importResults['errors']++;
            $this->importResults['error_details'][] = "Row error: " . $e->getMessage();
            Log::error('Import error: ' . $e->getMessage(), ['row' => $row]);
            return null;
        }
    }

    public function rules(): array
    {
        return [
            'nama_inventaris' => ['required_without_all:nama,nama inventaris', 'nullable', 'string', 'max:255'],
            'nama inventaris' => ['required_without_all:nama,nama_inventaris', 'nullable', 'string', 'max:255'], // Export format
            'nama' => ['required_without_all:nama_inventaris,nama inventaris', 'nullable', 'string', 'max:255'],
            'kuantitas' => ['required', 'integer', 'min:0'],
            'status' => ['required', 'in:tersedia,tidak tersedia,Tersedia,Tidak Tersedia,available,unavailable'],
            'deskripsi' => ['nullable', 'string']
        ];
    }

    public function customValidationMessages()
    {
        return [
            'nama_inventaris.required' => 'Kolom nama inventaris wajib diisi.',
            'nama.required_without' => 'Kolom nama wajib diisi jika nama_inventaris kosong.',
            'kuantitas.required' => 'Kolom kuantitas wajib diisi.',
            'kuantitas.integer' => 'Kuantitas harus berupa angka.',
            'kuantitas.min' => 'Kuantitas tidak boleh kurang dari 0.',
            'status.required' => 'Kolom status wajib diisi.',
            'status.in' => 'Status harus tersedia atau tidak tersedia.',
        ];
    }

    public function batchSize(): int
    {
        return 100;
    }

    public function chunkSize(): int
    {
        return 100;
    }

    /**
     * Get import results
     */
    public function getImportResults(): array
    {
        $this->importResults['success'] = $this->importResults['created'] + $this->importResults['updated'];
        return $this->importResults;
    }

    /**
     * Clean string data
     */
    private function cleanString($value): string
    {
        return trim(strip_tags($value));
    }

    /**
     * Clean number data
     */
    private function cleanNumber($value): int
    {
        // Remove any non-numeric characters except decimal point
        $cleaned = preg_replace('/[^0-9.]/', '', $value);
        return (int) $cleaned;
    }

    /**
     * Clean status data
     */
    private function cleanStatus($value): string
    {
        $status = strtolower(trim($value));

        // Handle various status formats
        if (in_array($status, ['tersedia', 'available', '1', 'yes', 'ya'])) {
            return 'tersedia';
        } elseif (in_array($status, ['tidak tersedia', 'not available', 'unavailable', '0', 'no', 'tidak'])) {
            return 'tidak tersedia';
        }

        return 'tersedia'; // Default
    }

    /**
     * Handle validation errors
     */
    public function onError(\Throwable $e)
    {
        $this->importResults['errors']++;
        $this->importResults['error_details'][] = $e->getMessage();
    }

    /**
     * Handle validation failures
     */
    public function onFailure(\Maatwebsite\Excel\Validators\Failure ...$failures)
    {
        foreach ($failures as $failure) {
            $this->importResults['errors']++;
            $this->importResults['error_details'][] = "Row {$failure->row()}: " . implode(', ', $failure->errors());
        }
    }
}
