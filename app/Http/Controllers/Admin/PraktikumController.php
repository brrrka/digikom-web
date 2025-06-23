<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Praktikum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PraktikumController extends Controller
{
    public function index(Request $request)
    {
        $query = Praktikum::query();

        // Search functionality
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $praktikums = $query->withCount('moduls')->latest()->paginate(10);

        return view('admin.praktikums.index', compact('praktikums'));
    }

    public function create()
    {
        return view('admin.praktikums.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:praktikums,name',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'name.required' => 'Nama praktikum wajib diisi.',
            'name.unique' => 'Nama praktikum sudah ada.',
            'name.max' => 'Nama praktikum maksimal 255 karakter.',
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Gambar harus berformat JPEG, PNG, JPG, atau GIF.',
            'image.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        try {
            // Generate slug (akan dilakukan otomatis di model boot)
            $validated['slug'] = Str::slug($validated['name']);

            // Handle image upload
            if ($request->hasFile('image')) {
                $validated['image'] = $request->file('image')->store('praktikums', 'public');
            }

            $praktikum = Praktikum::create($validated);

            return redirect()->route('admin.praktikums.index')
                ->with('success', 'Praktikum berhasil dibuat!');
        } catch (\Exception $e) {
            // Clean up uploaded file if creation fails
            if (isset($validated['image'])) {
                Storage::disk('public')->delete($validated['image']);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan praktikum. Silakan coba lagi.');
        }
    }

    public function show(Praktikum $praktikum)
    {
        $praktikum->load(['moduls' => function ($query) {
            $query->orderBy('modul_ke');
        }]);

        return view('admin.praktikums.show', compact('praktikum'));
    }

    public function edit(Praktikum $praktikum)
    {
        return view('admin.praktikums.edit', compact('praktikum'));
    }

    public function update(Request $request, Praktikum $praktikum)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:praktikums,name,' . $praktikum->id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'name.required' => 'Nama praktikum wajib diisi.',
            'name.unique' => 'Nama praktikum sudah ada.',
            'name.max' => 'Nama praktikum maksimal 255 karakter.',
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Gambar harus berformat JPEG, PNG, JPG, atau GIF.',
            'image.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        try {
            // Update slug if name changed (akan dilakukan otomatis di model boot)
            if ($praktikum->name !== $validated['name']) {
                $validated['slug'] = Str::slug($validated['name']);
            }

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image
                if ($praktikum->image) {
                    Storage::disk('public')->delete($praktikum->image);
                }
                $validated['image'] = $request->file('image')->store('praktikums', 'public');
            }

            $praktikum->update($validated);

            return redirect()->route('admin.praktikums.show', $praktikum)
                ->with('success', 'Praktikum berhasil diupdate!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat mengupdate praktikum. Silakan coba lagi.');
        }
    }

    public function destroy(Praktikum $praktikum)
    {
        try {
            // Check if praktikum has moduls
            if ($praktikum->moduls()->count() > 0) {
                return redirect()->route('admin.praktikums.index')
                    ->with('error', 'Tidak dapat menghapus praktikum yang memiliki modul!');
            }

            // Delete image
            if ($praktikum->image) {
                Storage::disk('public')->delete($praktikum->image);
            }

            $praktikum->delete();

            return redirect()->route('admin.praktikums.index')
                ->with('success', 'Praktikum "' . $praktikum->name . '" berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus praktikum. Silakan coba lagi.');
        }
    }
}
