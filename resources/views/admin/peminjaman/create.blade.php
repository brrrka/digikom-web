{{-- resources/views/admin/peminjaman/create.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Tambah Peminjaman')
@section('page-title', 'Tambah Peminjaman')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Tambah Peminjaman Baru</h2>
                        <p class="text-sm text-gray-600 mt-1">Buat peminjaman untuk user tertentu</p>
                    </div>
                    <a href="{{ route('admin.peminjaman.index') }}"
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
            <form action="{{ route('admin.peminjaman.store') }}" method="POST" class="p-6">
                @csrf

                <div class="space-y-6">
                    <!-- User Selection -->
                    <div>
                        <label for="id_users" class="block text-sm font-medium text-gray-700 mb-2">
                            Pilih User <span class="text-red-500">*</span>
                        </label>
                        <select name="id_users" id="id_users"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('id_users') border-red-300 @enderror"
                            required>
                            <option value="">Pilih User</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('id_users') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                    @if($user->nim)
                                        ({{ $user->nim }})
                                    @endif
                                    - {{ $user->email }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_users')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Tanggal Peminjaman -->
                        <div>
                            <label for="tanggal_peminjaman" class="block text-sm font-medium text-gray-700 mb-2">
                                Tanggal Peminjaman <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="tanggal_peminjaman" id="tanggal_peminjaman"
                                value="{{ old('tanggal_peminjaman', date('Y-m-d')) }}"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('tanggal_peminjaman') border-red-300 @enderror"
                                required>
                            @error('tanggal_peminjaman')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tanggal Selesai -->
                        <div>
                            <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700 mb-2">
                                Tanggal Selesai <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="tanggal_selesai" id="tanggal_selesai"
                                value="{{ old('tanggal_selesai') }}"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('tanggal_selesai') border-red-300 @enderror"
                                required>
                            @error('tanggal_selesai')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Alasan -->
                    <div>
                        <label for="alasan" class="block text-sm font-medium text-gray-700 mb-2">
                            Alasan Peminjaman <span class="text-red-500">*</span>
                        </label>
                        <textarea name="alasan" id="alasan" rows="3"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('alasan') border-red-300 @enderror"
                            placeholder="Masukkan alasan peminjaman..." required>{{ old('alasan') }}</textarea>
                        @error('alasan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Items Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Barang yang Dipinjam <span class="text-red-500">*</span>
                        </label>
                        <div class="border border-gray-300 rounded-lg p-4 bg-gray-50">
                            <div class="mb-4">
                                <div class="relative">
                                    <input type="text" id="inventaris_search"
                                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                        placeholder="Cari inventaris...">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <div id="selected_items_container" class="space-y-3 mb-4">
                                <!-- Selected items will be dynamically added here -->
                            </div>

                            <div class="max-h-60 overflow-y-auto border border-gray-200 rounded-lg bg-white">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Pilih
                                            </th>
                                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Inventaris
                                            </th>
                                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Tersedia
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200" id="inventaris_list">
                                        @foreach($inventaris as $item)
                                            @php
                                                $tersedia = $item->kuantitas - $item->total_dipinjam;
                                            @endphp
                                            @if($tersedia > 0)
                                                <tr class="inventaris-row hover:bg-gray-50" data-id="{{ $item->id }}" data-nama="{{ $item->nama }}" data-tersedia="{{ $tersedia }}">
                                                    <td class="px-4 py-4 whitespace-nowrap">
                                                        <input type="checkbox" class="inventaris-checkbox h-4 w-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500"
                                                            data-id="{{ $item->id }}" data-nama="{{ $item->nama }}" data-tersedia="{{ $tersedia }}">
                                                    </td>
                                                    <td class="px-4 py-4">
                                                        <div class="flex items-center">
                                                            @if($item->images)
                                                                <img class="h-8 w-8 rounded-lg object-cover mr-3" src="{{ Storage::url($item->images) }}" alt="{{ $item->nama }}">
                                                            @else
                                                                <div class="h-8 w-8 bg-gray-200 rounded-lg flex items-center justify-center mr-3">
                                                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5"></path>
                                                                    </svg>
                                                                </div>
                                                            @endif
                                                            <div>
                                                                <div class="text-sm font-medium text-gray-900">{{ $item->nama }}</div>
                                                                <div class="text-sm text-gray-500">{{ $item->deskripsi ? Str::limit($item->deskripsi, 50) : 'Tidak ada deskripsi' }}</div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="px-4 py-4 whitespace-nowrap">
                                                        <div class="text-sm text-gray-900">{{ $tersedia }} / {{ $item->kuantitas }}</div>
                                                        <div class="text-xs text-gray-500">{{ $item->total_dipinjam }} dipinjam</div>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @error('items')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                        <a href="{{ route('admin.peminjaman.index') }}"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
                            Batal
                        </a>
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-primary-600 border border-transparent rounded-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Simpan Peminjaman
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('inventaris_search');
            const inventarisRows = document.querySelectorAll('.inventaris-row');
            const checkboxes = document.querySelectorAll('.inventaris-checkbox');
            const selectedItemsContainer = document.getElementById('selected_items_container');
            const form = document.querySelector('form');

            let selectedItems = {};

            // Search functionality
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();

                inventarisRows.forEach(row => {
                    const nama = row.dataset.nama.toLowerCase();
                    if (nama.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });

            // Checkbox handling
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const id = this.dataset.id;
                    const nama = this.dataset.nama;
                    const tersedia = parseInt(this.dataset.tersedia);

                    if (this.checked) {
                        selectedItems[id] = {
                            nama: nama,
                            tersedia: tersedia,
                            kuantitas: 1
                        };
                        addSelectedItem(id, nama, tersedia);
                    } else {
                        delete selectedItems[id];
                        removeSelectedItem(id);
                    }
                });
            });

            function addSelectedItem(id, nama, tersedia) {
                const itemDiv = document.createElement('div');
                itemDiv.className = 'flex items-center justify-between bg-white p-3 rounded-lg border border-gray-200';
                itemDiv.id = `selected-item-${id}`;

                itemDiv.innerHTML = `
                    <div class="flex-1">
                        <div class="text-sm font-medium text-gray-900">${nama}</div>
                        <div class="text-xs text-gray-500">Tersedia: ${tersedia} unit</div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="flex items-center space-x-2">
                            <label class="text-sm text-gray-700">Qty:</label>
                            <input type="number"
                                class="quantity-input w-20 px-2 py-1 text-sm border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-primary-500"
                                value="1" min="1" max="${tersedia}"
                                data-id="${id}">
                        </div>
                        <button type="button" class="remove-item text-red-600 hover:text-red-800" data-id="${id}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <input type="hidden" name="items[${id}][id_inventaris]" value="${id}">
                    <input type="hidden" name="items[${id}][kuantitas]" value="1" class="hidden-quantity">
                `;

                selectedItemsContainer.appendChild(itemDiv);

                // Add event listeners
                const quantityInput = itemDiv.querySelector('.quantity-input');
                const removeButton = itemDiv.querySelector('.remove-item');
                const hiddenQuantity = itemDiv.querySelector('.hidden-quantity');

                quantityInput.addEventListener('input', function() {
                    const qty = parseInt(this.value) || 1;
                    if (qty > tersedia) {
                        this.value = tersedia;
                        alert(`Kuantitas maksimal untuk ${nama} adalah ${tersedia}`);
                    }
                    selectedItems[id].kuantitas = parseInt(this.value);
                    hiddenQuantity.value = this.value;
                });

                removeButton.addEventListener('click', function() {
                    const itemId = this.dataset.id;
                    delete selectedItems[itemId];
                    removeSelectedItem(itemId);

                    // Uncheck the checkbox
                    const checkbox = document.querySelector(`input[data-id="${itemId}"]`);
                    if (checkbox) {
                        checkbox.checked = false;
                    }
                });
            }

            function removeSelectedItem(id) {
                const itemDiv = document.getElementById(`selected-item-${id}`);
                if (itemDiv) {
                    itemDiv.remove();
                }
            }

            // Form validation
            form.addEventListener('submit', function(e) {
                if (Object.keys(selectedItems).length === 0) {
                    e.preventDefault();
                    alert('Pilih minimal satu barang untuk dipinjam');
                    return false;
                }

                // Validate quantities
                for (const [id, item] of Object.entries(selectedItems)) {
                    if (item.kuantitas > item.tersedia) {
                        e.preventDefault();
                        alert(`Kuantitas untuk ${item.nama} melebihi yang tersedia (${item.tersedia})`);
                        return false;
                    }
                }
            });

            // Auto-calculate end date
            const tanggalPeminjaman = document.getElementById('tanggal_peminjaman');
            const tanggalSelesai = document.getElementById('tanggal_selesai');

            tanggalPeminjaman.addEventListener('change', function() {
                if (this.value && !tanggalSelesai.value) {
                    const startDate = new Date(this.value);
                    const endDate = new Date(startDate);
                    endDate.setDate(endDate.getDate() + 7); // Default 7 days
                    tanggalSelesai.value = endDate.toISOString().split('T')[0];
                }
            });

            // Validation for dates
            tanggalSelesai.addEventListener('change', function() {
                const startDate = new Date(tanggalPeminjaman.value);
                const endDate = new Date(this.value);

                if (endDate <= startDate) {
                    alert('Tanggal selesai harus setelah tanggal peminjaman');
                    this.value = '';
                }
            });
        });
    </script>
@endsection
