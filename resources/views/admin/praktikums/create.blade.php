{{-- resources/views/admin/praktikums/create.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Tambah Praktikum')
@section('page-title', 'Tambah Praktikum')

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Tambah Praktikum Baru</h2>
                        <p class="text-sm text-gray-600 mt-1">Isi informasi lengkap untuk praktikum baru</p>
                    </div>
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

            <!-- Form -->
            <form action="{{ route('admin.praktikums.store') }}" method="POST" enctype="multipart/form-data"
                class="p-6">
                @csrf

                <div class="space-y-6">
                    <!-- Nama Praktikum -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Praktikum <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('name') border-red-300 @enderror"
                            placeholder="Masukkan nama praktikum..." required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Slug akan dibuat otomatis berdasarkan nama</p>
                    </div>

                    <!-- Slug Preview -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Preview Slug</label>
                        <div class="px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg">
                            <span id="slug-preview" class="text-sm text-gray-600 italic">akan-dibuat-otomatis</span>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">URL praktikum akan menjadi: /praktikum/<span
                                id="slug-url">akan-dibuat-otomatis</span></p>
                    </div>

                    <!-- Upload Gambar -->
                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                            Gambar Praktikum
                        </label>
                        <div
                            class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 transition-colors">
                            <div class="space-y-1 text-center">
                                <div id="image-preview" class="hidden">
                                    <img id="preview-img" src="" alt="Preview"
                                        class="mx-auto h-48 w-auto rounded-lg">
                                </div>
                                <div id="image-placeholder">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                        viewBox="0 0 48 48">
                                        <path
                                            d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="text-sm text-gray-600">
                                        <label for="image"
                                            class="relative cursor-pointer bg-white rounded-md font-medium text-primary-600 hover:text-primary-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary-500">
                                            <span>Upload gambar</span>
                                            <input id="image" name="image" type="file" class="sr-only"
                                                accept="image/*" onchange="previewImage(event)">
                                        </label>
                                        <p class="pl-1">atau drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG, GIF hingga 2MB</p>
                                </div>
                            </div>
                        </div>
                        @error('image')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Info Box -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">Informasi Praktikum</h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <ul class="list-disc pl-5 space-y-1">
                                        <li>Nama praktikum harus unik dan deskriptif</li>
                                        <li>Slug akan dibuat otomatis dari nama praktikum</li>
                                        <li>Gambar akan digunakan sebagai thumbnail praktikum</li>
                                        <li>Setelah dibuat, Anda dapat menambahkan modul-modul ke praktikum ini</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                        <a href="{{ route('admin.praktikums.index') }}"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
                            Batal
                        </a>
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-primary-600 border border-transparent rounded-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Simpan Praktikum
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Function to create slug from name
        function createSlug(text) {
            return text
                .toLowerCase()
                .trim()
                .replace(/[^\w\s-]/g, '') // remove special characters
                .replace(/[\s_-]+/g, '-') // replace spaces and underscores with hyphens
                .replace(/^-+|-+$/g, ''); // remove leading/trailing hyphens
        }

        // Preview image function
        function previewImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('preview-img').src = e.target.result;
                    document.getElementById('image-preview').classList.remove('hidden');
                    document.getElementById('image-placeholder').classList.add('hidden');
                };
                reader.readAsDataURL(file);
            }
        }

        // Auto-generate slug when typing name
        document.addEventListener('DOMContentLoaded', function() {
            const nameInput = document.getElementById('name');
            const slugPreview = document.getElementById('slug-preview');
            const slugUrl = document.getElementById('slug-url');

            nameInput.addEventListener('input', function() {
                const slug = createSlug(this.value);
                const displaySlug = slug || 'akan-dibuat-otomatis';

                slugPreview.textContent = displaySlug;
                slugUrl.textContent = displaySlug;

                // Add some visual feedback
                if (this.value.trim()) {
                    slugPreview.classList.remove('italic');
                    slugPreview.classList.add('font-medium');
                } else {
                    slugPreview.classList.add('italic');
                    slugPreview.classList.remove('font-medium');
                }
            });
        });
    </script>
@endsection
