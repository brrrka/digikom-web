<?php
// app/Http/Controllers/Admin/DashboardController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Praktikum;
use App\Models\Modul;
use App\Models\Inventaris;
use App\Models\Peminjaman;
use App\Models\Artikel;
use App\Exports\ExportAllData;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = $this->getDashboardStats();
        $charts = $this->getChartData();
        $recentActivities = $this->getRecentActivities();

        return view('admin.dashboard.index', compact('stats', 'charts', 'recentActivities'));
    }

    private function getDashboardStats()
    {
        return [
            'users' => [
                'total' => User::count(),
                'new_this_month' => User::whereMonth('created_at', now()->month)->count(),
                'asisten' => User::where('is_asisten', true)->count(),
            ],
            'praktikum' => [
                'total' => Praktikum::count(),
                'total_moduls' => Modul::count(),
            ],
            'inventaris' => [
                'total' => Inventaris::count(),
                'tersedia' => Inventaris::where('status', 'tersedia')->count(),
                'tidak_tersedia' => Inventaris::where('status', 'tidak tersedia')->count(),
            ],
            'peminjaman' => [
                'total' => Peminjaman::count(),
                'pending' => Peminjaman::where('status', 'diajukan')->count(),
                'active' => Peminjaman::whereIn('status', ['disetujui', 'dipinjam'])->count(),
                'overdue' => Peminjaman::where('status', 'jatuh tenggat')->count(),
                'this_month' => Peminjaman::whereMonth('created_at', now()->month)->count(),
            ],
            'artikel' => [
                'total' => Artikel::count(),
                'published' => Artikel::where('status', 'published')->count(),
                'draft' => Artikel::where('status', 'draft')->count(),
            ]
        ];
    }

    private function getChartData()
    {
        // Data untuk chart peminjaman per bulan (6 bulan terakhir)
        $peminjamanChart = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $peminjamanChart[] = [
                'month' => $date->format('M Y'),
                'count' => Peminjaman::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count()
            ];
        }

        // Data untuk chart status peminjaman
        $statusChart = [
            ['status' => 'Diajukan', 'count' => Peminjaman::where('status', 'diajukan')->count()],
            ['status' => 'Disetujui', 'count' => Peminjaman::where('status', 'disetujui')->count()],
            ['status' => 'Dipinjam', 'count' => Peminjaman::where('status', 'dipinjam')->count()],
            ['status' => 'Dikembalikan', 'count' => Peminjaman::where('status', 'dikembalikan')->count()],
            ['status' => 'Jatuh Tenggat', 'count' => Peminjaman::where('status', 'jatuh tenggat')->count()],
        ];

        return [
            'peminjaman_monthly' => $peminjamanChart,
            'peminjaman_status' => $statusChart,
        ];
    }

    private function getRecentActivities()
    {
        $recentPeminjaman = Peminjaman::with('user')
            ->latest()
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'type' => 'peminjaman',
                    'message' => "Peminjaman baru dari {$item->user->name}",
                    'time' => $item->created_at->diffForHumans(),
                    'status' => $item->status,
                    'url' => route('admin.peminjaman.show', $item->id)
                ];
            });

        $recentUsers = User::latest()
            ->limit(3)
            ->get()
            ->map(function ($item) {
                return [
                    'type' => 'user',
                    'message' => "User baru: {$item->name}",
                    'time' => $item->created_at->diffForHumans(),
                    'status' => 'new',
                    'url' => route('admin.users.show', $item->id)
                ];
            });

        return $recentPeminjaman->concat($recentUsers)
            ->sortByDesc('time')
            ->take(8);
    }

    public function exportAllData()
    {
        return Excel::download(new ExportAllData, 'digikom_all_data_' . now()->format('Y-m-d') . '.xlsx');
    }
}
