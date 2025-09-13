<?php
// app/Http/Controllers/Admin/InventarisController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventaris;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\InventarisExport;
use App\Imports\InventarisImport;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class InventarisController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Inventaris::query();

            // Search functionality
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('nama', 'like', '%' . $search . '%')
                        ->orWhere('deskripsi', 'like', '%' . $search . '%');
                });
            }

            // Filter by status
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            $inventaris = $query->latest()->paginate(12);

            // Stats for dashboard cards dengan perhitungan yang benar
            $stats = [
                'total' => Inventaris::count(),
                'tersedia' => Inventaris::where('status', 'tersedia')->count(),
                'tidak_tersedia' => Inventaris::where('status', 'tidak tersedia')->count(),
                'total_kuantitas' => Inventaris::sum('kuantitas'),
                'total_dipinjam' => Inventaris::sum('total_dipinjam'),
            ];

            // Hitung total tersedia dengan query terpisah
            $stats['total_tersedia'] = Inventaris::selectRaw('SUM(kuantitas - COALESCE(total_dipinjam, 0)) as total')
                ->first()->total ?? 0;

            return view('admin.inventaris.index', compact('inventaris', 'stats'));
        } catch (\Exception $e) {
            Log::error('Inventaris index error', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memuat data inventaris.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.inventaris.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nama' => 'required|string|max:255|unique:inventaris,nama',
                'kuantitas' => 'required|integer|min:0',
                'deskripsi' => 'nullable|string|max:1000',
                'images' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'status' => 'required|in:tersedia,tidak tersedia',
            ], [
                'nama.required' => 'Nama inventaris wajib diisi.',
                'nama.unique' => 'Nama inventaris sudah ada.',
                'kuantitas.required' => 'Kuantitas wajib diisi.',
                'kuantitas.integer' => 'Kuantitas harus berupa angka.',
                'kuantitas.min' => 'Kuantitas tidak boleh kurang dari 0.',
                'images.image' => 'File harus berupa gambar.',
                'images.max' => 'Ukuran gambar maksimal 2MB.',
                'status.required' => 'Status wajib dipilih.',
            ]);

            // Handle image upload
            if ($request->hasFile('images')) {
                $validated['images'] = $request->file('images')->store('inventaris', 'public');
            }

            // Set default total_dipinjam
            $validated['total_dipinjam'] = 0;

            $inventaris = Inventaris::create($validated);

            return redirect()->route('admin.inventaris.index')
                ->with('success', 'Inventaris "' . $inventaris->nama . '" berhasil ditambahkan!');
        } catch (\Exception $e) {
            Log::error('Inventaris store error', ['error' => $e->getMessage()]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan inventaris: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $inventaris = Inventaris::with(['detailPeminjaman.peminjaman.user'])
                ->findOrFail($id);

            // Hitung statistik untuk inventaris ini
            $activeLoanCount = $inventaris->detailPeminjaman()
                ->whereHas('peminjaman', function ($query) {
                    $query->whereIn('status', ['disetujui', 'dipinjam', 'jatuh tenggat']);
                })
                ->sum('jumlah');

            $totalLoanHistory = $inventaris->detailPeminjaman()->count();

            $stats = [
                'tersedia' => $inventaris->tersedia,
                'dipinjam' => $inventaris->total_dipinjam ?? 0,
                'active_loans' => $activeLoanCount,
                'total_history' => $totalLoanHistory
            ];

            return view('admin.inventaris.show', compact('inventaris', 'stats'));
        } catch (\Exception $e) {
            Log::error('Inventaris show error', ['id' => $id, 'error' => $e->getMessage()]);
            return redirect()->route('admin.inventaris.index')
                ->with('error', 'Inventaris tidak ditemukan.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            $inventaris = Inventaris::findOrFail($id);
            return view('admin.inventaris.edit', compact('inventaris'));
        } catch (\Exception $e) {
            Log::error('Inventaris edit error', ['id' => $id, 'error' => $e->getMessage()]);
            return redirect()->route('admin.inventaris.index')
                ->with('error', 'Inventaris tidak ditemukan.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $inventaris = Inventaris::findOrFail($id);

            $validated = $request->validate([
                'nama' => 'required|string|max:255|unique:inventaris,nama,' . $id,
                'kuantitas' => 'required|integer|min:0',
                'deskripsi' => 'nullable|string|max:1000',
                'images' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'status' => 'required|in:tersedia,tidak tersedia',
            ], [
                'nama.required' => 'Nama inventaris wajib diisi.',
                'nama.unique' => 'Nama inventaris sudah ada.',
                'kuantitas.required' => 'Kuantitas wajib diisi.',
                'kuantitas.integer' => 'Kuantitas harus berupa angka.',
                'kuantitas.min' => 'Kuantitas tidak boleh kurang dari 0.',
                'images.image' => 'File harus berupa gambar.',
                'images.max' => 'Ukuran gambar maksimal 2MB.',
                'status.required' => 'Status wajib dipilih.',
            ]);

            // Validasi kuantitas tidak boleh kurang dari total dipinjam
            if ($validated['kuantitas'] < ($inventaris->total_dipinjam ?? 0)) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', "Kuantitas tidak boleh kurang dari total yang sedang dipinjam ({$inventaris->total_dipinjam}).");
            }

            // Handle image upload
            if ($request->hasFile('images')) {
                // Delete old image
                if ($inventaris->images) {
                    Storage::disk('public')->delete($inventaris->images);
                }
                $validated['images'] = $request->file('images')->store('inventaris', 'public');
            }

            $inventaris->update($validated);

            return redirect()->route('admin.inventaris.show', $inventaris->id)
                ->with('success', 'Inventaris "' . $inventaris->nama . '" berhasil diupdate!');
        } catch (\Exception $e) {
            Log::error('Inventaris update error', ['id' => $id, 'error' => $e->getMessage()]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat mengupdate inventaris: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $inventaris = Inventaris::findOrFail($id);

            // Cek apakah inventaris bisa dihapus (tidak ada peminjaman aktif)
            if (!$inventaris->canBeDeleted()) {
                return redirect()->route('admin.inventaris.index')
                    ->with('error', 'Tidak dapat menghapus inventaris yang sedang dipinjam atau memiliki riwayat peminjaman aktif!');
            }

            $nama = $inventaris->nama;

            // Delete image
            if ($inventaris->images) {
                Storage::disk('public')->delete($inventaris->images);
            }

            $inventaris->delete();

            return redirect()->route('admin.inventaris.index')
                ->with('success', 'Inventaris "' . $nama . '" berhasil dihapus!');
        } catch (\Exception $e) {
            Log::error('Inventaris destroy error', ['id' => $id, 'error' => $e->getMessage()]);
            return redirect()->route('admin.inventaris.index')
                ->with('error', 'Terjadi kesalahan saat menghapus inventaris: ' . $e->getMessage());
        }
    }

    /**
     * Upload image for inventaris
     */
    public function uploadImage(Request $request, $id)
    {
        try {
            $inventaris = Inventaris::findOrFail($id);

            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // Delete old image
            if ($inventaris->images) {
                Storage::disk('public')->delete($inventaris->images);
            }

            // Store new image
            $imagePath = $request->file('image')->store('inventaris', 'public');

            $inventaris->update(['images' => $imagePath]);

            return response()->json([
                'success' => true,
                'message' => 'Gambar berhasil diupload',
                'image_url' => Storage::url($imagePath)
            ]);
        } catch (\Exception $e) {
            Log::error('Upload image error', ['id' => $id, 'error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal upload gambar: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk update status
     */
    public function bulkUpdateStatus(Request $request)
    {
        try {
            $request->validate([
                'ids' => 'required|array|min:1',
                'ids.*' => 'exists:inventaris,id',
                'status' => 'required|in:tersedia,tidak tersedia',
            ]);

            // Validasi status untuk inventaris yang sedang dipinjam
            if ($request->status === 'tersedia') {
                $inventarisItems = Inventaris::whereIn('id', $request->ids)->get();

                foreach ($inventarisItems as $item) {
                    if (($item->total_dipinjam ?? 0) >= $item->kuantitas) {
                        return response()->json([
                            'success' => false,
                            'message' => "Inventaris '{$item->nama}' tidak bisa diset tersedia karena stok sudah habis dipinjam."
                        ], 422);
                    }
                }
            }

            $updated = Inventaris::whereIn('id', $request->ids)
                ->update(['status' => $request->status]);

            return response()->json([
                'success' => true,
                'message' => "Status {$updated} inventaris berhasil diupdate menjadi " . $request->status
            ]);
        } catch (\Exception $e) {
            Log::error('Bulk update status error', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal update status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Recalculate all inventaris totals
     */
    public function recalculateAll()
    {
        try {
            DB::beginTransaction();

            $inventaris = Inventaris::all();
            $updated = 0;
            $errors = [];

            foreach ($inventaris as $item) {
                try {
                    $item->recalculateTotalDipinjam();
                    $updated++;
                } catch (\Exception $e) {
                    $errors[] = "Error pada {$item->nama}: " . $e->getMessage();
                }
            }

            DB::commit();

            $message = "Berhasil merecalculate {$updated} inventaris.";
            if (!empty($errors)) {
                $message .= " Errors: " . implode(', ', array_slice($errors, 0, 3));
                if (count($errors) > 3) {
                    $message .= " dan " . (count($errors) - 3) . " error lainnya.";
                }
            }

            return redirect()->route('admin.inventaris.index')
                ->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Recalculate all error', ['error' => $e->getMessage()]);

            return redirect()->route('admin.inventaris.index')
                ->with('error', 'Terjadi kesalahan saat recalculate: ' . $e->getMessage());
        }
    }

    /**
     * Export inventaris to Excel
     */
    public function export(Request $request)
    {
        try {
            // Log untuk debugging
            Log::info('Export inventaris started', [
                'user_id' => auth()->id(),
                'filters' => $request->only(['search', 'status'])
            ]);

            // Validate filters
            $filters = $request->validate([
                'search' => 'nullable|string|max:255',
                'status' => 'nullable|in:tersedia,tidak tersedia'
            ]);

            // Generate filename with timestamp
            $timestamp = now()->format('Y-m-d_H-i-s');
            $filename = "inventaris_export_{$timestamp}.xlsx";

            // Create export instance
            $export = new InventarisExport($filters);

            Log::info('Export inventaris processing', [
                'filename' => $filename,
                'filters' => $filters
            ]);

            return Excel::download($export, $filename, \Maatwebsite\Excel\Excel::XLSX, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ]);
        } catch (\Exception $e) {
            Log::error('Export inventaris failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('admin.inventaris.index')
                ->with('error', 'Gagal mengexport data: ' . $e->getMessage());
        }
    }

    /**
     * Show import form
     */
    public function showImportForm()
    {
        return view('admin.inventaris.import');
    }

    /**
     * Import inventaris from Excel
     */
    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => [
                'required',
                'file',
                'mimes:xlsx,xls,csv',
                'max:2048' // 2MB max
            ]
        ], [
            'file.required' => 'File Excel wajib dipilih.',
            'file.mimes' => 'File harus berformat Excel (.xlsx, .xls) atau CSV.',
            'file.max' => 'Ukuran file maksimal 2MB.'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->with('error', 'Validasi file gagal.');
        }

        try {
            DB::beginTransaction();

            // Create import instance
            $import = new InventarisImport();

            // Process the import
            Excel::import($import, $request->file('file'));

            // Get import results
            $results = $import->getImportResults();

            DB::commit();

            // Prepare success message
            $message = "Import berhasil! ";
            $message .= "Dibuat: {$results['created']}, ";
            $message .= "Diupdate: {$results['updated']}, ";
            $message .= "Dilewati: {$results['skipped']}";

            if ($results['errors'] > 0) {
                $message .= ", Error: {$results['errors']}";
            }

            // Return with detailed results
            return redirect()->route('admin.inventaris.index')
                ->with('success', $message)
                ->with('import_details', $results);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            DB::rollBack();

            $failures = $e->failures();
            $errorMessages = [];

            foreach ($failures as $failure) {
                $errorMessages[] = "Baris {$failure->row()}: " . implode(', ', $failure->errors());
            }

            return redirect()->back()
                ->with('error', 'Validasi data gagal.')
                ->with('validation_errors', $errorMessages);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Import inventaris failed', ['error' => $e->getMessage()]);
            return redirect()->back()
                ->with('error', 'Import gagal: ' . $e->getMessage());
        }
    }

    /**
     * Download template Excel for import
     */
    public function downloadTemplate()
    {
        try {
            Log::info('Download template started', ['user_id' => auth()->id()]);

            // Use same headers as export for consistency
            $headers = [
                ['ID', 'Nama Inventaris', 'Kuantitas', 'Status', 'Deskripsi', 'Total Dipinjam', 'Tersedia', 'Tanggal Dibuat', 'Terakhir Diupdate'],
                ['', 'Mikroskop Digital', '5', 'tersedia', 'Mikroskop digital untuk lab biologi', '', '', '', ''],
                ['', 'Pipet Mikro', '20', 'tersedia', 'Pipet mikro 10-100µL', '', '', '', ''],
                ['', 'Tabung Reaksi', '50', 'tidak tersedia', 'Tabung reaksi borosilikat 15ml', '', '', '', ''],
                ['', 'Beaker Glass 250ml', '30', 'tersedia', 'Gelas beaker borosilikat', '', '', '', ''],
                ['', 'pH Meter Digital', '3', 'tersedia', 'Alat ukur pH digital', '', '', '', ''],
            ];

            $filename = 'template_import_inventaris_' . now()->format('Y-m-d') . '.xlsx';

            // Create Excel file using the same export class structure
            $export = new class($headers) implements \Maatwebsite\Excel\Concerns\FromArray, \Maatwebsite\Excel\Concerns\WithHeadings, \Maatwebsite\Excel\Concerns\WithStyles, \Maatwebsite\Excel\Concerns\WithColumnWidths {
                private $data;

                public function __construct($data) {
                    $this->data = $data;
                }

                public function array(): array {
                    return array_slice($this->data, 1); // Skip header row
                }

                public function headings(): array {
                    return $this->data[0]; // First row as heading
                }

                public function columnWidths(): array {
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

                public function styles(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet) {
                    return [
                        1 => [
                            'font' => ['bold' => true],
                            'fill' => [
                                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                'startColor' => ['rgb' => '4F46E5']
                            ],
                            'font' => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true],
                        ],
                    ];
                }
            };

            return Excel::download($export, $filename);
        } catch (\Exception $e) {
            Log::error('Download template failed', [
                'error' => $e->getMessage()
            ]);

            return redirect()->route('admin.inventaris.index')
                ->with('error', 'Gagal mendownload template: ' . $e->getMessage());
        }
    }

    /**
     * Bulk export selected inventaris
     */
    public function bulkExport(Request $request)
    {
        try {
            $request->validate([
                'ids' => 'required|array|min:1',
                'ids.*' => 'exists:inventaris,id',
            ]);

            Log::info('Bulk export started', [
                'selected_count' => count($request->ids),
                'user_id' => auth()->id()
            ]);

            // Store selected IDs in session for download
            session()->put('export_selected_ids', $request->ids);

            return response()->json([
                'success' => true,
                'message' => 'Export sedang diproses...',
                'download_url' => route('admin.inventaris.export-selected')
            ]);
        } catch (\Exception $e) {
            Log::error('Bulk export failed', [
                'error' => $e->getMessage(),
                'selected_ids' => $request->ids ?? []
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses export: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export selected inventaris
     */
    public function exportSelected()
    {
        try {
            $selectedIds = session()->get('export_selected_ids');

            if (!$selectedIds || !is_array($selectedIds)) {
                return redirect()->route('admin.inventaris.index')
                    ->with('error', 'Tidak ada data yang dipilih untuk export atau session expired.');
            }

            Log::info('Export selected processing', [
                'selected_ids' => $selectedIds,
                'count' => count($selectedIds)
            ]);

            // Validate that the selected IDs still exist
            $validIds = Inventaris::whereIn('id', $selectedIds)->pluck('id')->toArray();

            if (empty($validIds)) {
                session()->forget('export_selected_ids');
                return redirect()->route('admin.inventaris.index')
                    ->with('error', 'Data yang dipilih tidak valid atau sudah dihapus.');
            }

            // Generate filename
            $timestamp = now()->format('Y-m-d_H-i-s');
            $filename = "inventaris_selected_{$timestamp}.xlsx";

            // Create export with selected IDs
            $export = new InventarisExport(['selected_ids' => $validIds]);

            // Clear session data
            session()->forget('export_selected_ids');

            Log::info('Export selected completed', [
                'filename' => $filename,
                'exported_count' => count($validIds)
            ]);

            return Excel::download($export, $filename, \Maatwebsite\Excel\Excel::XLSX, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ]);
        } catch (\Exception $e) {
            session()->forget('export_selected_ids');

            Log::error('Export selected failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('admin.inventaris.index')
                ->with('error', 'Gagal export data terpilih: ' . $e->getMessage());
        }
    }

    /**
     * AJAX method untuk mendapatkan stok real-time
     */
    public function getStock($id)
    {
        try {
            $inventaris = Inventaris::findOrFail($id);

            // Recalculate untuk data terbaru
            $inventaris->recalculateTotalDipinjam();

            $stockInfo = [
                'id' => $inventaris->id,
                'nama' => $inventaris->nama,
                'kuantitas' => $inventaris->kuantitas,
                'total_dipinjam' => $inventaris->total_dipinjam ?? 0,
                'tersedia' => $inventaris->tersedia,
                'status' => $inventaris->status,
                'can_borrow' => $inventaris->status === 'tersedia' && $inventaris->tersedia > 0
            ];

            return response()->json($stockInfo);
        } catch (\Exception $e) {
            Log::error('Get stock error', ['id' => $id, 'error' => $e->getMessage()]);
            return response()->json([
                'error' => 'Inventaris tidak ditemukan'
            ], 404);
        }
    }
}
