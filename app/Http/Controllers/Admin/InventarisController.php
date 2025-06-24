<?php
// app/Http/Controllers/Admin/InventarisController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventaris;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class InventarisController extends Controller
{
    public function index(Request $request)
    {
        $query = Inventaris::query();

        // Search functionality
        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%')
                ->orWhere('deskripsi', 'like', '%' . $request->search . '%');
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $inventaris = $query->latest()->paginate(10);

        // Stats for dashboard cards dengan perhitungan yang benar
        $stats = [
            'total' => Inventaris::count(),
            'tersedia' => Inventaris::where('status', 'tersedia')->count(),
            'tidak_tersedia' => Inventaris::where('status', 'tidak tersedia')->count(),
            'total_kuantitas' => Inventaris::sum('kuantitas'),
            'total_dipinjam' => Inventaris::sum('total_dipinjam'),
            'total_tersedia' => DB::raw('SUM(kuantitas - COALESCE(total_dipinjam, 0))')
        ];

        // Hitung total tersedia dengan query terpisah karena menggunakan DB::raw
        $stats['total_tersedia'] = Inventaris::selectRaw('SUM(kuantitas - COALESCE(total_dipinjam, 0)) as total')
            ->first()->total ?? 0;

        return view('admin.inventaris.index', compact('inventaris', 'stats'));
    }

    public function create()
    {
        return view('admin.inventaris.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kuantitas' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
            'images' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:tersedia,tidak tersedia',
        ]);

        // Handle image upload
        if ($request->hasFile('images')) {
            $validated['images'] = $request->file('images')->store('inventaris', 'public');
        }

        // Set default total_dipinjam
        $validated['total_dipinjam'] = 0;

        $inventaris = Inventaris::create($validated);

        return redirect()->route('admin.inventaris.index')
            ->with('success', 'Inventaris berhasil ditambahkan!');
    }

    public function show($id)
    {
        $inventaris = Inventaris::with(['detailPeminjaman.peminjaman.user'])
            ->findOrFail($id);

        // Hitung statistik untuk inventaris ini
        $activeLoanCount = $inventaris->detailPeminjaman()
            ->whereHas('peminjaman', function ($query) {
                $query->whereIn('status', ['disetujui', 'dipinjam', 'jatuh tenggat']);
            })
            ->count();

        $totalLoanHistory = $inventaris->detailPeminjaman()->count();

        $stats = [
            'tersedia' => $inventaris->tersedia,
            'dipinjam' => $inventaris->total_dipinjam ?? 0,
            'active_loans' => $activeLoanCount,
            'total_history' => $totalLoanHistory
        ];

        return view('admin.inventaris.show', compact('inventaris', 'stats'));
    }

    public function edit($id)
    {
        $inventaris = Inventaris::findOrFail($id);
        return view('admin.inventaris.edit', compact('inventaris'));
    }

    public function update(Request $request, $id)
    {
        $inventaris = Inventaris::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kuantitas' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
            'images' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:tersedia,tidak tersedia',
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
            ->with('success', 'Inventaris berhasil diupdate!');
    }

    public function destroy($id)
    {
        try {
            $inventaris = Inventaris::findOrFail($id);

            // Cek apakah inventaris bisa dihapus (tidak ada peminjaman aktif)
            if (!$inventaris->canBeDeleted()) {
                return redirect()->route('admin.inventaris.index')
                    ->with('error', 'Tidak dapat menghapus inventaris yang sedang dipinjam atau memiliki riwayat peminjaman aktif!');
            }

            // Delete image
            if ($inventaris->images) {
                Storage::disk('public')->delete($inventaris->images);
            }

            $inventaris->delete();

            return redirect()->route('admin.inventaris.index')
                ->with('success', 'Inventaris "' . $inventaris->nama . '" berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('admin.inventaris.index')
                ->with('error', 'Terjadi kesalahan saat menghapus inventaris: ' . $e->getMessage());
        }
    }

    public function uploadImage(Request $request, $id)
    {
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
    }

    public function bulkUpdateStatus(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
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

        Inventaris::whereIn('id', $request->ids)
            ->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Status inventaris berhasil diupdate'
        ]);
    }

    // Method baru untuk recalculate semua inventaris
    public function recalculateAll()
    {
        try {
            DB::beginTransaction();

            $inventaris = Inventaris::all();
            $updated = 0;
            $errors = [];

            foreach ($inventaris as $item) {
                try {
                    $oldTotal = $item->total_dipinjam ?? 0;
                    $item->recalculateTotalDipinjam();
                    $newTotal = $item->fresh()->total_dipinjam ?? 0;

                    $updated++;
                } catch (\Exception $e) {
                    $errors[] = "Error pada {$item->nama}: " . $e->getMessage();
                }
            }

            DB::commit();

            $message = "Berhasil merecalculate {$updated} inventaris.";
            if (!empty($errors)) {
                $message .= " Errors: " . implode(', ', $errors);
            }

            return redirect()->route('admin.inventaris.index')
                ->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('admin.inventaris.index')
                ->with('error', 'Terjadi kesalahan saat recalculate: ' . $e->getMessage());
        }
    }


    // AJAX method untuk mendapatkan stok real-time
    public function getStock($id)
    {
        try {
            $stockInfo = Inventaris::getStockInfo($id);

            if (!$stockInfo) {
                return response()->json(['error' => 'Inventaris tidak ditemukan'], 404);
            }

            return response()->json($stockInfo);
        } catch (\Exception $e) {

            return response()->json([
                'error' => 'Terjadi kesalahan saat mengambil data stok'
            ], 500);
        }
    }
}
