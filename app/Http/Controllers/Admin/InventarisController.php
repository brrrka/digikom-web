<?php
// app/Http/Controllers/Admin/InventarisController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventaris;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

        // Stats for dashboard cards - PERBAIKAN: Hapus perhitungan yang error
        $stats = [
            'total' => Inventaris::count(),
            'tersedia' => Inventaris::where('status', 'tersedia')->count(),
            'tidak_tersedia' => Inventaris::where('status', 'tidak tersedia')->count(),
            'total_kuantitas' => Inventaris::sum('kuantitas'),
            'total_dipinjam' => 0 // Set default 0 dulu, nanti bisa dihitung kalau tabel peminjaman sudah ada
        ];

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

        $inventaris = Inventaris::create($validated);

        return redirect()->route('admin.inventaris.index')
            ->with('success', 'Inventaris berhasil ditambahkan!');
    }

    // PERBAIKAN: Ganti parameter dari $inventaris menjadi $inventari
    public function show($id)
    {
        $inventaris = Inventaris::findOrFail($id);

        // PERBAIKAN: Coba load relasi, tapi handle jika tidak ada
        try {
            $inventaris->load(['detailPeminjaman.peminjaman.user']);
        } catch (\Exception $e) {
            // Jika relasi tidak ada, biarkan kosong
        }

        return view('admin.inventaris.show', compact('inventaris'));
    }

    // PERBAIKAN: Ganti parameter dari $inventaris menjadi $inventari
    public function edit($id)
    {
        $inventaris = Inventaris::findOrFail($id);
        return view('admin.inventaris.edit', compact('inventaris'));
    }

    // PERBAIKAN: Ganti parameter dari $inventaris menjadi $inventari
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

    // PERBAIKAN: Ganti parameter dari $inventaris menjadi $inventari
    public function destroy($id)
    {
        try {
            $inventaris = Inventaris::findOrFail($id);

            // PERBAIKAN: Simplifikasi cek peminjaman - cek apakah ada detail peminjaman
            $hasDetailPeminjaman = false;
            try {
                // Coba cek apakah ada tabel detail_peminjaman dan ada data
                $hasDetailPeminjaman = \DB::table('detail_peminjaman')
                    ->where('id_inventaris', $id)
                    ->exists();
            } catch (\Exception $e) {
                // Jika tabel tidak ada, lanjutkan saja
                $hasDetailPeminjaman = false;
            }

            if ($hasDetailPeminjaman) {
                return redirect()->route('admin.inventaris.index')
                    ->with('error', 'Tidak dapat menghapus inventaris yang pernah dipinjam!');
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
                ->with('error', 'Terjadi kesalahan saat menghapus inventaris. Silakan coba lagi.');
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

        Inventaris::whereIn('id', $request->ids)
            ->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Status inventaris berhasil diupdate'
        ]);
    }
}
