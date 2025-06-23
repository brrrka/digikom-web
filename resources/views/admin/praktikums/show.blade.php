{{-- resources/views/admin/praktikums/show.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Detail Praktikum')
@section('page-title', 'Detail Praktikum')

@section('content')
    <div class="max-w-6xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">{{ $praktikum->name }}</h2>
                        <p class="text-sm text-gray-600 mt-1">Detail lengkap praktikum dan modul-modulnya</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('admin.praktikums.edit', $praktikum) }}"
                            class="inline-flex items-center px-3 py-2 text-sm font-medium text-blue-700 bg-blue-50 border border-blue-200 rounded-md hover:bg-blue-100 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                </path>
                            </svg>
                            Edit
                        </a>
                        <a href="{{ route('admin.praktikums.index') }}"
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
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Praktikum</h3>
                                <dl class="grid grid-cols-1 gap-4">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Nama Praktikum</dt>
                                        <dd class="mt-1 text-xl font-semibold text-gray-900">{{ $praktikum->name }}</dd>
                                    </div>

                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Slug</dt>
                                        <dd class="mt-1 text-sm text-gray-900 font-mono bg-gray-50 px-2 py-1 rounded">{{ $praktikum->slug }}</dd>
                                    </div>

                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">URL Praktikum</dt>
                                        <dd class="mt-1">
                                            <a href="/praktikum/{{ $praktikum->slug }}" target="_blank"
                                                class="text-sm text-primary-600 hover:text-primary-500 underline">
                                                {{ url('/praktikum/' . $praktikum->slug) }}
                                                <svg class="w-3 h-3 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                                </svg>
                                            </a>
                                        </dd>
                                    </div>
                                </dl>
                            </div>

                            <!-- Image Section -->
                            @if($praktikum->image)
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Gambar Praktikum</h3>
                                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                                        <img src="{{ Storage::url($praktikum->image) }}" alt="{{ $praktikum->name }}"
                                            class="w-full h-64 object-cover">
                                    </div>
                                </div>
                            @endif

                            <!-- Moduls Section -->
                            <div>
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg font-medium text-gray-900">
                                        Modul-modul ({{ $praktikum->moduls->count() }})
                                    </h3>
                                    <a href="{{ route('admin.moduls.create', ['praktikum_id' => $praktikum->id]) }}"
                                        class="inline-flex items-center px-3 py-2 text-sm font-medium text-primary-700 bg-primary-50 border border-primary-200 rounded-md hover:bg-primary-100 transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        Tambah Modul
                                    </a>
                                </div>

                                @if($praktikum->moduls->count() > 0)
                                    <div class="space-y-4">
                                        @foreach($praktikum->moduls as $modul)
                                            <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                                                <div class="flex items-start justify-between">
                                                    <div class="flex-1 min-w-0">
                                                        <div class="flex items-center space-x-2 mb-2">
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800">
                                                                Modul {{ $modul->modul_ke }}
                                                            </span>
                                                            <h4 class="text-sm font-medium text-gray-900 truncate">{{ $modul->title }}</h4>
                                                        </div>

                                                        @if($modul->deskripsi)
                                                            <p class="text-sm text-gray-600 mb-2 line-clamp-2">{{ $modul->deskripsi }}</p>
                                                        @endif

                                                        <!-- Resource indicators -->
                                                        <div class="flex items-center space-x-4">
                                                            @if($modul->file_path)
                                                                <span class="inline-flex items-center text-xs text-green-600">
                                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                                        </path>
                                                                    </svg>
                                                                    File
                                                                </span>
                                                            @endif

                                                            @if($modul->link_video)
                                                                <span class="inline-flex items-center text-xs text-red-600">
                                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                            d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                                                                        </path>
                                                                    </svg>
                                                                    Video
                                                                </span>
                                                            @endif

                                                            @if($modul->images)
                                                                <span class="inline-flex items-center text-xs text-purple-600">
                                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                                        </path>
                                                                    </svg>
                                                                    Gambar
                                                                </span>
                                                            @endif

                                                            <span class="text-xs text-gray-500">
                                                                {{ $modul->created_at ? $modul->created_at->format('d/m/Y') : 'N/A' }}
                                                            </span>
                                                        </div>
                                                    </div>

                                                    <div class="flex items-center space-x-2 ml-4">
                                                        <a href="{{ route('admin.moduls.show', $modul) }}"
                                                            class="inline-flex items-center px-2 py-1 text-xs font-medium text-primary-700 bg-primary-50 rounded hover:bg-primary-100 transition-colors">
                                                            Lihat
                                                        </a>
                                                        <a href="{{ route('admin.moduls.edit', $modul) }}"
                                                            class="inline-flex items-center px-2 py-1 text-xs font-medium text-blue-700 bg-blue-50 rounded hover:bg-blue-100 transition-colors">
                                                            Edit
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-8 border-2 border-dashed border-gray-300 rounded-lg">
                                        <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                            </path>
                                        </svg>
                                        <h4 class="mt-2 text-sm font-medium text-gray-900">Belum ada modul</h4>
                                        <p class="mt-1 text-sm text-gray-500">Tambahkan modul pertama untuk praktikum ini.</p>
                                        <div class="mt-4">
                                            <a href="{{ route('admin.moduls.create', ['praktikum_id' => $praktikum->id]) }}"
                                                class="inline-flex items-center px-3 py-2 text-sm font-medium text-primary-700 bg-primary-50 border border-primary-200 rounded-md hover:bg-primary-100 transition-colors">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                </svg>
                                                Tambah Modul
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="lg:col-span-1">
                        <div class="space-y-6">
                            <!-- Details Card -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h3 class="text-sm font-medium text-gray-900 mb-3">Detail Praktikum</h3>
                                <dl class="space-y-3">
                                    <div>
                                        <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Modul</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $praktikum->moduls->count() }} modul</dd>
                                    </div>
                                    <div>
                                        <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Dibuat</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $praktikum->created_at ? $praktikum->created_at->format('d M Y, H:i') : 'N/A' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Diupdate</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $praktikum->updated_at ? $praktikum->updated_at->format('d M Y, H:i') : 'N/A' }}</dd>
                                    </div>
                                </dl>
                            </div>

                            <!-- Status Resources -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h3 class="text-sm font-medium text-gray-900 mb-3">Status Resource</h3>
                                <div class="space-y-3">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">Gambar Praktikum</span>
                                        @if($praktikum->image)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Ada
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                                Tidak Ada
                                            </span>
                                        @endif
                                    </div>

                                    @php
                                        $modulWithFile = $praktikum->moduls->where('file_path', '!=', null)->count();
                                        $modulWithVideo = $praktikum->moduls->where('link_video', '!=', null)->count();
                                        $modulWithImage = $praktikum->moduls->where('images', '!=', null)->count();
                                    @endphp

                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">Modul dengan File</span>
                                        <span class="text-xs text-gray-900 font-medium">{{ $modulWithFile }}/{{ $praktikum->moduls->count() }}</span>
                                    </div>

                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">Modul dengan Video</span>
                                        <span class="text-xs text-gray-900 font-medium">{{ $modulWithVideo }}/{{ $praktikum->moduls->count() }}</span>
                                    </div>

                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">Modul dengan Gambar</span>
                                        <span class="text-xs text-gray-900 font-medium">{{ $modulWithImage }}/{{ $praktikum->moduls->count() }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h3 class="text-sm font-medium text-gray-900 mb-3">Aksi</h3>
                                <div class="space-y-2">
                                    <a href="{{ route('admin.praktikums.edit', $praktikum) }}"
                                        class="w-full inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-blue-700 bg-blue-50 border border-blue-200 rounded-md hover:bg-blue-100 transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                        Edit Praktikum
                                    </a>

                                    <form action="{{ route('admin.praktikums.destroy', $praktikum) }}" method="POST"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus praktikum {{ $praktikum->name }}? Tindakan ini tidak dapat dibatalkan.')"
                                        class="w-full">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="w-full inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-red-700 bg-red-50 border border-red-200 rounded-md hover:bg-red-100 transition-colors">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                            Hapus Praktikum
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

                    const praktikumName = '{{ $praktikum->name }}';
                    const modulCount = {{ $praktikum->moduls->count() }};

                    let warningMessage = `⚠️ PERINGATAN HAPUS PRAKTIKUM\n\nAnda akan menghapus: "${praktikumName}"\n\n• Tindakan ini tidak dapat dibatalkan\n• Gambar dan data akan hilang permanen`;

                    if (modulCount > 0) {
                        warningMessage += `\n• Praktikum ini memiliki ${modulCount} modul`;
                    }

                    warningMessage += `\n\nApakah Anda yakin ingin melanjutkan?`;

                    const result = confirm(warningMessage);

                    if (result) {
                        // Show loading state
                        const submitBtn = this.querySelector('button[type="submit"]');
                        const originalText = submitBtn.innerHTML;
                        submitBtn.innerHTML = '<svg class="w-4 h-4 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 0021.001 8c0 .522-.042 1.04-.122 1.548"></path></svg>Menghapus...';
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
