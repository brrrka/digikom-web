<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use Illuminate\Http\Request;

class ArtikelController extends Controller
{
    /**
     * Menampilkan halaman index artikel (untuk website)
     */
    public function index(Request $request)
    {
        $query = Artikel::with('user')
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->latest('published_at');

        // Search functionality
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('content', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by tags
        if ($request->filled('tag')) {
            $query->where('tags', 'like', '%' . $request->tag . '%');
        }

        $artikels = $query->paginate(12);

        // Get popular tags untuk filter
        $popular_tags = $this->getPopularTagsArray(8);

        return view('pages.artikel.index', compact('artikels', 'popular_tags'));
    }

    /**
     * Menampilkan detail artikel
     */
    public function show($identifier)
    {
        // Cek apakah identifier adalah slug atau ID
        if (is_numeric($identifier)) {
            $artikel = Artikel::with('user')
                ->where('status', 'published')
                ->whereNotNull('published_at')
                ->findOrFail($identifier);
        } else {
            $artikel = Artikel::with('user')
                ->where('status', 'published')
                ->whereNotNull('published_at')
                ->where('slug', $identifier)
                ->firstOrFail();
        }

        return view('pages.artikel.show', compact('artikel'));
    }

    /**
     * API: Get published artikels
     */
    public function getArtikels(Request $request)
    {
        $query = Artikel::with('user')
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->latest('published_at');

        // Search functionality
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('content', 'like', '%' . $request->search . '%')
                    ->orWhere('tags', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by tags
        if ($request->filled('tag')) {
            $query->where('tags', 'like', '%' . $request->tag . '%');
        }

        // Pagination
        $perPage = $request->get('per_page', 10);
        $artikels = $query->paginate($perPage);

        // Format response untuk API
        $artikels->getCollection()->transform(function ($artikel) {
            return [
                'id' => $artikel->id,
                'title' => $artikel->title,
                'slug' => $artikel->slug,
                'excerpt' => $this->getExcerpt($artikel->content),
                'image' => $artikel->image ? asset('storage/' . $artikel->image) : null,
                'author' => $artikel->user->name ?? 'Unknown',
                'published_at' => $artikel->published_at->format('Y-m-d H:i:s'),
                'published_at_human' => $artikel->published_at->diffForHumans(),
                'reading_time' => $this->calculateReadingTime($artikel->content),
                'tags' => $artikel->tags ? explode(',', $artikel->tags) : [],
            ];
        });

        return response()->json($artikels);
    }

    /**
     * API: Show single artikel by ID or slug
     */
    public function showArtikels($identifier)
    {
        // Cek apakah identifier adalah ID (numeric) atau slug
        if (is_numeric($identifier)) {
            $artikel = Artikel::with('user')
                ->where('status', 'published')
                ->whereNotNull('published_at')
                ->findOrFail($identifier);
        } else {
            $artikel = Artikel::with('user')
                ->where('status', 'published')
                ->whereNotNull('published_at')
                ->where('slug', $identifier)
                ->firstOrFail();
        }

        // Format response
        $data = [
            'id' => $artikel->id,
            'title' => $artikel->title,
            'slug' => $artikel->slug,
            'content' => $artikel->content,
            'image' => $artikel->image ? asset('storage/' . $artikel->image) : null,
            'author' => [
                'id' => $artikel->user->id ?? null,
                'name' => $artikel->user->name ?? 'Unknown',
                'email' => $artikel->user->email ?? null,
            ],
            'meta_description' => $artikel->meta_description,
            'tags' => $artikel->tags ? explode(',', $artikel->tags) : [],
            'published_at' => $artikel->published_at->format('Y-m-d H:i:s'),
            'published_at_human' => $artikel->published_at->diffForHumans(),
            'reading_time' => $this->calculateReadingTime($artikel->content),
            'created_at' => $artikel->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $artikel->updated_at->format('Y-m-d H:i:s'),
        ];

        return response()->json($data);
    }

    /**
     * API: Get related artikels
     */
    public function getRelatedArtikels($id, Request $request)
    {
        $artikel = Artikel::where('status', 'published')
            ->whereNotNull('published_at')
            ->findOrFail($id);

        $limit = $request->get('limit', 5);

        $related = collect();

        // Cari artikel dengan tags yang sama jika ada
        if ($artikel->tags) {
            $tags = explode(',', $artikel->tags);
            $related = Artikel::with('user')
                ->where('status', 'published')
                ->whereNotNull('published_at')
                ->where('id', '!=', $id)
                ->where(function ($query) use ($tags) {
                    foreach ($tags as $tag) {
                        $query->orWhere('tags', 'like', '%' . trim($tag) . '%');
                    }
                })
                ->latest('published_at')
                ->limit($limit)
                ->get();
        }

        // Jika artikel terkait kurang dari limit, ambil artikel terbaru lainnya
        if ($related->count() < $limit) {
            $additionalCount = $limit - $related->count();
            $additional = Artikel::with('user')
                ->where('status', 'published')
                ->whereNotNull('published_at')
                ->where('id', '!=', $id)
                ->whereNotIn('id', $related->pluck('id'))
                ->latest('published_at')
                ->limit($additionalCount)
                ->get();

            $related = $related->merge($additional);
        }

        // Format response
        $related->transform(function ($artikel) {
            return [
                'id' => $artikel->id,
                'title' => $artikel->title,
                'slug' => $artikel->slug,
                'excerpt' => $this->getExcerpt($artikel->content),
                'image' => $artikel->image ? asset('storage/' . $artikel->image) : null,
                'author' => $artikel->user->name ?? 'Unknown',
                'published_at' => $artikel->published_at->format('Y-m-d H:i:s'),
                'published_at_human' => $artikel->published_at->diffForHumans(),
                'reading_time' => $this->calculateReadingTime($artikel->content),
            ];
        });

        return response()->json($related);
    }

    /**
     * API: Get popular tags
     */
    public function getPopularTags(Request $request)
    {
        $limit = $request->get('limit', 10);
        return response()->json($this->getPopularTagsArray($limit));
    }

    /**
     * API: Search artikels
     */
    public function searchArtikels(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:2',
        ]);

        $query = $request->get('q');
        $perPage = $request->get('per_page', 10);

        $artikels = Artikel::with('user')
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', '%' . $query . '%')
                    ->orWhere('content', 'like', '%' . $query . '%')
                    ->orWhere('tags', 'like', '%' . $query . '%')
                    ->orWhere('meta_description', 'like', '%' . $query . '%');
            })
            ->latest('published_at')
            ->paginate($perPage);

        // Format response
        $artikels->getCollection()->transform(function ($artikel) {
            return [
                'id' => $artikel->id,
                'title' => $artikel->title,
                'slug' => $artikel->slug,
                'excerpt' => $this->getExcerpt($artikel->content),
                'image' => $artikel->image ? asset('storage/' . $artikel->image) : null,
                'author' => $artikel->user->name ?? 'Unknown',
                'published_at' => $artikel->published_at->format('Y-m-d H:i:s'),
                'published_at_human' => $artikel->published_at->diffForHumans(),
                'reading_time' => $this->calculateReadingTime($artikel->content),
                'tags' => $artikel->tags ? explode(',', $artikel->tags) : [],
            ];
        });

        return response()->json([
            'query' => $query,
            'results' => $artikels
        ]);
    }

    /**
     * Helper: Get excerpt from content
     */
    private function getExcerpt($content, $length = 150)
    {
        return \Illuminate\Support\Str::limit(strip_tags($content), $length);
    }

    /**
     * Helper: Calculate reading time
     */
    private function calculateReadingTime($content)
    {
        $wordCount = str_word_count(strip_tags($content));
        return max(1, ceil($wordCount / 200)); // 200 words per minute
    }

    /**
     * Helper: Get popular tags as array
     */
    private function getPopularTagsArray($limit = 10)
    {
        $articles = Artikel::where('status', 'published')
            ->whereNotNull('published_at')
            ->whereNotNull('tags')
            ->where('tags', '!=', '')
            ->get();

        $tagCounts = [];
        foreach ($articles as $article) {
            $tags = explode(',', $article->tags);
            foreach ($tags as $tag) {
                $tag = trim($tag);
                if (!empty($tag)) {
                    $tagCounts[$tag] = isset($tagCounts[$tag]) ? $tagCounts[$tag] + 1 : 1;
                }
            }
        }

        // Sort by count dan ambil top tags
        arsort($tagCounts);
        return array_slice($tagCounts, 0, $limit, true);
    }
}
