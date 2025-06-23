{{-- resources/views/admin/peminjaman/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Peminjaman')
@section('page-title', 'Management Peminjaman')

@section('content')
    <div class="space-y-6">
        <!-- Header with Stats -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Peminjaman</h2>
                    <p class="mt-1 text-sm text-gray-600">Kelola semua peminjaman inventaris</p>
                </div>
                <div class="mt-4 lg:mt-0">
                    <a href="{{ route('admin.peminjaman.create') }}"
                        class="inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Buat Peminjaman
                    </a>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mt-6">
                <div class="bg-blue-50 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-blue-900">Total</p>
                            <p class="text-lg font-semibold text-blue-900">{{ $stats['total'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-yellow-50 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-yellow-900">Pending</p>
                            <p class="text-lg font-semibold text-yellow-900">{{ $stats['pending'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-green-50 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-900">Disetujui</p>
                            <p class="text-lg font-semibold text-green-900">{{ $stats['approved'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-blue-50 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4">
                                </path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-blue-900">Aktif</p>
                            <p class="text-lg font-semibold text-blue-900">{{ $stats['active'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-red-50 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.864-.833-2.634 0L4.168 18.5c-.77.833.192 2.5 1.732 2.5z">
                                </path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-red-900">Terlambat</p>
                            <p class="text-lg font-semibold text-red-900">{{ $stats['overdue'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-purple-50 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-purple-900">Selesai</p>
                            <p class="text-lg font-semibold text-purple-900">{{ $stats['returned'] }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter and Search -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <form method="GET" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input type="text" name="search" id="search" value="{{ request('search') }}"
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                placeholder="Nama atau NIM...">
                        </div>
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" id="status"
                            class="block w-full py-2 px-3 border border-gray-300 bg-white rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                            <option value="">Semua Status</option>
                            <option value="diajukan" {{ request('status') === 'diajukan' ? 'selected' : '' }}>Diajukan
                            </option>
                            <option value="disetujui" {{ request('status') === 'disetujui' ? 'selected' : '' }}>Disetujui
                            </option>
                            <option value="dipinjam" {{ request('status') === 'dipinjam' ? 'selected' : '' }}>Dipinjam
                            </option>
                            <option value="jatuh tenggat" {{ request('status') === 'jatuh tenggat' ? 'selected' : '' }}>
                                Jatuh Tenggat</option>
                            <option value="dikembalikan" {{ request('status') === 'dikembalikan' ? 'selected' : '' }}>
                                Dikembalikan</option>
                            <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak
                            </option>
                        </select>
                    </div>

                    <!-- Date From -->
                    <div>
                        <label for="date_from" class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                        <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}"
                            class="block w-full py-2 px-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    </div>

                    <!-- Date To -->
                    <div>
                        <label for="date_to" class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                        <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}"
                            class="block w-full py-2 px-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-2">
                    <button type="submit"
                        class="px-4 py-2 bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium rounded-lg transition-colors">
                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Filter
                    </button>
                    @if (request()->hasAny(['search', 'status', 'date_from', 'date_to']))
                        <a href="{{ route('admin.peminjaman.index') }}"
                            class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                            Reset
                        </a>
                    @endif
                    <a href="{{ route('admin.export.peminjaman', request()->query()) }}"
                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        Export PDF
                    </a>
                </div>
            </form>
        </div>

        <!-- Peminjaman Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Peminjam
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tanggal
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Jangka
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Barang
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($peminjaman as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div
                                            class="w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center">
                                            <span class="text-sm font-medium text-primary-800">
                                                {{ substr($item->user->name, 0, 1) }}
                                            </span>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $item->user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $item->user->nim }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <div>
                                        <div class="font-medium">{{ $item->tanggal_peminjaman->format('d M Y') }}</div>
                                        <div class="text-gray-500">s/d {{ $item->tanggal_selesai->format('d M Y') }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $item->jangka === 'pendek' ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800' }}">
                                        {{ ucfirst($item->jangka) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                {{ $item->status === 'diajukan' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $item->status === 'disetujui' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $item->status === 'dipinjam' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $item->status === 'dikembalikan' ? 'bg-purple-100 text-purple-800' : '' }}
                                {{ $item->status === 'jatuh tenggat' ? 'bg-red-100 text-red-800' : '' }}
                                {{ $item->status === 'ditolak' ? 'bg-gray-100 text-gray-800' : '' }}
                            ">
                                        {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">
                                        @foreach ($item->detailPeminjaman->take(2) as $detail)
                                            <div class="truncate">{{ $detail->inventaris->nama }}
                                                ({{ $detail->kuantitas }})</div>
                                        @endforeach
                                        @if ($item->detailPeminjaman->count() > 2)
                                            <div class="text-xs text-gray-500">+{{ $item->detailPeminjaman->count() - 2 }}
                                                lainnya</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <a href="{{ route('admin.peminjaman.show', $item) }}"
                                            class="text-primary-600 hover:text-primary-800 text-sm font-medium">
                                            Lihat
                                        </a>

                                        @if (in_array($item->status, ['diajukan', 'disetujui', 'dipinjam', 'jatuh tenggat']))
                                            <button onclick="updateStatus({{ $item->id }}, '{{ $item->status }}')"
                                                class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                                Update
                                            </button>
                                        @endif

                                        <a href="{{ route('admin.peminjaman.export', $item) }}"
                                            class="text-green-600 hover:text-green-800 text-sm font-medium">
                                            PDF
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada peminjaman</h3>
                                    <p class="mt-1 text-sm text-gray-500">Mulai dengan membuat peminjaman pertama.</p>
                                    <div class="mt-6">
                                        <a href="{{ route('admin.peminjaman.create') }}"
                                            class="inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-lg transition-colors">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                            Buat Peminjaman
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($peminjaman->hasPages())
                <div class="bg-white px-6 py-4 border-t border-gray-200">
                    {{ $peminjaman->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Status Update Modal -->
    <div id="statusModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Update Status Peminjaman</h3>
                <form id="statusForm">
                    <div class="mb-4">
                        <label for="newStatus" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select id="newStatus" name="status"
                            class="block w-full py-2 px-3 border border-gray-300 bg-white rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                            <option value="">Pilih Status</option>
                            <option value="disetujui">Disetujui</option>
                            <option value="ditolak">Ditolak</option>
                            <option value="dipinjam">Dipinjam</option>
                            <option value="dikembalikan">Dikembalikan</option>
                            <option value="jatuh tenggat">Jatuh Tenggat</option>
                        </select>
                    </div>

                    <div class="mb-4" id="tanggalPengembalianDiv" style="display: none;">
                        <label for="tanggalPengembalian" class="block text-sm font-medium text-gray-700 mb-2">Tanggal
                            Pengembalian</label>
                        <input type="date" id="tanggalPengembalian" name="tanggal_pengembalian"
                            class="block w-full py-2 px-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    </div>

                    <div class="mb-4">
                        <label for="catatan" class="block text-sm font-medium text-gray-700 mb-2">Catatan
                            (Opsional)</label>
                        <textarea id="catatan" name="catatan" rows="3"
                            class="block w-full py-2 px-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                            placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                    </div>

                    <div class="flex justify-end space-x-2">
                        <button type="button" onclick="closeStatusModal()"
                            class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 text-sm font-medium rounded-lg transition-colors">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-lg transition-colors">
                            Update Status
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let currentPeminjamanId = null;

        function updateStatus(peminjamanId, currentStatus) {
            currentPeminjamanId = peminjamanId;

            // Show appropriate status options based on current status
            const statusSelect = document.getElementById('newStatus');
            statusSelect.innerHTML = '<option value="">Pilih Status</option>';

            const allowedTransitions = {
                'diajukan': ['disetujui', 'ditolak'],
                'disetujui': ['dipinjam', 'ditolak'],
                'dipinjam': ['dikembalikan', 'jatuh tenggat'],
                'jatuh tenggat': ['dikembalikan']
            };

            if (allowedTransitions[currentStatus]) {
                allowedTransitions[currentStatus].forEach(status => {
                    const option = document.createElement('option');
                    option.value = status;
                    option.textContent = status.charAt(0).toUpperCase() + status.slice(1).replace('_', ' ');
                    statusSelect.appendChild(option);
                });
            }

            // Show modal
            document.getElementById('statusModal').classList.remove('hidden');
        }

        function closeStatusModal() {
            document.getElementById('statusModal').classList.add('hidden');
            document.getElementById('statusForm').reset();
            document.getElementById('tanggalPengembalianDiv').style.display = 'none';
            currentPeminjamanId = null;
        }

        // Show tanggal pengembalian when status is dikembalikan
        document.getElementById('newStatus').addEventListener('change', function() {
            const tanggalDiv = document.getElementById('tanggalPengembalianDiv');
            if (this.value === 'dikembalikan') {
                tanggalDiv.style.display = 'block';
                document.getElementById('tanggalPengembalian').value = new Date().toISOString().split('T')[0];
            } else {
                tanggalDiv.style.display = 'none';
            }
        });

        // Handle status form submission
        document.getElementById('statusForm').addEventListener('submit', function(e) {
            e.preventDefault();

            if (!currentPeminjamanId) return;

            const formData = new FormData(this);
            const data = Object.fromEntries(formData);

            fetch(`/admin/peminjaman/${currentPeminjamanId}/status`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Gagal mengupdate status: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat mengupdate status');
                });
        });

        // Close modal when clicking outside
        document.getElementById('statusModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeStatusModal();
            }
        });

        // Auto check for overdue peminjaman
        setInterval(function() {
            fetch('/admin/peminjaman/check-overdue', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                        'content')
                }
            });
        }, 300000); // Check every 5 minutes
    </script>
@endpush
