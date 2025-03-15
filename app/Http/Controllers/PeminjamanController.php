<?php

namespace App\Http\Controllers;

use App\Models\Inventaris;
use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
                'total_dipinjam' => $item->total_dipinjam,
                'tersedia' => $item->kuantitas - $item->total_dipinjam,
                'status' => $item->status,
                'is_available' => ($item->kuantitas - $item->total_dipinjam) > 0 && $item->status == 'tersedia',
            ];
        });
        return view('pages.peminjaman.form', compact('inventaris'));
    }

    public function riwayatPeminjaman()
    {
        $peminjaman = Peminjaman::where('id_users', Auth::id())
            ->with('detailPeminjaman.inventaris')
            ->paginate(5);

        return view('pages.peminjaman.status', compact('peminjaman'));
    }

    // Di dalam method quantitySelection di PeminjamanController
    public function quantitySelection(Request $request)
    {
        $request->validate([
            'id_inventaris' => 'required|array',
            'id_inventaris.*' => 'exists:inventaris,id',
            'tanggal_peminjaman' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_peminjaman',
            'alasan' => 'required|string',
        ]);

        // Mengambil hanya inventaris yang tersedia
        $selectedItems = Inventaris::whereIn('id', $request->id_inventaris)
            ->get()
            ->map(function ($item) {
                $item->stok_tersedia = $item->kuantitas - $item->total_dipinjam;
                return $item;
            })
            ->filter(function ($item) {
                return $item->stok_tersedia > 0 && $item->status == 'tersedia';
            });

        // Jika tidak ada item tersedia, kembali ke form
        if ($selectedItems->isEmpty()) {
            return redirect()->route('peminjaman.form')
                ->with('error', 'Tidak ada barang yang tersedia untuk dipinjam');
        }

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

        // Filter out items with zero quantity
        $items = [];
        foreach ($request->id_inventaris as $inventarisId) {
            $kuantitas = $request->kuantitas[$inventarisId] ?? 0;
            if ($kuantitas > 0) {
                $items[$inventarisId] = $kuantitas;
            }
        }

        if (empty($items)) {
            return redirect()->route('peminjaman.form')
                ->with('error', 'Tidak ada barang yang dipilih dengan kuantitas valid.');
        }

        // Validate all items are available in the requested quantities
        $inventarisItems = Inventaris::whereIn('id', array_keys($items))->get();
        foreach ($inventarisItems as $item) {
            $requestedQuantity = $items[$item->id];
            $availableQuantity = $item->kuantitas - $item->total_dipinjam;

            if ($availableQuantity < $requestedQuantity || $item->status != 'tersedia') {
                return redirect()->route('peminjaman.form')
                    ->with('error', "Barang '{$item->nama}' tidak tersedia dalam jumlah yang diminta.");
            }
        }

        // Calculate loan duration
        $tanggalPinjam = Carbon::parse($request->tanggal_peminjaman);
        $tanggalSelesai = Carbon::parse($request->tanggal_selesai);
        $selisihHari = $tanggalPinjam->diffInDays($tanggalSelesai);
        $jangka = $selisihHari <= 14 ? 'pendek' : 'panjang';

        // Begin transaction
        DB::beginTransaction();

        try {
            // Create the main peminjaman record
            $peminjaman = Peminjaman::create([
                'id_users' => Auth::id(),
                'tanggal_peminjaman' => $request->tanggal_peminjaman,
                'tanggal_selesai' => $request->tanggal_selesai,
                'alasan' => $request->alasan,
                'jangka' => $jangka,
                'status' => 'diajukan',
            ]);

            // Create detail records for each item
            foreach ($items as $inventarisId => $kuantitas) {
                DetailPeminjaman::create([
                    'id_peminjaman' => $peminjaman->id,
                    'id_inventaris' => $inventarisId,
                    'kuantitas' => $kuantitas,
                ]);
            }

            DB::commit();

            return redirect()->route('peminjaman')
                ->with('success', 'Peminjaman berhasil diajukan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('peminjaman.form')
                ->with('error', 'Terjadi kesalahan saat mengajukan peminjaman: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        // Check if the logged-in user owns this peminjaman record
        $peminjaman = Peminjaman::with('detailPeminjaman.inventaris')
            ->findOrFail($id);

        // Check if the current user is the owner of this peminjaman
        if ($peminjaman->id_users !== Auth::id()) {
            return redirect()->route('peminjaman')
                ->with('error', 'Anda tidak memiliki akses ke data peminjaman ini.');
        }

        return view('pages.peminjaman.detail', compact('peminjaman'));
    }

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

    // Add this method to update inventory quantities when a loan is approved
    public function updatePeminjamanStatus(Request $request, $id)
    {
        // This method would be called by an admin or authorized user
        $request->validate([
            'status' => 'required|in:disetujui,ditolak,dikembalikan',
        ]);

        $peminjaman = Peminjaman::with('detailPeminjaman.inventaris')->findOrFail($id);
        $oldStatus = $peminjaman->status;
        $newStatus = $request->status;

        DB::beginTransaction();

        try {
            // Update the peminjaman status
            $peminjaman->status = $newStatus;
            $peminjaman->save();

            // If peminjaman is approved, update inventory quantities
            if ($newStatus == 'disetujui' && $oldStatus != 'disetujui') {
                foreach ($peminjaman->detailPeminjaman as $detail) {
                    $inventaris = $detail->inventaris;
                    $inventaris->total_dipinjam += $detail->kuantitas;

                    // Update status if all items are borrowed
                    if ($inventaris->kuantitas <= $inventaris->total_dipinjam) {
                        $inventaris->status = 'tidak tersedia';
                    }

                    $inventaris->save();
                }
            }

            // If items are returned, update inventory quantities
            if ($newStatus == 'dikembalikan' && $oldStatus == 'disetujui') {
                foreach ($peminjaman->detailPeminjaman as $detail) {
                    $inventaris = $detail->inventaris;
                    $inventaris->total_dipinjam -= $detail->kuantitas;

                    // Make sure total_dipinjam doesn't go below 0
                    if ($inventaris->total_dipinjam < 0) {
                        $inventaris->total_dipinjam = 0;
                    }

                    // Update status back to available
                    if ($inventaris->total_dipinjam < $inventaris->kuantitas) {
                        $inventaris->status = 'tersedia';
                    }

                    $inventaris->save();
                }
            }

            DB::commit();

            return redirect()->back()->with('success', 'Status peminjaman berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui status: ' . $e->getMessage());
        }
    }
}
