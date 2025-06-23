<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\User;
use App\Models\Inventaris;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class PeminjamanController extends Controller
{
    public function index(Request $request)
    {
        $query = Peminjaman::with(['user', 'detailPeminjaman.inventaris']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('id_users', $request->user_id);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('tanggal_peminjaman', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('tanggal_peminjaman', '<=', $request->date_to);
        }

        // Search functionality
        if ($request->filled('search')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('nim', 'like', '%' . $request->search . '%');
            });
        }

        $peminjaman = $query->latest()->paginate(15);

        // Stats for dashboard cards
        $stats = [
            'total' => Peminjaman::count(),
            'pending' => Peminjaman::where('status', 'diajukan')->count(),
            'approved' => Peminjaman::where('status', 'disetujui')->count(),
            'active' => Peminjaman::where('status', 'dipinjam')->count(),
            'overdue' => Peminjaman::where('status', 'jatuh tenggat')->count(),
            'returned' => Peminjaman::where('status', 'dikembalikan')->count(),
        ];

        $users = User::orderBy('name')->get();

        return view('admin.peminjaman.index', compact('peminjaman', 'stats', 'users'));
    }

    public function create()
    {
        $users = User::orderBy('name')->get();
        $inventaris = Inventaris::where('status', 'tersedia')->where('kuantitas', '>', 0)->get();

        return view('admin.peminjaman.create', compact('users', 'inventaris'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_users' => 'required|exists:users,id',
            'tanggal_peminjaman' => 'required|date|after_or_equal:today',
            'tanggal_selesai' => 'required|date|after:tanggal_peminjaman',
            'alasan' => 'required|string',
            'items' => 'required|array|min:1',
            'items.*.id_inventaris' => 'required|exists:inventaris,id',
            'items.*.kuantitas' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            // Check item availability
            foreach ($validated['items'] as $item) {
                $inventaris = Inventaris::find($item['id_inventaris']);
                if ($inventaris->kuantitas < $item['kuantitas']) {
                    throw new \Exception("Kuantitas {$inventaris->nama} tidak mencukupi");
                }
            }

            // Calculate jangka waktu
            $startDate = Carbon::parse($validated['tanggal_peminjaman']);
            $endDate = Carbon::parse($validated['tanggal_selesai']);
            $daysDiff = $startDate->diffInDays($endDate);
            $jangka = $daysDiff <= 7 ? 'pendek' : 'panjang';

            // Create peminjaman
            $peminjaman = Peminjaman::create([
                'id_users' => $validated['id_users'],
                'tanggal_peminjaman' => $validated['tanggal_peminjaman'],
                'tanggal_selesai' => $validated['tanggal_selesai'],
                'alasan' => $validated['alasan'],
                'jangka' => $jangka,
                'status' => 'disetujui', // Admin langsung setujui
            ]);

            // Create detail peminjaman
            foreach ($validated['items'] as $item) {
                $peminjaman->detailPeminjaman()->create([
                    'id_inventaris' => $item['id_inventaris'],
                    'kuantitas' => $item['kuantitas'],
                ]);
            }

            DB::commit();

            return redirect()->route('admin.peminjaman.index')
                ->with('success', 'Peminjaman berhasil dibuat!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Error: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(Peminjaman $peminjaman)
    {
        $peminjaman->load(['user', 'detailPeminjaman.inventaris']);
        return view('admin.peminjaman.show', compact('peminjaman'));
    }

    public function edit(Peminjaman $peminjaman)
    {
        if (in_array($peminjaman->status, ['dikembalikan', 'ditolak'])) {
            return redirect()->route('admin.peminjaman.index')
                ->with('error', 'Tidak dapat mengedit peminjaman yang sudah selesai!');
        }

        $peminjaman->load(['detailPeminjaman.inventaris']);
        $users = User::orderBy('name')->get();
        $inventaris = Inventaris::where('status', 'tersedia')->get();

        return view('admin.peminjaman.edit', compact('peminjaman', 'users', 'inventaris'));
    }

    public function update(Request $request, Peminjaman $peminjaman)
    {
        $validated = $request->validate([
            'tanggal_selesai' => 'required|date|after:tanggal_peminjaman',
            'alasan' => 'required|string',
            'catatan' => 'nullable|string',
        ]);

        // Calculate jangka waktu
        $startDate = Carbon::parse($peminjaman->tanggal_peminjaman);
        $endDate = Carbon::parse($validated['tanggal_selesai']);
        $daysDiff = $startDate->diffInDays($endDate);
        $validated['jangka'] = $daysDiff <= 7 ? 'pendek' : 'panjang';

        $peminjaman->update($validated);

        return redirect()->route('admin.peminjaman.index')
            ->with('success', 'Peminjaman berhasil diupdate!');
    }

    public function destroy(Peminjaman $peminjaman)
    {
        if (!in_array($peminjaman->status, ['diajukan', 'ditolak'])) {
            return redirect()->route('admin.peminjaman.index')
                ->with('error', 'Hanya dapat menghapus peminjaman yang berstatus diajukan atau ditolak!');
        }

        $peminjaman->detailPeminjaman()->delete();
        $peminjaman->delete();

        return redirect()->route('admin.peminjaman.index')
            ->with('success', 'Peminjaman berhasil dihapus!');
    }

    public function updateStatus(Request $request, Peminjaman $peminjaman)
    {
        $validated = $request->validate([
            'status' => 'required|in:diajukan,disetujui,ditolak,dipinjam,jatuh tenggat,dikembalikan',
            'catatan' => 'nullable|string',
            'tanggal_pengembalian' => 'nullable|date',
        ]);

        $oldStatus = $peminjaman->status;

        // Status transition validation
        $allowedTransitions = [
            'diajukan' => ['disetujui', 'ditolak'],
            'disetujui' => ['dipinjam', 'ditolak'],
            'dipinjam' => ['dikembalikan', 'jatuh tenggat'],
            'jatuh tenggat' => ['dikembalikan'],
        ];

        if (
            isset($allowedTransitions[$oldStatus]) &&
            !in_array($validated['status'], $allowedTransitions[$oldStatus])
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Transisi status tidak valid'
            ], 400);
        }

        // Set tanggal pengembalian if status is dikembalikan
        if ($validated['status'] === 'dikembalikan') {
            $validated['tanggal_pengembalian'] = $validated['tanggal_pengembalian'] ?? now();
        }

        $peminjaman->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Status peminjaman berhasil diupdate',
            'new_status' => $validated['status']
        ]);
    }

    public function exportPdf(Peminjaman $peminjaman)
    {
        $peminjaman->load(['user', 'detailPeminjaman.inventaris']);

        $pdf = PDF::loadView('admin.peminjaman.pdf', compact('peminjaman'));

        return $pdf->download('peminjaman-' . $peminjaman->id . '.pdf');
    }

    public function exportAll(Request $request)
    {
        $query = Peminjaman::with(['user', 'detailPeminjaman.inventaris']);

        // Apply same filters as index
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('tanggal_peminjaman', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('tanggal_peminjaman', '<=', $request->date_to);
        }

        $peminjaman = $query->get();

        $pdf = PDF::loadView('admin.peminjaman.export-all', compact('peminjaman'));

        return $pdf->download('laporan-peminjaman-' . now()->format('Y-m-d') . '.pdf');
    }

    public function checkOverdue()
    {
        $overduePeminjaman = Peminjaman::where('status', 'dipinjam')
            ->where('tanggal_selesai', '<', now())
            ->get();

        foreach ($overduePeminjaman as $peminjaman) {
            $peminjaman->update(['status' => 'jatuh tenggat']);
        }

        return response()->json([
            'success' => true,
            'message' => $overduePeminjaman->count() . ' peminjaman diupdate ke status jatuh tenggat'
        ]);
    }
}
