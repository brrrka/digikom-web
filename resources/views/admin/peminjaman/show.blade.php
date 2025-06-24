{{-- resources/views/admin/peminjaman/show.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Detail Peminjaman')
@section('page-title', 'Detail Peminjaman')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Detail Peminjaman PD-{{ str_pad($peminjaman->id, 4, '0', STR_PAD_LEFT) }}</h2>
                        <p class="text-sm text-gray-600 mt-1">Informasi lengkap peminjaman</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        @if(!in_array($peminjaman->status, ['dikembalikan', 'ditolak']))
                            <a href="{{ route('admin.peminjaman.edit', $peminjaman->id) }}"
                                class="inline-flex items-center px-3 py-2 text-sm font-medium text-blue-700 bg-blue-50 border border-blue-200 rounded-md hover:bg-blue-100 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                    </path>
                                </svg>
                                Edit
                            </a>
                        @endif
                        @if($peminjaman->bukti_path)
                            <a href="{{ route('admin.peminjaman.export', $peminjaman->id) }}"
                                class="inline-flex items-center px-3 py-2 text-sm font-medium text-green-700 bg-green-50 border border-green-200 rounded-md hover:bg-green-100 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Download Surat
                            </a>
                        @endif
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

            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- User Information -->
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Peminjam</h3>
                            <dl class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Nama</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $peminjaman->user->name ?? 'Unknown User' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">NIM</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $peminjaman->user->nim ?? 'Tidak ada NIM' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Email</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $peminjaman->user->email ?? 'Tidak ada email' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">No. Telepon</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $peminjaman->user->no_telp ?? 'Tidak ada nomor' }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Peminjaman Details -->
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Detail Peminjaman</h3>
                            <dl class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">ID Peminjaman</dt>
                                    <dd class="mt-1 text-sm font-semibold text-gray-900">PD-{{ str_pad($peminjaman->id, 4, '0', STR_PAD_LEFT) }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                                    <dd class="mt-1">
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
                                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $statusColors[$peminjaman->status] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ ucfirst($peminjaman->status) }}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Jangka Waktu</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($peminjaman->jangka) }} ({{ $peminjaman->tanggal_peminjaman->diffInDays($peminjaman->tanggal_selesai) }} hari)</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Tanggal Peminjaman</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $peminjaman->tanggal_peminjaman->format('d/m/Y') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Tanggal Selesai</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $peminjaman->tanggal_selesai->format('d/m/Y') }}</dd>
                                </div>
                                @if($peminjaman->tanggal_pengembalian)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Tanggal Pengembalian</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $peminjaman->tanggal_pengembalian->format('d/m/Y H:i') }}</dd>
                                    </div>
                                @endif
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Dibuat</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $peminjaman->created_at->format('d/m/Y H:i') }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>

                <!-- Alasan Peminjaman -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Alasan Peminjaman</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-900">{{ $peminjaman->alasan }}</p>
                    </div>
                </div>

                <!-- Catatan Admin -->
                @if($peminjaman->catatan)
                    <div class="mt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Catatan Admin</h3>
                        <div class="bg-blue-50 rounded-lg p-4">
                            <p class="text-sm text-blue-900">{{ $peminjaman->catatan }}</p>
                        </div>
                    </div>
                @endif

                <!-- Items List -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Barang yang Dipinjam ({{ $peminjaman->detailPeminjaman->count() }} item)</h3>
                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Barang
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Kuantitas
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status Inventaris
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($peminjaman->detailPeminjaman as $detail)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                @if($detail->inventaris->images)
                                                    <img class="h-10 w-10 rounded-lg object-cover mr-4" src="{{ Storage::url($detail->inventaris->images) }}" alt="{{ $detail->inventaris->nama }}">
                                                @else
                                                    <div class="h-10 w-10 bg-gray-200 rounded-lg flex items-center justify-center mr-4">
                                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5"></path>
                                                        </svg>
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">{{ $detail->inventaris->nama }}</div>
                                                    <div class="text-sm text-gray-500">{{ $detail->inventaris->deskripsi ? Str::limit($detail->inventaris->deskripsi, 50) : 'Tidak ada deskripsi' }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $detail->kuantitas }} unit</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $detail->inventaris->status === 'tersedia' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ ucfirst($detail->inventaris->status) }}
                                            </span>
                                            <div class="text-xs text-gray-500 mt-1">
                                                Total: {{ $detail->inventaris->kuantitas }} |
                                                Dipinjam: {{ $detail->inventaris->total_dipinjam }} |
                                                Tersedia: {{ $detail->inventaris->kuantitas - $detail->inventaris->total_dipinjam }}
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Status Update Actions -->
                @if(!in_array($peminjaman->status, ['dikembalikan', 'ditolak']))
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Update Status</h3>
                        <div class="bg-gray-50 rounded-lg p-6">
                            <form id="statusForm" class="space-y-4">
                                @csrf
                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status Baru</label>
                                    <select name="status" id="status" class="block w-full max-w-xs py-2 px-3 border border-gray-300 bg-white rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                        @php
                                            $allowedTransitions = [
                                                'diajukan' => ['disetujui', 'ditolak'],
                                                'disetujui' => ['dipinjam', 'ditolak'],
                                                'dipinjam' => ['dikembalikan', 'jatuh tenggat'],
                                                'jatuh tenggat' => ['dikembalikan'],
                                            ];
                                        @endphp
                                        <option value="">Pilih Status</option>
                                        @if(isset($allowedTransitions[$peminjaman->status]))
                                            @foreach($allowedTransitions[$peminjaman->status] as $nextStatus)
                                                <option value="{{ $nextStatus }}">{{ ucfirst($nextStatus) }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div>
                                    <label for="catatan" class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional)</label>
                                    <textarea name="catatan" id="catatan" rows="3"
                                        class="block w-full py-2 px-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                        placeholder="Tambahkan catatan untuk peminjam...">{{ $peminjaman->catatan }}</textarea>
                                </div>
                                <div id="tanggal_pengembalian_field" class="hidden">
                                    <label for="tanggal_pengembalian" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Pengembalian</label>
                                    <input type="datetime-local" name="tanggal_pengembalian" id="tanggal_pengembalian"
                                        class="block w-full max-w-xs py-2 px-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                        value="{{ now()->format('Y-m-d\TH:i') }}">
                                </div>
                                <div class="flex items-center space-x-3">
                                    <button type="submit"
                                        class="inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-lg transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                        </svg>
                                        Update Status
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif

                <!-- File Information -->
                @if($peminjaman->bukti_path)
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">File Surat Peminjaman</h3>
                        <div class="bg-green-50 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <svg class="w-8 h-8 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-green-900">Surat peminjaman tersedia</p>
                                        <p class="text-sm text-green-700">File: {{ basename($peminjaman->bukti_path) }}</p>
                                    </div>
                                </div>
                                <a href="{{ route('admin.peminjaman.export', $peminjaman->id) }}"
                                    class="inline-flex items-center px-3 py-2 text-sm font-medium text-green-700 bg-green-100 rounded-md hover:bg-green-200 transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Download
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const statusSelect = document.getElementById('status');
            const tanggalPengembalianField = document.getElementById('tanggal_pengembalian_field');
            const statusForm = document.getElementById('statusForm');

            // Show/hide tanggal pengembalian field
            statusSelect.addEventListener('change', function() {
                if (this.value === 'dikembalikan') {
                    tanggalPengembalianField.classList.remove('hidden');
                } else {
                    tanggalPengembalianField.classList.add('hidden');
                }
            });

            // Handle form submission
            statusForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const status = formData.get('status');

                if (!status) {
                    alert('Pilih status terlebih dahulu');
                    return;
                }

                if (!confirm(`Apakah Anda yakin ingin mengubah status ke ${status}?`)) {
                    return;
                }

                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<svg class="w-4 h-4 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 0021.001 8c0 .522-.042 1.04-.122 1.548"></path></svg>Mengupdate...';
                submitBtn.disabled = true;

                const data = {
                    status: status,
                    catatan: formData.get('catatan'),
                };

                if (status === 'dikembalikan') {
                    data.tanggal_pengembalian = formData.get('tanggal_pengembalian');
                }

                fetch(`/admin/peminjaman/{{ $peminjaman->id }}/status`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat mengupdate status');
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                });
            });
        });
    </script>
@endsection
