<?php

namespace App\Http\Controllers;

use App\Models\Modul;
use App\Models\Praktikum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ModulController extends Controller
{
    public function getModulsByPraktikum($slug)
    {
        $praktikum = Praktikum::where('slug', $slug)->firstOrFail();
        $moduls = Modul::where('id_praktikums', $praktikum->id)
            ->orderBy('modul_ke', 'asc')
            ->get();

        return view('pages.praktikum.modul', compact('moduls', 'praktikum'));
    }


    public function downloadModul($id)
    {
        $modul = Modul::findOrFail($id);

        $fullPath = public_path('' . $modul->file_path);

        if (file_exists($fullPath)) {
            return response()->download($fullPath, $modul->title . '.pdf');
        }

        return redirect()->back()->with('error', 'File tidak ditemukan');
    }
}
