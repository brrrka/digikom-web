{{-- resources/views/admin/artikel/create.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Tambah Artikel')
@section('page-title', 'Tambah Artikel')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Tambah Artikel Baru</h2>
                        <p class="text-sm text-gray-600 mt-1">Isi informasi lengkap untuk artikel baru</p>
                    </div>
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

            <!-- Form -->
            <form action="{{ route('admin.artikel.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf

                <div class="space-y-6">
                    <!-- Judul Artikel -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            Judul Artikel <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('title') border-red-300 @enderror"
                            placeholder="Masukkan judul artikel..." required>
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Penulis -->
                    <div>
                        <label for="id_users" class="block text-sm font-medium text-gray-700 mb-2">
                            Penulis <span class="text-red-500">*</span>
                        </label>
                        <select name="id_users" id="id_users"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('id_users') border-red-300 @enderror"
                            required>
                            <option value="">Pilih Penulis</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" {{ old('id_users') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_users')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select name="status" id="status"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('status') border-red-300 @enderror"
                            required>
                            <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published
                            </option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Pilih "Draft" untuk menyimpan tanpa publikasi, atau
                            "Published" untuk langsung mempublikasi</p>
                    </div>

                    <!-- Tags -->
                    <div>
                        <label for="tags" class="block text-sm font-medium text-gray-700 mb-2">
                            Tags
                        </label>
                        <input type="text" name="tags" id="tags" value="{{ old('tags') }}"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('tags') border-red-300 @enderror"
                            placeholder="Pisahkan dengan koma: teknologi, berita, artikel">
                        @error('tags')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Pisahkan beberapa tag dengan koma (opsional)</p>
                    </div>

                    <!-- Content -->
                    <div>
                        <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                            Konten Artikel <span class="text-red-500">*</span>
                        </label>
                        <textarea name="content" id="content" rows="12"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('content') border-red-300 @enderror"
                            placeholder="Tulis konten artikel di sini...

Anda bisa menggunakan HTML sederhana seperti:
- <p>untuk paragraf</p>
- <strong>untuk teks tebal</strong>
- <em>untuk teks miring</em>
- <h3>untuk subjudul</h3>
- <ul><li>untuk daftar</li></ul>"
                            required>{{ old('content') }}</textarea>
                        @error('content')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Konten mendukung HTML sederhana untuk formatting</p>
                    </div>

                    <!-- Upload Gambar -->
                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                            Gambar Artikel
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
                                <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">Tips Menulis Artikel</h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <ul class="list-disc pl-5 space-y-1">
                                        <li>Gunakan judul yang menarik dan deskriptif</li>
                                        <li>Tulis konten yang informatif dan mudah dipahami</li>
                                        <li>Tambahkan gambar untuk mempercantik tampilan artikel</li>
                                        <li>Gunakan tags yang relevan untuk memudahkan pencarian</li>
                                        <li>Simpan sebagai draft terlebih dahulu untuk review sebelum publikasi</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Preview Content -->
                    <div id="content-preview" class="hidden">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Preview Konten</h3>
                        <div class="prose prose-sm max-w-none bg-gray-50 rounded-lg p-6 border border-gray-200">
                            <div id="preview-content"></div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                        <a href="{{ route('admin.artikel.index') }}"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
                            Batal
                        </a>
                        <button type="button" id="preview-btn"
                            class="px-4 py-2 text-sm font-medium text-purple-700 bg-purple-50 border border-purple-300 rounded-md hover:bg-purple-100 transition-colors">
                            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                </path>
                            </svg>
                            Preview
                        </button>
                        <button type="submit" name="action" value="draft"
                            class="px-4 py-2 text-sm font-medium text-yellow-700 bg-yellow-50 border border-yellow-300 rounded-md hover:bg-yellow-100 transition-colors">
                            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                </path>
                            </svg>
                            Simpan sebagai Draft
                        </button>
                        <button type="submit" name="action" value="published"
                            class="px-4 py-2 text-sm font-medium text-white bg-primary-600 border border-transparent rounded-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Simpan & Publikasikan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
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

        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const statusSelect = document.getElementById('status');
            const contentTextarea = document.getElementById('content');
            const previewBtn = document.getElementById('preview-btn');
            const contentPreview = document.getElementById('content-preview');
            const previewContent = document.getElementById('preview-content');

            // Update status field based on which button is clicked
            document.querySelectorAll('button[name="action"]').forEach(button => {
                button.addEventListener('click', function() {
                    statusSelect.value = this.value;
                });
            });

            // Auto-resize textarea
            if (contentTextarea) {
                contentTextarea.addEventListener('input', function() {
                    this.style.height = 'auto';
                    this.style.height = (this.scrollHeight) + 'px';
                });

                // Initial resize
                contentTextarea.style.height = 'auto';
                contentTextarea.style.height = (contentTextarea.scrollHeight) + 'px';
            }

            // Tags input enhancement
            const tagsInput = document.getElementById('tags');
            if (tagsInput) {
                tagsInput.addEventListener('input', function() {
                    // Auto-format tags (remove extra spaces, etc.)
                    let value = this.value;
                    // Remove multiple consecutive commas
                    value = value.replace(/,+/g, ',');
                    // Remove spaces before commas
                    value = value.replace(/\s+,/g, ',');
                    // Add space after commas if missing
                    value = value.replace(/,(?!\s)/g, ', ');
                    this.value = value;
                });
            }

            // Preview functionality
            if (previewBtn && contentPreview && previewContent) {
                let isPreviewVisible = false;

                previewBtn.addEventListener('click', function() {
                    const content = contentTextarea.value.trim();

                    if (!content) {
                        alert('Silakan isi konten artikel terlebih dahulu.');
                        contentTextarea.focus();
                        return;
                    }

                    if (!isPreviewVisible) {
                        // Show preview
                        previewContent.innerHTML = content;
                        contentPreview.classList.remove('hidden');
                        this.innerHTML =
                            '<svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>Tutup Preview';
                        isPreviewVisible = true;

                        // Scroll to preview
                        contentPreview.scrollIntoView({
                            behavior: 'smooth'
                        });
                    } else {
                        // Hide preview
                        contentPreview.classList.add('hidden');
                        this.innerHTML =
                            '<svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>Preview';
                        isPreviewVisible = false;
                    }
                });
            }

            // Character counter for content
            if (contentTextarea) {
                const counterDiv = document.createElement('div');
                counterDiv.className = 'text-xs text-gray-500 mt-1';
                counterDiv.id = 'char-counter';
                contentTextarea.parentNode.appendChild(counterDiv);

                function updateCounter() {
                    const content = contentTextarea.value;
                    const wordCount = content.trim() ? content.trim().split(/\s+/).length : 0;
                    const charCount = content.length;
                    counterDiv.textContent = `${wordCount} kata, ${charCount} karakter`;
                }

                contentTextarea.addEventListener('input', updateCounter);
                updateCounter(); // Initial count
            }
        });
    </script>
@endsection
