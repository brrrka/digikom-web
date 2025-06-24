<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Modul;
use App\Models\Praktikum;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ModulController extends Controller
{
    public function index(Request $request)
    {
        $query = Modul::with('praktikum');

        // Filter by praktikum
        if ($request->filled('praktikum_id')) {
            $query->where('id_praktikums', $request->praktikum_id);
        }

        // Search functionality
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                ->orWhere('deskripsi', 'like', '%' . $request->search . '%');
        }

        $moduls = $query->latest()->paginate(10);
        $praktikums = Praktikum::orderBy('name')->get();

        return view('admin.moduls.index', compact('moduls', 'praktikums'));
    }

    public function create()
    {
        $praktikums = Praktikum::orderBy('name')->get();

        $users = User::whereHas('role', function ($query) {
            $query->where('roles', 'superadmin');
        })
            ->orWhere('id_roles', 1)
            ->orderBy('name')
            ->get();

        return view('admin.moduls.create', compact('praktikums', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_praktikums' => 'required|exists:praktikums,id',
            'modul_ke' => [
                'required',
                'integer',
                'min:1',
                Rule::unique('moduls')->where(function ($query) use ($request) {
                    return $query->where('id_praktikums', $request->id_praktikums);
                }),
            ],
            'title' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:2000',
            'file_path' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx|max:10240',
            'link_video' => 'nullable|url|max:500',
            'images' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'id_praktikums.required' => 'Praktikum wajib dipilih.',
            'id_praktikums.exists' => 'Praktikum yang dipilih tidak valid.',
            'modul_ke.required' => 'Nomor modul wajib diisi.',
            'modul_ke.unique' => 'Nomor modul ini sudah ada untuk praktikum yang dipilih.',
            'title.required' => 'Judul modul wajib diisi.',
            'title.max' => 'Judul modul maksimal 255 karakter.',
            'deskripsi.max' => 'Deskripsi maksimal 2000 karakter.',
            'file_path.mimes' => 'File harus berformat PDF, DOC, DOCX, PPT, atau PPTX.',
            'file_path.max' => 'Ukuran file maksimal 10MB.',
            'link_video.url' => 'Link video harus berupa URL yang valid.',
            'images.image' => 'File harus berupa gambar.',
            'images.mimes' => 'Gambar harus berformat JPEG, PNG, JPG, atau GIF.',
            'images.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        try {
            // Handle file upload
            if ($request->hasFile('file_path')) {
                $validated['file_path'] = $request->file('file_path')->store('moduls', 'public');
            }

            // Handle image upload
            if ($request->hasFile('images')) {
                $validated['images'] = $request->file('images')->store('moduls/images', 'public');
            }

            $modul = Modul::create($validated);

            return redirect()->route('admin.moduls.index')
                ->with('success', 'Modul berhasil dibuat!');
        } catch (\Exception $e) {
            // Clean up uploaded files if creation fails
            if (isset($validated['file_path'])) {
                Storage::disk('public')->delete($validated['file_path']);
            }
            if (isset($validated['images'])) {
                Storage::disk('public')->delete($validated['images']);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan modul. Silakan coba lagi.');
        }
    }

    public function show(Modul $modul)
    {
        $modul->load('praktikum');
        return view('admin.moduls.show', compact('modul'));
    }

    public function edit(Modul $modul)
    {
        $praktikums = Praktikum::orderBy('name')->get();
        return view('admin.moduls.edit', compact('modul', 'praktikums'));
    }

    public function update(Request $request, Modul $modul)
    {
        $validated = $request->validate([
            'id_praktikums' => 'required|exists:praktikums,id',
            'modul_ke' => [
                'required',
                'integer',
                'min:1',
                Rule::unique('moduls')->where(function ($query) use ($request) {
                    return $query->where('id_praktikums', $request->id_praktikums);
                })->ignore($modul->id),
            ],
            'title' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:2000',
            'file_path' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx|max:10240',
            'link_video' => 'nullable|url|max:500',
            'images' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'id_praktikums.required' => 'Praktikum wajib dipilih.',
            'id_praktikums.exists' => 'Praktikum yang dipilih tidak valid.',
            'modul_ke.required' => 'Nomor modul wajib diisi.',
            'modul_ke.unique' => 'Nomor modul ini sudah ada untuk praktikum yang dipilih.',
            'title.required' => 'Judul modul wajib diisi.',
            'title.max' => 'Judul modul maksimal 255 karakter.',
            'deskripsi.max' => 'Deskripsi maksimal 2000 karakter.',
            'file_path.mimes' => 'File harus berformat PDF, DOC, DOCX, PPT, atau PPTX.',
            'file_path.max' => 'Ukuran file maksimal 10MB.',
            'link_video.url' => 'Link video harus berupa URL yang valid.',
            'images.image' => 'File harus berupa gambar.',
            'images.mimes' => 'Gambar harus berformat JPEG, PNG, JPG, atau GIF.',
            'images.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        try {
            // Handle file upload
            if ($request->hasFile('file_path')) {
                // Delete old file
                if ($modul->file_path) {
                    Storage::disk('public')->delete($modul->file_path);
                }
                $validated['file_path'] = $request->file('file_path')->store('moduls', 'public');
            }

            // Handle image upload
            if ($request->hasFile('images')) {
                // Delete old image
                if ($modul->images) {
                    Storage::disk('public')->delete($modul->images);
                }
                $validated['images'] = $request->file('images')->store('moduls/images', 'public');
            }

            $modul->update($validated);

            return redirect()->route('admin.moduls.show', $modul)
                ->with('success', 'Modul berhasil diupdate!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat mengupdate modul. Silakan coba lagi.');
        }
    }

    public function destroy(Modul $modul)
    {
        try {
            // Delete files
            if ($modul->file_path) {
                Storage::disk('public')->delete($modul->file_path);
            }
            if ($modul->images) {
                Storage::disk('public')->delete($modul->images);
            }

            $modul->delete();

            return redirect()->route('admin.moduls.index')
                ->with('success', 'Modul "' . $modul->title . '" berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus modul. Silakan coba lagi.');
        }
    }

    public function uploadFile(Request $request, Modul $modul)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx,ppt,pptx|max:10240',
        ]);

        try {
            // Delete old file
            if ($modul->file_path) {
                Storage::disk('public')->delete($modul->file_path);
            }

            // Store new file
            $filePath = $request->file('file')->store('moduls', 'public');

            $modul->update(['file_path' => $filePath]);

            return response()->json([
                'success' => true,
                'message' => 'File berhasil diupload',
                'file_url' => Storage::url($filePath)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengupload file.'
            ], 500);
        }
    }

    public function deleteFile($id)
    {
        try {
            $modul = Modul::findOrFail($id);

            if ($modul->file_path) {
                Storage::disk('public')->delete($modul->file_path);
                $modul->update(['file_path' => null]);
            }

            return response()->json([
                'success' => true,
                'message' => 'File berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus file.'
            ], 500);
        }
    }
}
