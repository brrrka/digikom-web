{{-- resources/views/admin/praktikums/edit.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Edit Praktikum')
@section('page-title', 'Edit Praktikum')

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Edit Praktikum</h2>
                        <p class="text-sm text-gray-600 mt-1">Perbarui informasi praktikum: {{ $praktikum->name }}</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('admin.praktikums.show', $praktikum) }}"
                            class="inline-flex items-center px-3 py-2 text-sm font-medium text-primary-700 bg-primary-50 border border-primary-200 rounded-md hover:bg-primary-100 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                </path>
                            </svg>
                            Lihat Detail
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

            <!-- Form -->
            <form action="{{ route('admin.praktikums.update', $praktikum) }}" method="POST" enctype="multipart/form-data"
                class="p-6">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <!-- Current Image Preview -->
                    @if ($praktikum->image)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Gambar Saat Ini</label>
                            <div class="flex items-start space-x-4">
                                <img src="{{ Storage::url($praktikum->image) }}" alt="{{ $praktikum->name }}"
                                    class="h-32 w-32 object-cover rounded-lg border border-gray-300">
                                <div class="flex-1">
                                    <p class="text-sm text-gray-600">
                                        Gambar saat ini untuk praktikum ini. Anda dapat mengupload gambar baru di bawah
                                        untuk menggantinya.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Nama Praktikum -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Praktikum <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name', $praktikum->name) }}"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('name') border-red-300 @enderror"
                            placeholder="Masukkan nama praktikum..." required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Slug akan diupdate otomatis jika nama diubah</p>
                    </div>

                    <!-- Current Slug Info -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Slug Saat Ini</label>
                        <div class="px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg">
                            <span class="text-sm text-gray-600 font-mono">{{ $praktikum->slug }}</span>
                        </div>
                    </div>

                    <!-- New Slug Preview -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Preview Slug Baru</label>
                        <div class="px-3 py-2 bg-blue-50 border border-blue-300 rounded-lg">
                            <span id="slug-preview" class="text-sm text-blue-700 font-mono">{{ $praktikum->slug }}</span>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">URL akan menjadi: /praktikum/<span id="slug-url"
                                class="font-mono">{{ $praktikum->slug }}</span></p>
                    </div>

                    <!-- Upload Gambar Baru -->
                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ $praktikum->image ? 'Ganti Gambar Praktikum' : 'Upload Gambar Praktikum' }}
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
                                            <span>{{ $praktikum->image ? 'Upload gambar baru' : 'Upload gambar' }}</span>
                                            <input id="image" name="image" type="file" class="sr-only"
                                                accept="image/*" onchange="previewImage(event)">
                                        </label>
                                        <p class="pl-1">atau drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG, GIF hingga 2MB</p>
                                    @if ($praktikum->image)
                                        <p class="text-xs text-primary-600 mt-1">Kosongkan jika tidak ingin mengubah gambar
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @error('image')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Info Box -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.864-.833-2.634 0L4.168 18.5c-.77.833.192 2.5 1.732 2.5z">
                                    </path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800">Perhatian</h3>
                                <div class="mt-2 text-sm text-yellow-700">
                                    <ul class="list-disc pl-5 space-y-1">
                                        <li>Mengubah nama akan memperbarui slug secara otomatis</li>
                                        <li>Perubahan slug akan mengubah URL praktikum</li>
                                        <li>Praktikum ini memiliki {{ $praktikum->moduls->count() }} modul terkait</li>
                                        <li>Gambar lama akan dihapus jika Anda mengupload gambar baru</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                        <a href="{{ route('admin.praktikums.show', $praktikum) }}"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
                            Batal
                        </a>
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-primary-600 border border-transparent rounded-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                            </svg>
                            Update Praktikum
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

        // Auto-generate slug preview when typing name
        document.addEventListener('DOMContentLoaded', function() {
            const nameInput = document.getElementById('name');
            const slugPreview = document.getElementById('slug-preview');
            const slugUrl = document.getElementById('slug-url');
            const originalName = '{{ $praktikum->name }}';
            const originalSlug = '{{ $praktikum->slug }}';

            nameInput.addEventListener('input', function() {
                const currentValue = this.value.trim();

                if (currentValue === originalName || !currentValue) {
                    // Show original slug if name unchanged or empty
                    slugPreview.textContent = originalSlug;
                    slugUrl.textContent = originalSlug;
                    slugPreview.classList.remove('text-blue-700');
                    slugPreview.classList.add('text-gray-600');
                } else {
                    // Show new slug preview
                    const newSlug = createSlug(currentValue);
                    slugPreview.textContent = newSlug || 'akan-dibuat-otomatis';
                    slugUrl.textContent = newSlug || 'akan-dibuat-otomatis';
                    slugPreview.classList.remove('text-gray-600');
                    slugPreview.classList.add('text-blue-700');
                }
            });

            // Initialize on page load
            nameInput.dispatchEvent(new Event('input'));
        });
    </script>
@endsection
