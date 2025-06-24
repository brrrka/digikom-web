{{-- resources/views/admin/inventaris/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Inventaris')
@section('page-title', 'Management Inventaris')

@section('content')
    <div class="space-y-6">
        <!-- Header with Stats -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Inventaris</h2>
                    <p class="mt-1 text-sm text-gray-600">Kelola semua inventaris laboratorium</p>
                </div>
                <div class="mt-4 lg:mt-0">
                    <div class="flex flex-col sm:flex-row gap-2">
                        <!-- Export/Import Buttons -->
                        <div class="flex gap-2">
                            <!-- Export Button -->
                            <div class="relative">
                                <button id="export-dropdown-btn"
                                    class="inline-flex items-center px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                    Export
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>

                                <!-- Export Dropdown -->
                                <div id="export-dropdown"
                                    class="hidden absolute right-0 mt-2 w-56 bg-white rounded-md shadow-lg z-50 border">
                                    <div class="py-1">
                                        <a href="{{ route('admin.inventaris.export', request()->query()) }}"
                                            class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                </path>
                                            </svg>
                                            Export Semua Data
                                        </a>
                                        <button onclick="exportSelected()"
                                            class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed"
                                            id="export-selected-btn" disabled>
                                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                </path>
                                            </svg>
                                            Export Terpilih (<span id="selected-count">0</span>)
                                        </button>
                                        <div class="border-t border-gray-100"></div>
                                        <a href="{{ route('admin.inventaris.download-template') }}"
                                            class="flex items-center px-4 py-2 text-sm text-blue-700 hover:bg-blue-50">
                                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                                </path>
                                            </svg>
                                            Download Template
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Import Button -->
                            <a href="{{ route('admin.inventaris.import-form') }}"
                                class="inline-flex items-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                    </path>
                                </svg>
                                Import
                            </a>

                            <!-- Recalculate Button -->
                            <button onclick="recalculateAll()"
                                class="inline-flex items-center px-3 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-lg transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 0021.001 8c0 .522-.042 1.04-.122 1.548m-2.526 1.117l-.982-.982A6.996 6.996 0 0019.001 8c0-1.039-.223-2.028-.624-2.928l-2.082 2.082m-2.584-2.584L12.001 6.172V4a8.001 8.001 0 012.928.624l-2.082 2.082m-2.584-2.584A6.996 6.996 0 008.001 4v2.172L6.293 4.464A8.001 8.001 0 004.001 8c0 .522.042 1.04.122 1.548">
                                    </path>
                                </svg>
                                Recalculate
                            </button>
                        </div>

                        <!-- Add Inventaris Button -->
                        <a href="{{ route('admin.inventaris.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Tambah Inventaris
                        </a>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mt-6">
                <div class="bg-blue-50 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5">
                                </path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-blue-900">Total Item</p>
                            <p class="text-lg font-semibold text-blue-900">{{ $stats['total'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-green-50 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-900">Tersedia</p>
                            <p class="text-lg font-semibold text-green-900">{{ $stats['tersedia'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-red-50 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.864-.833-2.634 0L4.168 18.5c-.77.833.192 2.5 1.732 2.5z">
                                </path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-red-900">Tidak Tersedia</p>
                            <p class="text-lg font-semibold text-red-900">{{ $stats['tidak_tersedia'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-purple-50 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z">
                                </path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-purple-900">Total Qty</p>
                            <p class="text-lg font-semibold text-purple-900">{{ $stats['total_kuantitas'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-orange-50 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4">
                                </path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-orange-900">Dipinjam</p>
                            <p class="text-lg font-semibold text-orange-900">{{ $stats['total_dipinjam'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bulk Actions Bar (Hidden by default) -->
        <div id="bulk-actions-bar" class="hidden bg-blue-50 border border-blue-200 rounded-xl p-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    <span class="text-sm font-medium text-blue-900">
                        <span id="bulk-selected-count">0</span> item dipilih
                    </span>
                </div>
                <div class="flex items-center space-x-3">
                    <button onclick="bulkUpdateStatus('tersedia')"
                        class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-green-700 bg-green-100 rounded-md hover:bg-green-200 transition-colors">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Set Tersedia
                    </button>
                    <button onclick="bulkUpdateStatus('tidak tersedia')"
                        class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-red-700 bg-red-100 rounded-md hover:bg-red-200 transition-colors">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.864-.833-2.634 0L4.168 18.5c-.77.833.192 2.5 1.732 2.5z">
                            </path>
                        </svg>
                        Set Tidak Tersedia
                    </button>
                    <button onclick="clearSelection()"
                        class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 transition-colors">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Batal
                    </button>
                </div>
            </div>
        </div>

        <!-- Filter and Search -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <form method="GET" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Select All Checkbox -->
                    <div class="flex items-center">
                        <input type="checkbox" id="select-all"
                            class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded mr-2">
                        <label for="select-all" class="text-sm font-medium text-gray-700">Pilih Semua</label>
                    </div>

                    <!-- Search -->
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input type="text" name="search" id="search" value="{{ request('search') }}"
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                placeholder="Cari nama atau deskripsi...">
                        </div>
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" id="status"
                            class="block w-full py-2 px-3 border border-gray-300 bg-white rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                            <option value="">Semua Status</option>
                            <option value="tersedia" {{ request('status') === 'tersedia' ? 'selected' : '' }}>Tersedia
                            </option>
                            <option value="tidak tersedia" {{ request('status') === 'tidak tersedia' ? 'selected' : '' }}>
                                Tidak Tersedia</option>
                        </select>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-end space-x-2">
                        <button type="submit"
                            class="px-4 py-2 bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Filter
                        </button>
                        @if (request()->hasAny(['search', 'status']))
                            <a href="{{ route('admin.inventaris.index') }}"
                                class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                                Reset
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        <!-- Inventaris Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($inventaris as $item)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover-lift relative"
                    data-item-id="{{ $item->id }}">

                    <!-- Checkbox for bulk selection -->
                    <div class="absolute top-3 left-3 z-10">
                        <input type="checkbox"
                            class="item-checkbox h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded bg-white shadow-sm"
                            value="{{ $item->id }}" onchange="updateSelection()">
                    </div>

                    <!-- Image -->
                    <div class="aspect-w-16 aspect-h-12 bg-gray-200">
                        @if ($item->images)
                            <img src="{{ Storage::url($item->images) }}" alt="{{ $item->nama }}"
                                class="w-full h-48 object-cover">
                        @else
                            <div
                                class="w-full h-48 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5">
                                    </path>
                                </svg>
                            </div>
                        @endif
                    </div>

                    <!-- Content -->
                    <div class="p-4">
                        <div class="flex items-start justify-between">
                            <div class="flex-1 min-w-0">
                                <h3 class="text-lg font-semibold text-gray-900 truncate">{{ $item->nama }}</h3>
                                <p class="text-sm text-gray-500 mt-1 line-clamp-2">
                                    {{ $item->deskripsi ?: 'Tidak ada deskripsi' }}</p>

                                <!-- Stats -->
                                <div class="flex items-center justify-between mt-3">
                                    <div class="flex items-center text-sm text-gray-600">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z">
                                            </path>
                                        </svg>
                                        Qty: {{ $item->kuantitas }}
                                    </div>
                                    @if (($item->total_dipinjam ?? 0) > 0)
                                        <div class="flex items-center text-sm text-orange-600">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3">
                                                </path>
                                            </svg>
                                            Dipinjam: {{ $item->total_dipinjam }}
                                        </div>
                                    @endif
                                </div>

                                <!-- Status Badge -->
                                <div class="mt-3">
                                    <span
                                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $item->status === 'tersedia' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-200">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('admin.inventaris.show', $item->id) }}"
                                    class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-primary-700 bg-primary-50 rounded-md hover:bg-primary-100 transition-colors">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                    Lihat
                                </a>
                                <a href="{{ route('admin.inventaris.edit', $item->id) }}"
                                    class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-blue-700 bg-blue-50 rounded-md hover:bg-blue-100 transition-colors">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                    Edit
                                </a>
                            </div>

                            <!-- Delete Button -->
                            <form action="{{ route('admin.inventaris.destroy', $item->id) }}" method="POST"
                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus inventaris {{ $item->nama }}? Tindakan ini tidak dapat dibatalkan.')"
                                class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-red-700 bg-red-50 rounded-md hover:bg-red-100 transition-colors">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5">
                            </path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada inventaris</h3>
                        <p class="mt-1 text-sm text-gray-500">Mulai dengan menambahkan inventaris pertama.</p>
                        <div class="mt-6">
                            <a href="{{ route('admin.inventaris.create') }}"
                                class="inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-lg transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Tambah Inventaris
                            </a>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if ($inventaris->hasPages())
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 px-6 py-4">
                {{ $inventaris->links() }}
            </div>
        @endif
    </div>

    <script>
        // Export/Import functionality
        let selectedItems = new Set();

        // Toggle export dropdown
        document.getElementById('export-dropdown-btn').addEventListener('click', function(e) {
            e.stopPropagation();
            document.getElementById('export-dropdown').classList.toggle('hidden');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function() {
            document.getElementById('export-dropdown').classList.add('hidden');
        });

        // Update selection tracking
        function updateSelection() {
            const checkboxes = document.querySelectorAll('.item-checkbox:checked');
            selectedItems.clear();

            checkboxes.forEach(checkbox => {
                selectedItems.add(checkbox.value);
            });

            const count = selectedItems.size;
            document.getElementById('selected-count').textContent = count;
            document.getElementById('bulk-selected-count').textContent = count;

            // Show/hide bulk actions bar
            const bulkBar = document.getElementById('bulk-actions-bar');
            if (count > 0) {
                bulkBar.classList.remove('hidden');
            } else {
                bulkBar.classList.add('hidden');
            }

            // Enable/disable export selected button
            const exportBtn = document.getElementById('export-selected-btn');
            exportBtn.disabled = count === 0;

            // Update select all checkbox
            const selectAllCheckbox = document.getElementById('select-all');
            const totalCheckboxes = document.querySelectorAll('.item-checkbox').length;
            selectAllCheckbox.checked = count === totalCheckboxes && totalCheckboxes > 0;
            selectAllCheckbox.indeterminate = count > 0 && count < totalCheckboxes;
        }

        // Select all functionality
        document.getElementById('select-all').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.item-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateSelection();
        });

        // Export selected items
        function exportSelected() {
            if (selectedItems.size === 0) {
                alert('Pilih minimal satu item untuk di-export.');
                return;
            }

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route('admin.inventaris.bulk-export') }}';

            // Add CSRF token
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);

            // Add selected IDs
            selectedItems.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'ids[]';
                input.value = id;
                form.appendChild(input);
            });

            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);
        }

        // Bulk update status
        function bulkUpdateStatus(status) {
            if (selectedItems.size === 0) {
                alert('Pilih minimal satu item untuk diupdate.');
                return;
            }

            if (!confirm(`Apakah Anda yakin ingin mengubah status ${selectedItems.size} item menjadi "${status}"?`)) {
                return;
            }

            fetch('{{ route('admin.inventaris.bulk-update-status') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        ids: Array.from(selectedItems),
                        status: status
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat mengupdate status.');
                });
        }

        // Clear selection
        function clearSelection() {
            document.querySelectorAll('.item-checkbox').forEach(checkbox => {
                checkbox.checked = false;
            });
            document.getElementById('select-all').checked = false;
            updateSelection();
        }

        // Recalculate all
        function recalculateAll() {
            if (!confirm(
                    'Apakah Anda yakin ingin merecalculate semua data inventaris? Proses ini akan memperbarui jumlah dipinjam berdasarkan data peminjaman aktual.'
                    )) {
                return;
            }

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route('admin.inventaris.recalculate-all') }}';

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);

            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);
        }

        // Enhanced delete confirmation with better UX
        document.addEventListener('DOMContentLoaded', function() {
            const deleteForms = document.querySelectorAll('form[onsubmit*="confirm"]');

            deleteForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const itemName = this.action.includes('inventaris') ?
                        this.querySelector('button').closest('.bg-white').querySelector('h3')
                        .textContent.trim() :
                        'item ini';

                    // Create custom modal-like confirmation
                    const result = confirm(
                        `⚠️ PERINGATAN HAPUS INVENTARIS\n\nAnda akan menghapus: "${itemName}"\n\n• Tindakan ini tidak dapat dibatalkan\n• Data akan hilang permanen\n• Pastikan tidak ada peminjaman aktif\n\nApakah Anda yakin ingin melanjutkan?`
                    );

                    if (result) {
                        // Show loading state
                        const submitBtn = this.querySelector('button[type="submit"]');
                        const originalText = submitBtn.innerHTML;
                        submitBtn.innerHTML =
                            '<svg class="w-4 h-4 mr-1 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 0021.001 8c0 .522-.042 1.04-.122 1.548m-2.526 1.117l-.982-.982A6.996 6.996 0 0019.001 8c0-1.039-.223-2.028-.624-2.928l-2.082 2.082m-2.584-2.584L12.001 6.172V4a8.001 8.001 0 012.928.624l-2.082 2.082m-2.584-2.584A6.996 6.996 0 008.001 4v2.172L6.293 4.464A8.001 8.001 0 004.001 8c0 .522.042 1.04.122 1.548"></path></svg>Menghapus...';
                        submitBtn.disabled = true;

                        // Submit the form
                        this.submit();
                    }
                });
            });
        });

        // Auto hide success/error messages
        setTimeout(function() {
            const alerts = document.querySelectorAll('.animate-slide-in');
            alerts.forEach(function(alert) {
                if (alert) {
                    alert.style.transition = 'opacity 0.3s ease-out';
                    alert.style.opacity = '0';
                    setTimeout(function() {
                        if (alert.parentNode) {
                            alert.remove();
                        }
                    }, 300);
                }
            });
        }, 5000);
    </script>
@endsection
