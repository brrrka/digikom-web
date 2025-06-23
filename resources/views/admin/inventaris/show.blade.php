{{-- resources/views/admin/inventaris/show.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Detail Inventaris')
@section('page-title', 'Detail Inventaris')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">{{ $inventaris->nama }}</h2>
                        <p class="text-sm text-gray-600 mt-1">Detail lengkap inventaris</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('admin.inventaris.edit', $inventaris->id) }}"
                            class="inline-flex items-center px-3 py-2 text-sm font-medium text-blue-700 bg-blue-50 border border-blue-200 rounded-md hover:bg-blue-100 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                </path>
                            </svg>
                            Edit
                        </a>
                        <a href="{{ route('admin.inventaris.index') }}"
                            class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                                </path>
                            </svg>
                            Kembali
                        </a>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Image Section -->
                    <div>
                        <div class="aspect-w-16 aspect-h-12 bg-gray-200 rounded-lg overflow-hidden">
                            @if ($inventaris->images)
                                <img src="{{ Storage::url($inventaris->images) }}" alt="{{ $inventaris->nama }}"
                                    class="w-full h-64 object-cover rounded-lg">
                            @else
                                <div
                                    class="w-full h-64 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center rounded-lg">
                                    <svg class="w-20 h-20 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5">
                                        </path>
                                    </svg>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Details Section -->
                    <div>
                        <dl class="space-y-6">
                            <!-- Nama -->
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Nama Inventaris</dt>
                                <dd class="mt-1 text-lg font-semibold text-gray-900">{{ $inventaris->nama }}</dd>
                            </div>

                            <!-- Status -->
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Status</dt>
                                <dd class="mt-1">
                                    <span
                                        class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $inventaris->status === 'tersedia' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ ucfirst($inventaris->status) }}
                                    </span>
                                </dd>
                            </div>

                            <!-- Kuantitas -->
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Kuantitas</dt>
                                <dd class="mt-1 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z">
                                        </path>
                                    </svg>
                                    <span class="text-lg font-semibold text-gray-900">{{ $inventaris->kuantitas }}</span>
                                    <span class="text-sm text-gray-500 ml-1">unit</span>
                                </dd>
                            </div>

                            {{-- PERBAIKAN: Hilangkan perhitungan dipinjam sementara karena tabel belum ada --}}
                            {{-- @php
                                $totalDipinjam = $inventaris->detailPeminjaman->where('peminjaman.status', 'dipinjam')->sum('kuantitas');
                            @endphp
                            @if ($totalDipinjam > 0)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Sedang Dipinjam</dt>
                                    <dd class="mt-1 flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-orange-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4">
                                            </path>
                                        </svg>
                                        <span class="text-lg font-semibold text-orange-900">{{ $totalDipinjam }}</span>
                                        <span class="text-sm text-gray-500 ml-1">unit</span>
                                    </dd>
                                </div>
                            @endif --}}

                            <!-- Tersedia - PERBAIKAN: Tampilkan total kuantitas saja dulu -->
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Kuantitas Tersedia</dt>
                                <dd class="mt-1 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-lg font-semibold text-green-900">
                                        {{ $inventaris->kuantitas }}
                                    </span>
                                    <span class="text-sm text-gray-500 ml-1">unit</span>
                                </dd>
                            </div>

                            <!-- Tanggal -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Ditambahkan</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        {{ $inventaris->created_at->format('d/m/Y H:i') }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Terakhir Diupdate</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        {{ $inventaris->updated_at->format('d/m/Y H:i') }}
                                    </dd>
                                </div>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Deskripsi -->
                @if ($inventaris->deskripsi)
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <dt class="text-sm font-medium text-gray-500 mb-2">Deskripsi</dt>
                        <dd class="text-gray-900 leading-relaxed">{{ $inventaris->deskripsi }}</dd>
                    </div>
                @endif

                <!-- Riwayat Peminjaman - PERBAIKAN: Hilangkan sementara karena tabel belum ada -->
                {{-- @if ($inventaris->detailPeminjaman && $inventaris->detailPeminjaman->count() > 0)
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Riwayat Peminjaman</h3>
                        <!-- Riwayat peminjaman content -->
                    </div>
                @endif --}}

                <!-- Action Buttons -->
                <div class="mt-8 pt-6 border-t border-gray-200 flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('admin.inventaris.edit', $inventaris->id) }}"
                            class="inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                </path>
                            </svg>
                            Edit Inventaris
                        </a>
                    </div>

                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                            class="inline-flex items-center px-3 py-2 text-sm font-medium text-red-700 bg-red-50 border border-red-200 rounded-md hover:bg-red-100 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                </path>
                            </svg>
                            Hapus
                        </button>

                        <div x-show="open" @click.away="open = false" x-transition
                            class="absolute right-0 mt-2 w-64 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 z-10">
                            <div class="p-4">
                                <h3 class="text-sm font-medium text-gray-900 mb-2">Hapus Inventaris</h3>
                                <p class="text-sm text-gray-600 mb-4">
                                    Apakah Anda yakin ingin menghapus inventaris ini? Tindakan ini tidak dapat dibatalkan.
                                </p>
                                <div class="flex items-center space-x-2">
                                    <form action="{{ route('admin.inventaris.destroy', $inventaris->id) }}"
                                        method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-medium rounded transition-colors">
                                            Ya, Hapus
                                        </button>
                                    </form>
                                    <button @click="open = false"
                                        class="px-3 py-1.5 bg-gray-200 hover:bg-gray-300 text-gray-700 text-xs font-medium rounded transition-colors">
                                        Batal
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Enhanced delete confirmation
        document.addEventListener('DOMContentLoaded', function() {
            const deleteForm = document.querySelector('form[action*="destroy"]');

            if (deleteForm) {
                deleteForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const inventarisName = '{{ $inventaris->nama }}';
                    const result = confirm(
                        `⚠️ PERINGATAN HAPUS INVENTARIS\n\nAnda akan menghapus: "${inventarisName}"\n\n• Tindakan ini tidak dapat dibatalkan\n• Data akan hilang permanen\n• Riwayat peminjaman akan tetap tersimpan\n\nApakah Anda yakin ingin melanjutkan?`
                        );

                    if (result) {
                        // Show loading state
                        const submitBtn = this.querySelector('button[type="submit"]');
                        const originalText = submitBtn.innerHTML;
                        submitBtn.innerHTML =
                            '<svg class="w-4 h-4 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 0021.001 8c0 .522-.042 1.04-.122 1.548"></path></svg>Menghapus...';
                        submitBtn.disabled = true;
                        submitBtn.classList.remove('hover:bg-red-700');
                        submitBtn.classList.add('opacity-75', 'cursor-not-allowed');

                        // Submit the form
                        this.submit();
                    }
                });
            }
        });
    </script>
@endsection
