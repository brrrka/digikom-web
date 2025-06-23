{{-- resources/views/admin/inventaris/edit.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Edit Inventaris')
@section('page-title', 'Edit Inventaris')

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Edit Inventaris</h2>
                        <p class="text-sm text-gray-600 mt-1">Perbarui informasi inventaris: {{ $inventaris->nama }}</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('admin.inventaris.show', $inventaris->id) }}"
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

            <!-- Form -->
            <form action="{{ route('admin.inventaris.update', $inventaris->id) }}" method="POST"
                enctype="multipart/form-data" class="p-6">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <!-- Current Image Preview -->
                    @if ($inventaris->images)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Gambar Saat Ini</label>
                            <div class="flex items-start space-x-4">
                                <img src="{{ Storage::url($inventaris->images) }}" alt="{{ $inventaris->nama }}"
                                    class="h-32 w-32 object-cover rounded-lg border border-gray-300">
                                <div class="flex-1">
                                    <p class="text-sm text-gray-600">
                                        Gambar saat ini untuk inventaris ini. Anda dapat mengupload gambar baru di bawah
                                        untuk menggantinya.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Nama Inventaris -->
                    <div>
                        <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Inventaris <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama" id="nama" value="{{ old('nama', $inventaris->nama) }}"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('nama') border-red-300 @enderror"
                            placeholder="Masukkan nama inventaris..." required>
                        @error('nama')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Kuantitas -->
                        <div>
                            <label for="kuantitas" class="block text-sm font-medium text-gray-700 mb-2">
                                Kuantitas <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="kuantitas" id="kuantitas"
                                value="{{ old('kuantitas', $inventaris->kuantitas) }}"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('kuantitas') border-red-300 @enderror"
                                placeholder="0" min="0" required>
                            @error('kuantitas')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            {{-- PERBAIKAN: Hilangkan perhitungan dipinjam sementara karena tabel belum ada --}}
                            {{-- @php
                                $totalDipinjam = $inventaris->detailPeminjaman->where('peminjaman.status', 'dipinjam')->sum('kuantitas');
                            @endphp
                            @if ($totalDipinjam > 0)
                                <p class="mt-1 text-xs text-orange-600">
                                    <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.864-.833-2.634 0L4.168 18.5c-.77.833.192 2.5 1.732 2.5z">
                                        </path>
                                    </svg>
                                    {{ $totalDipinjam }} unit sedang dipinjam
                                </p>
                            @endif --}}
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select name="status" id="status"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('status') border-red-300 @enderror"
                                required>
                                <option value="">Pilih Status</option>
                                <option value="tersedia"
                                    {{ old('status', $inventaris->status) === 'tersedia' ? 'selected' : '' }}>
                                    Tersedia
                                </option>
                                <option value="tidak tersedia"
                                    {{ old('status', $inventaris->status) === 'tidak tersedia' ? 'selected' : '' }}>
                                    Tidak Tersedia
                                </option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">
                            Deskripsi
                        </label>
                        <textarea name="deskripsi" id="deskripsi" rows="4"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('deskripsi') border-red-300 @enderror"
                            placeholder="Masukkan deskripsi inventaris...">{{ old('deskripsi', $inventaris->deskripsi) }}</textarea>
                        @error('deskripsi')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Upload Gambar Baru -->
                    <div>
                        <label for="images" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ $inventaris->images ? 'Ganti Gambar' : 'Upload Gambar Baru' }}
                        </label>
                        <div
                            class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 transition-colors">
                            <div class="space-y-1 text-center">
                                <div id="image-preview" class="hidden">
                                    <img id="preview-img" src="" alt="Preview"
                                        class="mx-auto h-32 w-auto rounded-lg">
                                </div>
                                <div id="upload-placeholder">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                        viewBox="0 0 48 48">
                                        <path
                                            d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="text-sm text-gray-600">
                                        <label for="images"
                                            class="relative cursor-pointer bg-white rounded-md font-medium text-primary-600 hover:text-primary-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary-500">
                                            <span>{{ $inventaris->images ? 'Upload gambar baru' : 'Upload gambar' }}</span>
                                            <input id="images" name="images" type="file" class="sr-only"
                                                accept="image/*" onchange="previewImage(event)">
                                        </label>
                                        <p class="pl-1">atau drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG, GIF hingga 2MB</p>
                                    @if ($inventaris->images)
                                        <p class="text-xs text-gray-500 mt-1 text-primary-600">
                                            Kosongkan jika tidak ingin mengubah gambar
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
                        <a href="{{ route('admin.inventaris.show', $inventaris->id) }}"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
                            Batal
                        </a>
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-primary-600 border border-transparent rounded-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                            </svg>
                            Update Inventaris
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function previewImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('preview-img').src = e.target.result;
                    document.getElementById('image-preview').classList.remove('hidden');
                    document.getElementById('upload-placeholder').classList.add('hidden');
                };
                reader.readAsDataURL(file);
            }
        }

        // Warning when reducing quantity below borrowed amount - DISABLE SEMENTARA
        document.addEventListener('DOMContentLoaded', function() {
            const kuantitasInput = document.getElementById('kuantitas');
            const borrowedAmount = 0; // Set 0 dulu karena tabel belum ada

            // Disabled untuk sementara
            /*
            kuantitasInput.addEventListener('input', function() {
                const currentValue = parseInt(this.value);

                if (borrowedAmount > 0 && currentValue < borrowedAmount) {
                    if (!document.getElementById('quantity-warning')) {
                        const warning = document.createElement('p');
                        warning.id = 'quantity-warning';
                        warning.className = 'mt-1 text-xs text-red-600';
                        warning.innerHTML = '<svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.864-.833-2.634 0L4.168 18.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>Kuantitas tidak boleh kurang dari jumlah yang sedang dipinjam (' + borrowedAmount + ' unit)';
                        this.parentNode.appendChild(warning);
                        this.classList.add('border-red-300');
                    }
                } else {
                    const warning = document.getElementById('quantity-warning');
                    if (warning) {
                        warning.remove();
                        this.classList.remove('border-red-300');
                    }
                }
            });
            */
        });
    </script>
@endsection
