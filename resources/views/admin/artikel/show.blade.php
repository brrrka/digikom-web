{{-- resources/views/admin/artikel/show.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Detail Artikel')
@section('page-title', 'Detail Artikel')

@section('content')
    <div class="max-w-6xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">{{ $artikel->title }}</h2>
                        <p class="text-sm text-gray-600 mt-1">Detail lengkap artikel dan informasinya</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('admin.artikel.edit', $artikel) }}"
                            class="inline-flex items-center px-3 py-2 text-sm font-medium text-blue-700 bg-blue-50 border border-blue-200 rounded-md hover:bg-blue-100 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                </path>
                            </svg>
                            Edit
                        </a>
                        <a href="{{ route('admin.artikel.index') }}"
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
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Artikel</h3>
                                <dl class="grid grid-cols-1 gap-4">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Judul Artikel</dt>
                                        <dd class="mt-1 text-xl font-semibold text-gray-900">{{ $artikel->title }}</dd>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                                            <dd class="mt-1">
                                                @if ($artikel->status == 'published')
                                                    <span
                                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                        </svg>
                                                        Published
                                                    </span>
                                                @else
                                                    <span
                                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        Draft
                                                    </span>
                                                @endif
                                            </dd>
                                        </div>

                                        <div>
                                            <dt class="text-sm font-medium text-gray-500">Penulis</dt>
                                            <dd class="mt-1 text-sm text-gray-900">{{ $artikel->user->name ?? 'N/A' }}</dd>
                                        </div>
                                    </div>

                                    @if ($artikel->tags)
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500">Tags</dt>
                                            <dd class="mt-1">
                                                <div class="flex flex-wrap gap-2">
                                                    @php
                                                        $tags = explode(',', $artikel->tags);
                                                    @endphp
                                                    @foreach ($tags as $tag)
                                                        <span
                                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                            {{ trim($tag) }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            </dd>
                                        </div>
                                    @endif
                                </dl>
                            </div>

                            <!-- Image Section -->
                            @if ($artikel->image)
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Gambar Artikel</h3>
                                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                                        <img src="{{ Storage::url($artikel->image) }}" alt="{{ $artikel->title }}"
                                            class="w-full h-64 object-cover">
                                    </div>
                                </div>
                            @endif

                            <!-- Content Section -->
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Konten Artikel</h3>
                                <div class="prose prose-sm max-w-none bg-gray-50 rounded-lg p-6 border border-gray-200">
                                    {!! $artikel->content !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="lg:col-span-1">
                        <div class="space-y-6">
                            <!-- Details Card -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h3 class="text-sm font-medium text-gray-900 mb-3">Detail Artikel</h3>
                                <dl class="space-y-3">
                                    <div>
                                        <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Status</dt>
                                        <dd class="mt-1 text-sm text-gray-900 capitalize">{{ $artikel->status }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Penulis</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $artikel->user->name ?? 'N/A' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Dibuat</dt>
                                        <dd class="mt-1 text-sm text-gray-900">
                                            {{ $artikel->created_at ? $artikel->created_at->format('d M Y, H:i') : 'N/A' }}
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Diupdate</dt>
                                        <dd class="mt-1 text-sm text-gray-900">
                                            {{ $artikel->updated_at ? $artikel->updated_at->format('d M Y, H:i') : 'N/A' }}
                                        </dd>
                                    </div>
                                    @if ($artikel->published_at)
                                        <div>
                                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">
                                                Dipublikasi</dt>
                                            <dd class="mt-1 text-sm text-gray-900">
                                                {{ $artikel->published_at->format('d M Y, H:i') }}</dd>
                                        </div>
                                    @endif
                                </dl>
                            </div>

                            <!-- Status Info -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h3 class="text-sm font-medium text-gray-900 mb-3">Status Info</h3>
                                <div class="space-y-3">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">Gambar Artikel</span>
                                        @if ($artikel->image)
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
                                        <span class="text-sm text-gray-600">Tags</span>
                                        @if ($artikel->tags)
                                            <span
                                                class="text-xs text-gray-900 font-medium">{{ count(explode(',', $artikel->tags)) }}
                                                tag</span>
                                        @else
                                            <span class="text-xs text-gray-500">Tidak ada</span>
                                        @endif
                                    </div>

                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">Jumlah Kata</span>
                                        <span
                                            class="text-xs text-gray-900 font-medium">{{ str_word_count(strip_tags($artikel->content)) }}
                                            kata</span>
                                    </div>

                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">Jumlah Karakter</span>
                                        <span
                                            class="text-xs text-gray-900 font-medium">{{ strlen(strip_tags($artikel->content)) }}
                                            karakter</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Quick Actions -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h3 class="text-sm font-medium text-gray-900 mb-3">Aksi Cepat</h3>
                                <div class="space-y-2">
                                    <!-- Toggle Status -->
                                    <form action="{{ route('admin.artikel.toggle-status', $artikel) }}" method="POST"
                                        class="w-full">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="w-full inline-flex items-center justify-center px-3 py-2 text-sm font-medium rounded-md transition-colors
                                            @if ($artikel->status == 'published') text-yellow-700 bg-yellow-50 border border-yellow-200 hover:bg-yellow-100
                                            @else
                                                text-green-700 bg-green-50 border border-green-200 hover:bg-green-100 @endif">
                                            @if ($artikel->status == 'published')
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Jadikan Draft
                                            @else
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Publikasikan
                                            @endif
                                        </button>
                                    </form>

                                    <!-- Edit -->
                                    <a href="{{ route('admin.artikel.edit', $artikel) }}"
                                        class="w-full inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-blue-700 bg-blue-50 border border-blue-200 rounded-md hover:bg-blue-100 transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                        Edit Artikel
                                    </a>

                                    <!-- Duplicate -->
                                    <form action="{{ route('admin.artikel.duplicate', $artikel) }}" method="POST"
                                        class="w-full">
                                        @csrf
                                        <button type="submit"
                                            class="w-full inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-purple-700 bg-purple-50 border border-purple-200 rounded-md hover:bg-purple-100 transition-colors">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                            Duplikasi
                                        </button>
                                    </form>

                                    <!-- Delete -->
                                    <form action="{{ route('admin.artikel.destroy', $artikel) }}" method="POST"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus artikel {{ $artikel->title }}? Tindakan ini tidak dapat dibatalkan.')"
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
                                            Hapus Artikel
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

                    const artikelTitle = '{{ $artikel->title }}';

                    let warningMessage =
                        `⚠️ PERINGATAN HAPUS ARTIKEL\n\nAnda akan menghapus: "${artikelTitle}"\n\n• Tindakan ini tidak dapat dibatalkan\n• Gambar dan data akan hilang permanen`;

                    warningMessage += `\n\nApakah Anda yakin ingin melanjutkan?`;

                    const result = confirm(warningMessage);

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
