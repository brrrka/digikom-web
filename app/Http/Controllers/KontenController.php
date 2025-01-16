<?php

namespace App\Http\Controllers;

use App\Models\Konten;
use Illuminate\Http\Request;

class KontenController extends Controller
    {
    public function getKontens()
    {
        $konten = Konten::all();

        return response()->json($konten);
    }
}