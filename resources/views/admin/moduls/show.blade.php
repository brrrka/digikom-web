{{-- resources/views/admin/moduls/show.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Detail Modul')
@section('page-title', 'Detail Modul')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="flex items-center space-x-2 mb-1">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800">
                                Modul {{ $modul->modul_ke }}
                            </span>
                            <span class="text-sm text-gray-500">{{ $modul->praktikum->name }}</span>
                        </div>
                        <h2 class="text-lg font-semibold text-gray-900">{{ $modul->title }}</h2>
                        <p class="text-sm text-gray-600 mt-1">Detail lengkap modul praktikum</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('admin.moduls.edit', $modul) }}"
                            class="inline-flex items-center px-3 py-2 text-sm font-medium text-blue-700 bg-blue-50 border border-blue-200 rounded-md hover:bg-blue-100 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                </path>
                            </svg>
                            Edit
                        </a>
                        <a href="{{ route('admin.moduls.index') }}"
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
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Main Content -->
                    <div class="lg:col-span-2">
                        <!-- Basic Info -->
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Modul</h3>
                                <dl class="grid grid-cols-1 gap-4">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Judul</dt>
                                        <dd class="mt-1 text-lg font-semibold text-gray-900">{{ $modul->title }}</dd>
                                    </div>

                                    @if ($modul->deskripsi)
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500">Deskripsi</dt>
                                            <dd class="mt-1 text-gray-900 leading-relaxed">{{ $modul->deskripsi }}</dd>
                                        </div>
                                    @endif
                                </dl>
                            </div>

                            <!-- File Section -->
                            @if ($modul->file_path)
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">File Modul</h3>
                                    <div class="border border-gray-200 rounded-lg p-4">
                                        <div class="flex items-start space-x-3">
                                            <div class="flex-shrink-0 mt-1">
                                                <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                    </path>
                                                </svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900 break-words">
                                                    {{ basename($modul->file_path) }}
                                                </p>
                                                <p class="text-sm text-gray-500">
                                                    @php
                                                        try {
                                                            $fileSize = Storage::exists('public/' . $modul->file_path)
                                                                ? round(
                                                                    Storage::size('public/' . $modul->file_path) / 1024,
                                                                    2,
                                                                )
                                                                : 0;
                                                        } catch (Exception $e) {
                                                            $fileSize = 0;
                                                        }
                                                    @endphp
                                                    {{ $fileSize }} KB
                                                </p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <a href="{{ Storage::url($modul->file_path) }}" download
                                                    class="inline-flex items-center px-3 py-2 text-sm font-medium text-green-700 bg-green-50 rounded-md hover:bg-green-100 transition-colors">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                        </path>
                                                    </svg>
                                                    Download
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Video Section -->
                            @if ($modul->link_video)
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Video Pembelajaran</h3>
                                    <div class="border border-gray-200 rounded-lg p-4">
                                        <div class="flex items-start space-x-3">
                                            <div class="flex-shrink-0 mt-1">
                                                <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                                                    </path>
                                                </svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900">Video Link</p>
                                                <p class="text-sm text-gray-500 break-all">{{ $modul->link_video }}</p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <a href="{{ $modul->link_video }}" target="_blank"
                                                    class="inline-flex items-center px-3 py-2 text-sm font-medium text-red-700 bg-red-50 rounded-md hover:bg-red-100 transition-colors">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14">
                                                        </path>
                                                    </svg>
                                                    Tonton
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Image Section -->
                            @if ($modul->images)
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Gambar Modul</h3>
                                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                                        <img src="{{ Storage::url($modul->images) }}" alt="{{ $modul->title }}"
                                            class="w-full h-64 object-cover">
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="lg:col-span-1">
                        <div class="space-y-6">
                            <!-- Details Card -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h3 class="text-sm font-medium text-gray-900 mb-3">Detail Modul</h3>
                                <dl class="space-y-3">
                                    <div>
                                        <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Praktikum</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $modul->praktikum->name }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Modul Ke</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $modul->modul_ke }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Dibuat</dt>
                                        <dd class="mt-1 text-sm text-gray-900">
                                            {{ $modul->created_at ? $modul->created_at->format('d M Y, H:i') : 'N/A' }}
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Diupdate</dt>
                                        <dd class="mt-1 text-sm text-gray-900">
                                            {{ $modul->updated_at ? $modul->updated_at->format('d M Y, H:i') : 'N/A' }}
                                        </dd>
                                    </div>
                                </dl>
                            </div>

                            <!-- Resources Status -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h3 class="text-sm font-medium text-gray-900 mb-3">Status Resource</h3>
                                <div class="space-y-3">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">File Modul</span>
                                        @if ($modul->file_path)
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Ada
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                                Tidak Ada
                                            </span>
                                        @endif
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">Video</span>
                                        @if ($modul->link_video)
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Ada
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                                Tidak Ada
                                            </span>
                                        @endif
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">Gambar</span>
                                        @if ($modul->images)
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Ada
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                                Tidak Ada
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h3 class="text-sm font-medium text-gray-900 mb-3">Aksi</h3>
                                <div class="space-y-2">
                                    <a href="{{ route('admin.moduls.edit', $modul) }}"
                                        class="w-full inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-blue-700 bg-blue-50 border border-blue-200 rounded-md hover:bg-blue-100 transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                        Edit Modul
                                    </a>

                                    <form action="{{ route('admin.moduls.destroy', $modul) }}" method="POST"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus modul {{ $modul->title }}? Tindakan ini tidak dapat dibatalkan.')"
                                        class="w-full">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="w-full inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-red-700 bg-red-50 border border-red-200 rounded-md hover:bg-red-100 transition-colors">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                            Hapus Modul
                                        </button>
                                    </form>
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

                    const modulTitle = '{{ $modul->title }}';
                    const result = confirm(
                        `⚠️ PERINGATAN HAPUS MODUL\n\nAnda akan menghapus: "${modulTitle}"\n\n• Tindakan ini tidak dapat dibatalkan\n• File dan data akan hilang permanen\n\nApakah Anda yakin ingin melanjutkan?`
                        );

                    if (result) {
                        // Show loading state
                        const submitBtn = this.querySelector('button[type="submit"]');
                        const originalText = submitBtn.innerHTML;
                        submitBtn.innerHTML =
                            '<svg class="w-4 h-4 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 0021.001 8c0 .522-.042 1.04-.122 1.548"></path></svg>Menghapus...';
                        submitBtn.disabled = true;
                        submitBtn.classList.remove('hover:bg-red-100');
                        submitBtn.classList.add('opacity-75', 'cursor-not-allowed');

                        // Submit the form
                        this.submit();
                    }
                });
            }
        });
    </script>
@endsection
