<?php

namespace App\Http\Controllers;

use App\Models\Inventaris;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeminjamanController extends Controller
{
    public function index()
    {
        return view('pages.peminjaman.index');
    }

    public function startPinjam()
    {
        return view('pages.peminjaman.start');
    }

    public function formPinjam()
    {
        return view('pages.peminjaman.form');
    }

    public function riwayatPeminjaman()
    {
        $peminjaman = Peminjaman::where('id_users', Auth::id())->with('inventaris')->get();

        return $peminjaman;
    }

    public function createPeminjaman()
    {
        $inventaris = Inventaris::all()->map(function ($item) {
            return [
                'id' => $item->id,
                'nama' => $item->nama,
                'deskripsi' => $item->deskripsi,
                'image' => $item->images,
                'kuantitas' => $item->kuantitas,
                'status' => $item->status,
                'is_available' => $item->kuantitas > 0 && $item->status == 'tersedia',
            ];
        });

        return response()->json($inventaris);
    }

    public function storePeminjaman(Request $request)
    {
        $request->validate([
            'id_inventaris' => 'required|exists:inventaris,id',
            'kuantitas' => 'required|integer|min:1',
            'tanggal_peminjaman' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_peminjaman',
        ]);

        $inventaris = Inventaris::find($request->id_inventaris);

        if ($request->kuantitas > $inventaris->kuantitas) {
            return response()->json([
                'message' => 'Kuantitas peminjaman melebihi kuantitas inventaris'
            ], 400);
        }

        Peminjaman::create([
            'id_users' => Auth::id(),
            'id_inventaris' => $request->id_inventaris,
            'kuantitas' => $request->kuantitas,
            'tanggal_peminjaman' => $request->tanggal_peminjaman,
            'tanggal_selesai' => $request->tanggal_selesai,
            'status' => 'diajukan',
        ]);

        return response()->json([
            'message' => 'Peminjaman berhasil diajukan'
        ], 201);
    }

    public function show($id) {}
}
