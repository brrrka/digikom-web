{{-- resources/views/admin/dashboard/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    <div class="space-y-8">
        <!-- Welcome Section -->
        <div class="bg-gradient-to-r from-primary-500 to-primary-600 rounded-2xl shadow-lg p-8 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold mb-2">Selamat Datang, {{ auth()->user()->name }}! 👋</h1>
                    <p class="text-primary-100 text-lg">Kelola sistem Digikom Lab dengan mudah dan efisien</p>
                </div>
                <div class="hidden lg:block">
                    <div class="bg-white/20 backdrop-blur-sm rounded-xl p-4">
                        <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Users Stats -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover-lift">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Users</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['users']['total'] }}</p>
                        <p class="text-sm text-green-600 mt-1">
                            +{{ $stats['users']['new_this_month'] }} bulan ini
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Praktikum Stats -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover-lift">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Praktikum</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['praktikum']['total'] }}</p>
                        <p class="text-sm text-gray-500 mt-1">
                            {{ $stats['praktikum']['total_moduls'] }} modul
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0118 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Inventaris Stats -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover-lift">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Inventaris</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['inventaris']['total'] }}</p>
                        <p class="text-sm text-green-600 mt-1">
                            {{ $stats['inventaris']['tersedia'] }} tersedia
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Peminjaman Stats -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover-lift">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Peminjaman</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['peminjaman']['total'] }}</p>
                        <p class="text-sm text-orange-600 mt-1">
                            {{ $stats['peminjaman']['pending'] }} pending
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Peminjaman Chart -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Peminjaman Per Bulan</h3>
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 bg-primary-500 rounded-full"></div>
                        <span class="text-sm text-gray-600">Peminjaman</span>
                    </div>
                </div>
                <div class="h-80">
                    <canvas id="peminjamanChart"></canvas>
                </div>
            </div>

            <!-- Status Chart -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Status Peminjaman</h3>
                    <button class="text-sm text-primary-600 hover:text-primary-700 font-medium">
                        Lihat Detail
                    </button>
                </div>
                <div class="h-80">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Recent Activities & Quick Actions -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Recent Activities -->
            <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Aktivitas Terbaru</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @forelse($recentActivities as $activity)
                            <div class="flex items-start space-x-4">
                                <div
                                    class="w-2 h-2 rounded-full {{ $activity['type'] === 'peminjaman' ? 'bg-blue-500' : 'bg-green-500' }} mt-2 flex-shrink-0">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm text-gray-900">{{ $activity['message'] }}</p>
                                    <p class="text-xs text-gray-500">{{ $activity['time'] }}</p>
                                </div>
                                @if (isset($activity['url']))
                                    <a href="{{ $activity['url'] }}"
                                        class="text-sm text-primary-600 hover:text-primary-700 font-medium">
                                        Lihat
                                    </a>
                                @endif
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                    </path>
                                </svg>
                                <p class="mt-2 text-sm text-gray-500">Belum ada aktivitas</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
                </div>
                <div class="p-6 space-y-4">
                    <a href="{{ route('admin.users.create') }}"
                        class="flex items-center p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors group">
                        <div
                            class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z">
                                </path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">Tambah User</p>
                            <p class="text-xs text-gray-500">Buat user baru</p>
                        </div>
                    </a>

                    <a href="{{ route('admin.praktikums.create') }}"
                        class="flex items-center p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors group">
                        <div
                            class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center group-hover:bg-green-200 transition-colors">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">Tambah Praktikum</p>
                            <p class="text-xs text-gray-500">Buat praktikum baru</p>
                        </div>
                    </a>

                    <a href="{{ route('admin.inventaris.create') }}"
                        class="flex items-center p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors group">
                        <div
                            class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center group-hover:bg-yellow-200 transition-colors">
                            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">Tambah Inventaris</p>
                            <p class="text-xs text-gray-500">Input barang baru</p>
                        </div>
                    </a>

                    <a href="{{ route('admin.export.all') }}"
                        class="flex items-center p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors group">
                        <div
                            class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center group-hover:bg-purple-200 transition-colors">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">Export Data</p>
                            <p class="text-xs text-gray-500">Download semua data</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Peminjaman Chart
        const peminjamanCtx = document.getElementById('peminjamanChart').getContext('2d');
        new Chart(peminjamanCtx, {
            type: 'line',
            data: {
                labels: @json(array_column($charts['peminjaman_monthly'], 'month')),
                datasets: [{
                    label: 'Peminjaman',
                    data: @json(array_column($charts['peminjaman_monthly'], 'count')),
                    borderColor: '#22c55e',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // Status Chart
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: @json(array_column($charts['peminjaman_status'], 'status')),
                datasets: [{
                    data: @json(array_column($charts['peminjaman_status'], 'count')),
                    backgroundColor: [
                        '#fbbf24',
                        '#22c55e',
                        '#3b82f6',
                        '#10b981',
                        '#ef4444'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            font: {
                                size: 12
                            }
                        }
                    }
                }
            }
        });
    </script>
@endpush
