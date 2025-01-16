<?php

namespace App\Http\Controllers;

use App\Models\Modul;
use App\Models\Praktikum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ModulController extends Controller
{

    public function getPraktikums()
    {
        $praktikum = Praktikum::get();

        return response()->json($praktikum);
    }
    public function getModuls()
    {
        $moduls = Modul::with('praktikum')->get();

        // return view('moduls.index', compact('moduls'));

        return response()->json($moduls);
    }

    public function downloadModul($id)
    {
        $modul = Modul::findOrFail($id);

        if ($modul && Storage::exists($modul->file_path)) {
            return Storage::download($modul->file_path, $modul->title . '.pdf');
        }

        return redirect()->back()->with('error', 'File tidak ditemukan');
    }
}