<?php
// app/Http/Controllers/Admin/ArtikelController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artikel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArtikelController extends Controller
{
    public function index(Request $request)
    {
        $query = Artikel::with('user');

        // Search functionality
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('content', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by author
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $artikel = $query->latest()->paginate(12);

        // Stats for dashboard cards
        $stats = [
            'total' => Artikel::count(),
            'published' => Artikel::where('status', 'published')->count(),
            'draft' => Artikel::where('status', 'draft')->count(),
            'this_month' => Artikel::whereMonth('created_at', now()->month)->count(),
        ];

        $users = User::orderBy('name')->get();

        return view('admin.artikel.index', compact('artikel', 'stats', 'users'));
    }

    public function create()
    {
        $users = User::orderBy('name')->get();
        return view('admin.artikel.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:draft,published',
            'meta_description' => 'nullable|string|max:160',
            'tags' => 'nullable|string',
        ]);

        // Generate slug
        $validated['slug'] = $this->generateUniqueSlug($validated['title']);

        // Handle image upload
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('artikel', 'public');
        }

        // Set published_at if status is published
        if ($validated['status'] === 'published') {
            $validated['published_at'] = now();
        }

        $artikel = Artikel::create($validated);

        return redirect()->route('admin.artikel.index')
            ->with('success', 'Artikel berhasil dibuat!');
    }

    public function show(Artikel $artikel)
    {
        $artikel->load('user');
        return view('admin.artikel.show', compact('artikel'));
    }

    public function edit(Artikel $artikel)
    {
        $users = User::orderBy('name')->get();
        return view('admin.artikel.edit', compact('artikel', 'users'));
    }

    public function update(Request $request, Artikel $artikel)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:draft,published',
            'meta_description' => 'nullable|string|max:160',
            'tags' => 'nullable|string',
        ]);

        // Update slug if title changed
        if ($artikel->title !== $validated['title']) {
            $validated['slug'] = $this->generateUniqueSlug($validated['title'], $artikel->id);
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($artikel->image) {
                Storage::disk('public')->delete($artikel->image);
            }
            $validated['image'] = $request->file('image')->store('artikel', 'public');
        }

        // Set published_at if status changed to published
        if ($validated['status'] === 'published' && $artikel->status === 'draft') {
            $validated['published_at'] = now();
        } elseif ($validated['status'] === 'draft') {
            $validated['published_at'] = null;
        }

        $artikel->update($validated);

        return redirect()->route('admin.artikel.index')
            ->with('success', 'Artikel berhasil diupdate!');
    }

    public function destroy(Artikel $artikel)
    {
        // Delete image
        if ($artikel->image) {
            Storage::disk('public')->delete($artikel->image);
        }

        $artikel->delete();

        return redirect()->route('admin.artikel.index')
            ->with('success', 'Artikel berhasil dihapus!');
    }

    public function toggleStatus(Artikel $artikel)
    {
        $newStatus = $artikel->status === 'published' ? 'draft' : 'published';

        $updateData = ['status' => $newStatus];

        // Set published_at when publishing
        if ($newStatus === 'published') {
            $updateData['published_at'] = now();
        } else {
            $updateData['published_at'] = null;
        }

        $artikel->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Status artikel berhasil diupdate',
            'new_status' => $newStatus
        ]);
    }

    public function bulkAction(Request $request)
    {
        $validated = $request->validate([
            'action' => 'required|in:delete,publish,draft,change_author',
            'artikel_ids' => 'required|array',
            'artikel_ids.*' => 'exists:artikel,id',
            'user_id' => 'required_if:action,change_author|exists:users,id',
        ]);

        $artikel = Artikel::whereIn('id', $validated['artikel_ids']);

        switch ($validated['action']) {
            case 'delete':
                foreach ($artikel->get() as $item) {
                    if ($item->image) {
                        Storage::disk('public')->delete($item->image);
                    }
                }
                $artikel->delete();
                $message = 'Artikel berhasil dihapus';
                break;

            case 'publish':
                $artikel->update([
                    'status' => 'published',
                    'published_at' => now()
                ]);
                $message = 'Artikel berhasil dipublikasi';
                break;

            case 'draft':
                $artikel->update([
                    'status' => 'draft',
                    'published_at' => null
                ]);
                $message = 'Artikel berhasil dijadikan draft';
                break;

            case 'change_author':
                $artikel->update(['user_id' => $validated['user_id']]);
                $message = 'Author artikel berhasil diupdate';
                break;
        }

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $path = $request->file('image')->store('artikel/editor', 'public');

        return response()->json([
            'success' => true,
            'url' => Storage::url($path)
        ]);
    }

    public function duplicate(Artikel $artikel)
    {
        $newArtikel = $artikel->replicate();
        $newArtikel->title = $artikel->title . ' (Copy)';
        $newArtikel->slug = $this->generateUniqueSlug($newArtikel->title);
        $newArtikel->status = 'draft';
        $newArtikel->published_at = null;
        $newArtikel->created_at = now();
        $newArtikel->updated_at = now();

        // Copy image if exists
        if ($artikel->image) {
            $originalPath = $artikel->image;
            $extension = pathinfo($originalPath, PATHINFO_EXTENSION);
            $newPath = 'artikel/' . Str::random(40) . '.' . $extension;

            if (Storage::disk('public')->exists($originalPath)) {
                Storage::disk('public')->copy($originalPath, $newPath);
                $newArtikel->image = $newPath;
            }
        }

        $newArtikel->save();

        return redirect()->route('admin.artikel.edit', $newArtikel)
            ->with('success', 'Artikel berhasil diduplikasi!');
    }

    private function generateUniqueSlug($title, $ignoreId = null)
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;

        while (true) {
            $query = Artikel::where('slug', $slug);

            if ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            }

            if (!$query->exists()) {
                break;
            }

            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    public function preview(Artikel $artikel)
    {
        return view('admin.artikel.preview', compact('artikel'));
    }
}
