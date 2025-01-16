<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use Illuminate\Http\Request;

class ArtikelController extends Controller
{
    public function getArtikels()
    {
        $artikels = Artikel::where('status', 'published')->orderBy('created_at', 'desc')->get();
        return response()->json($artikels);
    }

    public function showArtikels($id)
    {
        $artikel = Artikel::findOrFail($id);
        return response()->json($artikel);
    }
}
