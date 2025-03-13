<?php

namespace App\Http\Controllers;

use App\Models\Inventaris;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
        return view('pages.peminjaman.form', compact('inventaris'));
    }

    public function riwayatPeminjaman()
    {
        $peminjaman = Peminjaman::where('id_users', Auth::id())->with('inventaris')->Paginate(5);
        return view('pages.peminjaman.status', compact('peminjaman'));
    }

    public function quantitySelection(Request $request)
    {
        $request->validate([
            'id_inventaris' => 'required|array',
            'id_inventaris.*' => 'exists:inventaris,id',
            'tanggal_peminjaman' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_peminjaman',
            'alasan' => 'required|string',
        ]);

        $selectedItems = Inventaris::whereIn('id', $request->id_inventaris)->get();

        // Pass the form data to the next page
        $tanggal_peminjaman = $request->tanggal_peminjaman;
        $tanggal_selesai = $request->tanggal_selesai;
        $alasan = $request->alasan;

        return view('pages.peminjaman.quantity', compact('selectedItems', 'tanggal_peminjaman', 'tanggal_selesai', 'alasan'));
    }

    public function confirmPeminjaman(Request $request)
    {
        $request->validate([
            'id_inventaris' => 'required|array',
            'id_inventaris.*' => 'exists:inventaris,id',
            'kuantitas' => 'required|array',
            'tanggal_peminjaman' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_peminjaman',
            'alasan' => 'required|string',
            'jangka'
        ]);

        $validInventarisIds = [];
        $quantities = [];

        foreach ($request->id_inventaris as $inventarisId) {
            $quantity = $request->kuantitas[$inventarisId] ?? 0;
            if ($quantity > 0) {
                $validInventarisIds[] = $inventarisId;
                $quantities[$inventarisId] = $quantity;
            }
        }

        if (empty($validInventarisIds)) {
            return redirect()->route('peminjaman.form')->with('error', 'Tidak ada barang yang dipilih');
        }

        $selectedItems = Inventaris::whereIn('id', $validInventarisIds)->get();

        $tanggal_peminjaman = $request->tanggal_peminjaman;
        $tanggal_selesai = $request->tanggal_selesai;
        $alasan = $request->alasan;

        return view('pages.peminjaman.confirm', compact(
            'selectedItems',
            'quantities',
            'tanggal_peminjaman',
            'tanggal_selesai',
            'alasan'
        ));
    }

    // Update the store method
    public function storePeminjaman(Request $request)
    {
        $request->validate([
            'id_inventaris' => 'required|array',
            'id_inventaris.*' => 'exists:inventaris,id',
            'kuantitas' => 'required|array',
            'tanggal_peminjaman' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_peminjaman',
            'alasan' => 'required|string',
        ]);

        $successCount = 0;

        foreach ($request->id_inventaris as $inventarisId) {
            $kuantitas = $request->kuantitas[$inventarisId] ?? 0;

            if ($kuantitas <= 0) {
                continue;
            }

            $inventaris = Inventaris::find($inventarisId);

            if ($inventaris && $inventaris->kuantitas >= $kuantitas && $inventaris->status == 'tersedia') {
                // Hitung selisih hari
                $tanggalPinjam = \Carbon\Carbon::parse($request->tanggal_peminjaman);
                $tanggalSelesai = \Carbon\Carbon::parse($request->tanggal_selesai);
                $selisihHari = $tanggalPinjam->diffInDays($tanggalSelesai);

                // Tentukan jangka peminjaman
                $jangka = $selisihHari <= 14 ? 'pendek' : 'panjang';

                Peminjaman::create([
                    'id_users' => Auth::id(),
                    'id_inventaris' => $inventarisId,
                    'kuantitas' => $kuantitas,
                    'tanggal_peminjaman' => $request->tanggal_peminjaman,
                    'tanggal_selesai' => $request->tanggal_selesai,
                    'alasan' => $request->alasan,
                    'status' => 'diajukan',
                    'jangka' => $jangka, // Tambahkan jangka
                ]);

                $successCount++;
            }
        }

        if ($successCount > 0) {
            return redirect()->route('peminjaman')->with('success', 'Peminjaman berhasil diajukan!');
        } else {
            return redirect()->route('peminjaman.form')->with('error', 'Tidak ada barang yang dipinjam atau kuantitas tidak valid.');
        }
    }


    public function show($id)
    {
        // Check if the logged-in user owns this peminjaman record
        $peminjaman = Peminjaman::with('inventaris')->findOrFail($id);

        // Check if the current user is the owner of this peminjaman
        if ($peminjaman->id_users !== Auth::id()) {
            return redirect()->route('peminjaman')
                ->with('error', 'Anda tidak memiliki akses ke data peminjaman ini.');
        }

        return view('pages.peminjaman.detail', compact('peminjaman'));
    }

    // You should also implement the download method for the peminjaman proof
    public function download($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        // Check if the current user is the owner of this peminjaman
        if ($peminjaman->id_users !== Auth::id()) {
            return redirect()->route('peminjaman')
                ->with('error', 'Anda tidak memiliki akses ke data peminjaman ini.');
        }

        // Check if the peminjaman is approved
        if ($peminjaman->status !== 'disetujui') {
            return redirect()->route('peminjaman.show', $peminjaman->id)
                ->with('error', 'Bukti peminjaman hanya tersedia untuk peminjaman yang disetujui.');
        }

        // Check if bukti_path exists
        if (!$peminjaman->bukti_path || !Storage::disk('public')->exists($peminjaman->bukti_path)) {
            return redirect()->route('peminjaman.show', $peminjaman->id)
                ->with('error', 'Bukti peminjaman tidak tersedia.');
        }

        // Download the file
        return Storage::disk('public')->download(
            $peminjaman->bukti_path,
            'Surat_Peminjaman_P00' . $peminjaman->id . '.docx'
        );
    }
}
