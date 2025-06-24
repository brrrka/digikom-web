{{-- resources/views/admin/peminjaman/edit.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Edit Peminjaman')
@section('page-title', 'Edit Peminjaman')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Edit Peminjaman
                            PD-{{ str_pad($peminjaman->id, 4, '0', STR_PAD_LEFT) }}</h2>
                        <p class="text-sm text-gray-600 mt-1">Perbarui informasi peminjaman</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('admin.peminjaman.show', $peminjaman->id) }}"
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
            </div>

            <!-- Form -->
            <form action="{{ route('admin.peminjaman.update', $peminjaman->id) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <!-- Current Info Display -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-sm font-medium text-gray-900 mb-3">Informasi Saat Ini</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                            <div>
                                <span class="text-gray-500">Peminjam:</span>
                                <div class="font-medium">{{ $peminjaman->user->name ?? 'Unknown User' }}</div>
                                <div class="text-xs text-gray-500">{{ $peminjaman->user->nim ?? 'No NIM' }}</div>
                            </div>
                            <div>
                                <span class="text-gray-500">Status:</span>
                                <div>
                                    @php
                                        $statusColors = [
                                            'diajukan' => 'bg-yellow-100 text-yellow-800',
                                            'disetujui' => 'bg-green-100 text-green-800',
                                            'dipinjam' => 'bg-blue-100 text-blue-800',
                                            'dikembalikan' => 'bg-gray-100 text-gray-800',
                                            'jatuh tenggat' => 'bg-red-100 text-red-800',
                                            'ditolak' => 'bg-red-100 text-red-800',
                                        ];
                                    @endphp
                                    <span
                                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$peminjaman->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($peminjaman->status) }}
                                    </span>
                                </div>
                            </div>
                            <div>
                                <span class="text-gray-500">Total Items:</span>
                                <div class="font-medium">{{ $peminjaman->detailPeminjaman->count() }} barang</div>
                            </div>
                        </div>
                    </div>

                    <!-- Warning for status restrictions -->
                    @if (in_array($peminjaman->status, ['dikembalikan', 'ditolak']))
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                            <div class="flex">
                                <svg class="w-5 h-5 text-red-400 mr-3 mt-0.5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.864-.833-2.634 0L4.168 18.5c-.77.833.192 2.5 1.732 2.5z">
                                    </path>
                                </svg>
                                <div>
                                    <h3 class="text-sm font-medium text-red-800">Peminjaman Tidak Dapat Diedit</h3>
                                    <p class="text-sm text-red-700 mt-1">Peminjaman dengan status
                                        "{{ $peminjaman->status }}" tidak dapat diedit.</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Tanggal Peminjaman (Read Only) -->
                            <div>
                                <label for="tanggal_peminjaman_display"
                                    class="block text-sm font-medium text-gray-700 mb-2">
                                    Tanggal Peminjaman
                                </label>
                                <input type="text" id="tanggal_peminjaman_display"
                                    value="{{ $peminjaman->tanggal_peminjaman->format('d/m/Y') }}"
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100 text-gray-700 cursor-not-allowed"
                                    readonly>
                                <p class="mt-1 text-xs text-gray-500">Tanggal peminjaman tidak dapat diubah</p>
                            </div>

                            <!-- Tanggal Selesai -->
                            <div>
                                <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700 mb-2">
                                    Tanggal Selesai <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="tanggal_selesai" id="tanggal_selesai"
                                    value="{{ old('tanggal_selesai', $peminjaman->tanggal_selesai->format('Y-m-d')) }}"
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('tanggal_selesai') border-red-300 @enderror"
                                    min="{{ $peminjaman->tanggal_peminjaman->addDay()->format('Y-m-d') }}" required>
                                @error('tanggal_selesai')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">
                                    Durasi saat ini:
                                    {{ $peminjaman->tanggal_peminjaman->diffInDays($peminjaman->tanggal_selesai) }} hari
                                    ({{ ucfirst($peminjaman->jangka) }})
                                </p>
                            </div>
                        </div>

                        <!-- Alasan -->
                        <div>
                            <label for="alasan" class="block text-sm font-medium text-gray-700 mb-2">
                                Alasan Peminjaman <span class="text-red-500">*</span>
                            </label>
                            <textarea name="alasan" id="alasan" rows="3"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('alasan') border-red-300 @enderror"
                                placeholder="Masukkan alasan peminjaman..." required>{{ old('alasan', $peminjaman->alasan) }}</textarea>
                            @error('alasan')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Catatan Admin -->
                        <div>
                            <label for="catatan" class="block text-sm font-medium text-gray-700 mb-2">
                                Catatan Admin (Opsional)
                            </label>
                            <textarea name="catatan" id="catatan" rows="3"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('catatan') border-red-300 @enderror"
                                placeholder="Tambahkan catatan untuk peminjam...">{{ old('catatan', $peminjaman->catatan) }}</textarea>
                            @error('catatan')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif

                    <!-- Items List (Read Only) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Barang yang Dipinjam ({{ $peminjaman->detailPeminjaman->count() }} item)
                        </label>
                        <div class="border border-gray-200 rounded-lg overflow-hidden bg-gray-50">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th scope="col"
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Barang
                                        </th>
                                        <th scope="col"
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Kuantitas
                                        </th>
                                        <th scope="col"
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($peminjaman->detailPeminjaman as $detail)
                                        <tr>
                                            <td class="px-4 py-4">
                                                <div class="flex items-center">
                                                    @if ($detail->inventaris->images)
                                                        <img class="h-8 w-8 rounded-lg object-cover mr-3"
                                                            src="{{ Storage::url($detail->inventaris->images) }}"
                                                            alt="{{ $detail->inventaris->nama }}">
                                                    @else
                                                        <div
                                                            class="h-8 w-8 bg-gray-200 rounded-lg flex items-center justify-center mr-3">
                                                            <svg class="w-4 h-4 text-gray-400" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5">
                                                                </path>
                                                            </svg>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $detail->inventaris->nama }}</div>
                                                        <div class="text-sm text-gray-500">
                                                            {{ $detail->inventaris->deskripsi ? Str::limit($detail->inventaris->deskripsi, 40) : 'Tidak ada deskripsi' }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $detail->kuantitas }} unit</div>
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap">
                                                <span
                                                    class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $detail->inventaris->status === 'tersedia' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ ucfirst($detail->inventaris->status) }}
                                                </span>
                                                <div class="text-xs text-gray-500 mt-1">
                                                    {{ $detail->inventaris->kuantitas - $detail->inventaris->total_dipinjam }}
                                                    tersedia
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <p class="mt-2 text-xs text-gray-500">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Daftar barang tidak dapat diubah. Untuk mengubah barang, buat peminjaman baru.
                        </p>
                    </div>

                    <!-- Submit Buttons -->
                    @if (!in_array($peminjaman->status, ['dikembalikan', 'ditolak']))
                        <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                            <a href="{{ route('admin.peminjaman.show', $peminjaman->id) }}"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
                                Batal
                            </a>
                            <button type="submit"
                                class="px-4 py-2 text-sm font-medium text-white bg-primary-600 border border-transparent rounded-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                                <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                </svg>
                                Update Peminjaman
                            </button>
                        </div>
                    @else
                        <div class="flex items-center justify-end pt-6 border-t border-gray-200">
                            <a href="{{ route('admin.peminjaman.show', $peminjaman->id) }}"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
                                Kembali ke Detail
                            </a>
                        </div>
                    @endif
                </div>
            </form>
        </div>

        <!-- Quick Actions Card -->
        @if (!in_array($peminjaman->status, ['dikembalikan', 'ditolak']))
            <div class="mt-6 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Aksi Cepat</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @if ($peminjaman->status === 'diajukan')
                        <button onclick="updateStatus('disetujui')"
                            class="inline-flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Setujui Peminjaman
                        </button>
                        <button onclick="updateStatus('ditolak')"
                            class="inline-flex items-center justify-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Tolak Peminjaman
                        </button>
                    @elseif($peminjaman->status === 'disetujui')
                        <button onclick="updateStatus('dipinjam')"
                            class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            Tandai Dipinjam
                        </button>
                        @if ($peminjaman->bukti_path)
                            <a href="{{ route('admin.peminjaman.export', $peminjaman->id) }}"
                                class="inline-flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                Download Surat
                            </a>
                        @endif
                    @elseif(in_array($peminjaman->status, ['dipinjam', 'jatuh tenggat']))
                        <button onclick="updateStatus('dikembalikan')"
                            class="inline-flex items-center justify-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Tandai Dikembalikan
                        </button>
                    @endif

                    <!-- Check Overdue Button (if applicable) -->
                    @if ($peminjaman->status === 'dipinjam' && $peminjaman->tanggal_selesai->isPast())
                        <button onclick="updateStatus('jatuh tenggat')"
                            class="inline-flex items-center justify-center px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.864-.833-2.634 0L4.168 18.5c-.77.833.192 2.5 1.732 2.5z">
                                </path>
                            </svg>
                            Tandai Jatuh Tenggat
                        </button>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tanggalSelesai = document.getElementById('tanggal_selesai');
            const tanggalPeminjaman = '{{ $peminjaman->tanggal_peminjaman->format('Y-m-d') }}';

            // Auto-calculate jangka waktu
            if (tanggalSelesai) {
                tanggalSelesai.addEventListener('change', function() {
                    const startDate = new Date(tanggalPeminjaman);
                    const endDate = new Date(this.value);

                    if (endDate <= startDate) {
                        alert('Tanggal selesai harus setelah tanggal peminjaman');
                        this.value = '{{ $peminjaman->tanggal_selesai->format('Y-m-d') }}';
                        return;
                    }

                    const diffTime = Math.abs(endDate - startDate);
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                    const jangka = diffDays <= 14 ? 'pendek' : 'panjang';

                    // Show duration info
                    const durationInfo = document.querySelector('.text-xs.text-gray-500');
                    if (durationInfo) {
                        durationInfo.textContent = `Durasi: ${diffDays} hari (${jangka})`;
                    }
                });
            }
        });

        function updateStatus(newStatus) {
            let confirmMessage = `Apakah Anda yakin ingin mengubah status ke ${newStatus}?`;

            if (newStatus === 'ditolak') {
                confirmMessage += '\n\nPeminjaman yang ditolak tidak dapat diubah lagi.';
            } else if (newStatus === 'dikembalikan') {
                confirmMessage += '\n\nBarang akan dikembalikan ke inventaris.';
            }

            if (!confirm(confirmMessage)) {
                return;
            }

            let additionalData = {};

            if (newStatus === 'dikembalikan') {
                additionalData.tanggal_pengembalian = new Date().toISOString();
            }

            fetch(`/admin/peminjaman/{{ $peminjaman->id }}/status`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        status: newStatus,
                        ...additionalData
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat mengupdate status');
                });
        }

        // Enhanced form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const tanggalSelesai = document.getElementById('tanggal_selesai');
            const alasan = document.getElementById('alasan');

            if (!tanggalSelesai.value) {
                e.preventDefault();
                alert('Tanggal selesai harus diisi');
                tanggalSelesai.focus();
                return;
            }

            if (!alasan.value.trim()) {
                e.preventDefault();
                alert('Alasan peminjaman harus diisi');
                alasan.focus();
                return;
            }

            if (alasan.value.trim().length < 10) {
                e.preventDefault();
                alert('Alasan peminjaman minimal 10 karakter');
                alasan.focus();
                return;
            }

            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML =
                '<svg class="w-4 h-4 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 0021.001 8c0 .522-.042 1.04-.122 1.548"></path></svg>Mengupdate...';
            submitBtn.disabled = true;
        });
    </script>
@endsection
