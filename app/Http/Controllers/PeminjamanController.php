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

    // PERBAIKAN: Method formPinjam dengan data real-time dan error handling
    public function formPinjam()
    {
        try {
            $inventarisItems = Inventaris::all();
            $inventaris = collect();

            foreach ($inventarisItems as $item) {
                $stockInfo = Inventaris::getStockInfo($item->id);

                if ($stockInfo && $stockInfo['is_available']) {
                    $inventaris->push([
                        'id' => $stockInfo['id'],
                        'nama' => $stockInfo['nama'],
                        'deskripsi' => $item->deskripsi,
                        'image' => $item->images,
                        'kuantitas' => $stockInfo['kuantitas'],
                        'tersedia' => $stockInfo['tersedia'],
                        'status' => $stockInfo['status'],
                        'is_available' => $stockInfo['is_available'],
                    ]);
                }
            }


            return view('pages.peminjaman.form', compact('inventaris'));
        } catch (\Exception $e) {

            return redirect()->route('peminjaman')
                ->with('error', 'Terjadi kesalahan saat memuat form peminjaman. Silakan coba lagi.');
        }
    }

    // PERBAIKAN: Method confirmPeminjaman dengan validasi dan error handling yang lebih baik
    public function confirmPeminjaman(Request $request)
    {
        $request->validate([
            'id_inventaris' => 'required|array',
            'id_inventaris.*' => 'exists:inventaris,id',
            'kuantitas' => 'required|array',
            'tanggal_peminjaman' => 'required|date|after_or_equal:today',
            'tanggal_selesai' => 'required|date|after:tanggal_peminjaman',
            'alasan' => 'required|string|min:10',
        ]);

        $validInventarisIds = [];
        $quantities = [];
        $errors = [];

        foreach ($request->id_inventaris as $inventarisId) {
            $quantity = $request->kuantitas[$inventarisId] ?? 0;

            if ($quantity > 0) {
                $stockInfo = Inventaris::getStockInfo($inventarisId);

                if (!$stockInfo || !$stockInfo['is_available']) {
                    $errors[] = "'{$stockInfo['nama']}' sudah tidak tersedia";
                    continue;
                }

                if ($stockInfo['tersedia'] < $quantity) {
                    $errors[] = "'{$stockInfo['nama']}' stok tidak mencukupi. Tersedia: {$stockInfo['tersedia']}, diminta: {$quantity}";
                    continue;
                }

                $validInventarisIds[] = $inventarisId;
                $quantities[$inventarisId] = $quantity;
            }
        }

        if (!empty($errors)) {
            return redirect()->route('peminjaman.form')
                ->with('error', implode('. ', $errors));
        }

        if (empty($validInventarisIds)) {
            return redirect()->route('peminjaman.form')
                ->with('error', 'Tidak ada barang yang dipilih dengan kuantitas valid');
        }

        try {
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
        } catch (\Exception $e) {

            return redirect()->route('peminjaman.form')
                ->with('error', 'Terjadi kesalahan saat memproses data. Silakan coba lagi.');
        }
    }

    public function riwayatPeminjaman()
    {
        $peminjaman = Peminjaman::where('id_users', Auth::id())
            ->with('detailPeminjaman.inventaris')
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return view('pages.peminjaman.status', compact('peminjaman'));
    }

    public function quantitySelection(Request $request)
    {
        $request->validate([
            'id_inventaris' => 'required|array',
            'id_inventaris.*' => 'exists:inventaris,id',
            'tanggal_peminjaman' => 'required|date|after_or_equal:today',
            'tanggal_selesai' => 'required|date|after:tanggal_peminjaman',
            'alasan' => 'required|string|min:10',
        ]);

        // Simpan data ke session untuk backup
        $request->session()->put('peminjaman_data', [
            'id_inventaris' => $request->id_inventaris,
            'tanggal_peminjaman' => $request->tanggal_peminjaman,
            'tanggal_selesai' => $request->tanggal_selesai,
            'alasan' => $request->alasan
        ]);

        $selectedItems = collect();
        $unavailableItems = [];

        foreach ($request->id_inventaris as $inventarisId) {
            $stockInfo = Inventaris::getStockInfo($inventarisId);

            if (!$stockInfo || !$stockInfo['is_available']) {
                $unavailableItems[] = $stockInfo['nama'] ?? "Item ID {$inventarisId}";
                continue;
            }

            // PERBAIKAN: Buat object baru dengan data yang diperlukan tanpa mengubah model asli
            $item = (object) [
                'id' => $stockInfo['id'],
                'nama' => $stockInfo['nama'],
                'deskripsi' => Inventaris::find($inventarisId)->deskripsi ?? '',
                'kuantitas' => $stockInfo['kuantitas'],
                'tersedia' => $stockInfo['tersedia'],
                'status' => $stockInfo['status'],
                'total_dipinjam' => $stockInfo['total_dipinjam']
            ];

            $selectedItems->push($item);
        }

        if (!empty($unavailableItems)) {
            return redirect()->route('peminjaman.form')
                ->with('error', "Barang berikut tidak tersedia: " . implode(', ', $unavailableItems));
        }

        if ($selectedItems->isEmpty()) {
            return redirect()->route('peminjaman.form')
                ->with('error', "Tidak ada barang yang tersedia untuk dipinjam.");
        }

        $tanggal_peminjaman = $request->tanggal_peminjaman;
        $tanggal_selesai = $request->tanggal_selesai;
        $alasan = $request->alasan;

        return view('pages.peminjaman.quantity', compact(
            'selectedItems',
            'tanggal_peminjaman',
            'tanggal_selesai',
            'alasan'
        ));
    }

    // PERBAIKAN: Method storePeminjaman dengan validasi dan penanganan yang lebih baik
    public function storePeminjaman(Request $request)
    {
        $request->validate([
            'id_inventaris' => 'required|array',
            'id_inventaris.*' => 'exists:inventaris,id',
            'kuantitas' => 'required|array',
            'tanggal_peminjaman' => 'required|date|after_or_equal:today',
            'tanggal_selesai' => 'required|date|after:tanggal_peminjaman',
            'alasan' => 'required|string|min:10',
        ]);

        $items = [];
        $errors = [];

        // Validasi dan kumpulkan item yang akan dipinjam
        foreach ($request->id_inventaris as $inventarisId) {
            $kuantitas = $request->kuantitas[$inventarisId] ?? 0;

            if ($kuantitas > 0) {
                // Triple-check dengan data real-time sebelum menyimpan
                $stockInfo = Inventaris::getStockInfo($inventarisId);

                if (!$stockInfo['is_available']) {
                    $errors[] = "'{$stockInfo['nama']}' sudah tidak tersedia";
                    continue;
                }

                if ($stockInfo['tersedia'] < $kuantitas) {
                    $errors[] = "'{$stockInfo['nama']}' stok tidak mencukupi. Tersedia: {$stockInfo['tersedia']}, diminta: {$kuantitas}";
                    continue;
                }

                $items[$inventarisId] = $kuantitas;
            }
        }

        if (!empty($errors)) {
            return redirect()->route('peminjaman.form')
                ->with('error', implode('. ', $errors));
        }

        if (empty($items)) {
            return redirect()->route('peminjaman.form')
                ->with('error', 'Tidak ada barang yang dipilih dengan kuantitas valid.');
        }

        // Hitung jangka waktu
        $tanggalPinjam = Carbon::parse($request->tanggal_peminjaman);
        $tanggalSelesai = Carbon::parse($request->tanggal_selesai);
        $selisihHari = $tanggalPinjam->diffInDays($tanggalSelesai);
        $jangka = $selisihHari <= 14 ? 'pendek' : 'panjang';

        DB::beginTransaction();
        try {
            // Final validation dengan lock untuk mencegah race condition
            foreach ($items as $inventarisId => $kuantitas) {
                $inventaris = Inventaris::lockForUpdate()->find($inventarisId);
                $inventaris->validateForLoan($kuantitas);
            }

            // Buat peminjaman
            $peminjaman = Peminjaman::create([
                'id_users' => Auth::id(),
                'tanggal_peminjaman' => $request->tanggal_peminjaman,
                'tanggal_selesai' => $request->tanggal_selesai,
                'alasan' => $request->alasan,
                'jangka' => $jangka,
                'status' => 'diajukan', // Status awal selalu 'diajukan' dari user
            ]);

            // Buat detail peminjaman
            foreach ($items as $inventarisId => $kuantitas) {
                DetailPeminjaman::create([
                    'id_peminjaman' => $peminjaman->id,
                    'id_inventaris' => $inventarisId,
                    'kuantitas' => $kuantitas,
                ]);
            }

            DB::commit();


            return redirect()->route('peminjaman')
                ->with('success', 'Peminjaman berhasil diajukan! Silakan tunggu persetujuan dari admin.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('peminjaman.form')
                ->with('error', 'Terjadi kesalahan saat mengajukan peminjaman: ' . $e->getMessage());
        }
    }

    // PERBAIKAN: Method checkAvailability dengan data real-time
    public function checkAvailability(Request $request)
    {
        $inventarisIds = $request->input('ids', []);

        if (empty($inventarisIds)) {
            return response()->json(['error' => 'No items specified'], 400);
        }

        try {
            $items = collect($inventarisIds)->map(function ($id) {
                return Inventaris::getStockInfo($id);
            })->filter();

            return response()->json($items->values());
        } catch (\Exception $e) {

            return response()->json([
                'error' => 'Terjadi kesalahan saat mengecek ketersediaan'
            ], 500);
        }
    }

    public function showQuantityForm(Request $request)
    {
        if (!$request->session()->has('peminjaman_data')) {
            return redirect()->route('peminjaman.form')
                ->with('error', 'Silakan pilih barang terlebih dahulu.');
        }

        $data = $request->session()->get('peminjaman_data');

        $selectedItems = collect();
        $unavailableItems = [];

        foreach ($data['id_inventaris'] as $inventarisId) {
            $stockInfo = Inventaris::getStockInfo($inventarisId);

            if (!$stockInfo || !$stockInfo['is_available']) {
                $unavailableItems[] = $stockInfo['nama'] ?? "Item ID {$inventarisId}";
                continue;
            }

            // PERBAIKAN: Sama seperti di atas, buat object baru
            $item = (object) [
                'id' => $stockInfo['id'],
                'nama' => $stockInfo['nama'],
                'deskripsi' => Inventaris::find($inventarisId)->deskripsi ?? '',
                'kuantitas' => $stockInfo['kuantitas'],
                'tersedia' => $stockInfo['tersedia'],
                'status' => $stockInfo['status'],
                'total_dipinjam' => $stockInfo['total_dipinjam']
            ];

            $selectedItems->push($item);
        }

        if (!empty($unavailableItems)) {
            $request->session()->forget('peminjaman_data');
            return redirect()->route('peminjaman.form')
                ->with('error', "Barang berikut sudah tidak tersedia: " . implode(', ', $unavailableItems));
        }

        return view('pages.peminjaman.quantity', [
            'selectedItems' => $selectedItems,
            'tanggal_peminjaman' => $data['tanggal_peminjaman'],
            'tanggal_selesai' => $data['tanggal_selesai'],
            'alasan' => $data['alasan']
        ]);
    }

    public function show($id)
    {
        $peminjaman = Peminjaman::with('detailPeminjaman.inventaris')
            ->findOrFail($id);

        if ($peminjaman->id_users !== Auth::id()) {
            return redirect()->route('peminjaman')
                ->with('error', 'Anda tidak memiliki akses ke data peminjaman ini.');
        }

        return view('pages.peminjaman.detail', compact('peminjaman'));
    }

    public function download($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        if ($peminjaman->id_users !== Auth::id()) {
            return redirect()->route('peminjaman')
                ->with('error', 'Anda tidak memiliki akses ke data peminjaman ini.');
        }

        if ($peminjaman->status !== 'disetujui') {
            return redirect()->route('peminjaman.show', $peminjaman->id)
                ->with('error', 'Bukti peminjaman hanya tersedia untuk peminjaman yang disetujui.');
        }

        if (!$peminjaman->bukti_path || !Storage::disk('public')->exists($peminjaman->bukti_path)) {
            return redirect()->route('peminjaman.show', $peminjaman->id)
                ->with('error', 'Bukti peminjaman tidak tersedia. Silakan hubungi admin.');
        }

        return Storage::disk('public')->download(
            $peminjaman->bukti_path,
            'Surat_Peminjaman_PD-' . $peminjaman->id . '.docx'
        );
    }
}
