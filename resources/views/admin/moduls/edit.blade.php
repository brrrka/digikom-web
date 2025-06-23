{{-- resources/views/admin/moduls/edit.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Edit Modul')
@section('page-title', 'Edit Modul')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Edit Modul</h2>
                        <p class="text-sm text-gray-600 mt-1">Perbarui informasi modul: {{ $modul->title }}</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('admin.moduls.show', $modul) }}"
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

            <!-- Form -->
            <form action="{{ route('admin.moduls.update', $modul) }}" method="POST" enctype="multipart/form-data"
                class="p-6">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <!-- Current Files Preview -->
                    @if ($modul->file_path || $modul->images)
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h3 class="text-sm font-medium text-gray-900 mb-3">File Saat Ini</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @if ($modul->file_path)
                                    <div class="flex items-center space-x-3 p-3 bg-white rounded border">
                                        <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate">
                                                {{ basename($modul->file_path) }}</p>
                                            <p class="text-xs text-gray-500">File Modul</p>
                                        </div>
                                    </div>
                                @endif

                                @if ($modul->images)
                                    <div class="flex items-center space-x-3 p-3 bg-white rounded border">
                                        <img src="{{ Storage::url($modul->images) }}" alt="Current image"
                                            class="h-12 w-12 object-cover rounded">
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate">
                                                {{ basename($modul->images) }}</p>
                                            <p class="text-xs text-gray-500">Gambar Modul</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <p class="text-xs text-gray-500 mt-2">Upload file baru di bawah untuk mengganti file yang ada
                            </p>
                        </div>
                    @endif

                    <!-- Praktikum dan Modul Ke -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Praktikum -->
                        <div>
                            <label for="id_praktikums" class="block text-sm font-medium text-gray-700 mb-2">
                                Praktikum <span class="text-red-500">*</span>
                            </label>
                            <select name="id_praktikums" id="id_praktikums"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('id_praktikums') border-red-300 @enderror"
                                required>
                                <option value="">Pilih Praktikum</option>
                                @foreach ($praktikums as $praktikum)
                                    <option value="{{ $praktikum->id }}"
                                        {{ old('id_praktikums', $modul->id_praktikums) == $praktikum->id ? 'selected' : '' }}>
                                        {{ $praktikum->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_praktikums')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Modul Ke -->
                        <div>
                            <label for="modul_ke" class="block text-sm font-medium text-gray-700 mb-2">
                                Modul Ke <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="modul_ke" id="modul_ke"
                                value="{{ old('modul_ke', $modul->modul_ke) }}"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('modul_ke') border-red-300 @enderror"
                                placeholder="1" min="1" required>
                            @error('modul_ke')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Nomor urut modul dalam praktikum (harus unik)</p>
                        </div>
                    </div>

                    <!-- Judul Modul -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            Judul Modul <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title" id="title" value="{{ old('title', $modul->title) }}"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('title') border-red-300 @enderror"
                            placeholder="Masukkan judul modul..." required>
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">
                            Deskripsi
                        </label>
                        <textarea name="deskripsi" id="deskripsi" rows="4"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('deskripsi') border-red-300 @enderror"
                            placeholder="Masukkan deskripsi modul...">{{ old('deskripsi', $modul->deskripsi) }}</textarea>
                        @error('deskripsi')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Upload File Baru -->
                    <div>
                        <label for="file_path" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ $modul->file_path ? 'Ganti File Modul' : 'Upload File Modul' }}
                        </label>
                        <div
                            class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 transition-colors">
                            <div class="space-y-1 text-center">
                                <div id="file-preview" class="hidden">
                                    <div class="flex items-center justify-center">
                                        <svg class="h-12 w-12 text-green-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                    </div>
                                    <p id="file-name" class="text-sm text-gray-600"></p>
                                </div>
                                <div id="file-placeholder">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                        viewBox="0 0 48 48">
                                        <path
                                            d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="text-sm text-gray-600">
                                        <label for="file_path"
                                            class="relative cursor-pointer bg-white rounded-md font-medium text-primary-600 hover:text-primary-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary-500">
                                            <span>{{ $modul->file_path ? 'Upload file baru' : 'Upload file' }}</span>
                                            <input id="file_path" name="file_path" type="file" class="sr-only"
                                                accept=".pdf,.doc,.docx,.ppt,.pptx" onchange="previewFile(event)">
                                        </label>
                                        <p class="pl-1">atau drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PDF, DOC, DOCX, PPT, PPTX hingga 10MB</p>
                                    @if ($modul->file_path)
                                        <p class="text-xs text-primary-600 mt-1">Kosongkan jika tidak ingin mengubah file
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @error('file_path')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Link Video -->
                    <div>
                        <label for="link_video" class="block text-sm font-medium text-gray-700 mb-2">
                            Link Video
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            <input type="url" name="link_video" id="link_video"
                                value="{{ old('link_video', $modul->link_video) }}"
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('link_video') border-red-300 @enderror"
                                placeholder="https://youtube.com/watch?v=...">
                        </div>
                        @error('link_video')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">URL video YouTube, Vimeo, atau platform lainnya</p>
                    </div>

                    <!-- Upload Gambar Baru -->
                    <div>
                        <label for="images" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ $modul->images ? 'Ganti Gambar Modul' : 'Upload Gambar Modul' }}
                        </label>
                        <div
                            class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 transition-colors">
                            <div class="space-y-1 text-center">
                                <div id="image-preview" class="hidden">
                                    <img id="preview-img" src="" alt="Preview"
                                        class="mx-auto h-32 w-auto rounded-lg">
                                </div>
                                <div id="image-placeholder">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                        viewBox="0 0 48 48">
                                        <path
                                            d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="text-sm text-gray-600">
                                        <label for="images"
                                            class="relative cursor-pointer bg-white rounded-md font-medium text-primary-600 hover:text-primary-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary-500">
                                            <span>{{ $modul->images ? 'Upload gambar baru' : 'Upload gambar' }}</span>
                                            <input id="images" name="images" type="file" class="sr-only"
                                                accept="image/*" onchange="previewImage(event)">
                                        </label>
                                        <p class="pl-1">atau drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG, GIF hingga 2MB</p>
                                    @if ($modul->images)
                                        <p class="text-xs text-primary-600 mt-1">Kosongkan jika tidak ingin mengubah gambar
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @error('images')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                        <a href="{{ route('admin.moduls.show', $modul) }}"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
                            Batal
                        </a>
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-primary-600 border border-transparent rounded-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                            </svg>
                            Update Modul
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function previewFile(event) {
            const file = event.target.files[0];
            if (file) {
                document.getElementById('file-name').textContent = file.name;
                document.getElementById('file-preview').classList.remove('hidden');
                document.getElementById('file-placeholder').classList.add('hidden');
            }
        }

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
    </script>
@endsection
