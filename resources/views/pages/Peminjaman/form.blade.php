<x-app-layout>
    <!-- CSRF Token for AJAX requests -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="min-h-screen flex flex-col px-4 md:px-48">
        <div class="flex justify-center mt-32 relative">
            <a class="absolute left-0 flex-none text-black" href="{{ route('peminjaman') }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
            </a>
            <h1 class="text-xl md:text-2xl font-bold self-center">Pinjam Barang</h1>
        </div>

        <!-- Loading indicator -->
        <div id="loading-indicator" class="hidden fixed top-0 left-0 w-full h-full bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center">
            <div class="bg-white p-4 rounded-lg">
                <div class="flex items-center">
                    <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-dark-digikom"></div>
                    <span class="ml-2">Memvalidasi ketersediaan barang...</span>
                </div>
            </div>
        </div>

        <form action="{{ route('peminjaman.quantity') }}" method="POST"
            class="mt-8 md:mt-12 flex flex-col items-center" id="peminjaman-form">
            @csrf

            <div class="flex flex-col w-full max-w-96 relative">
                <label for="tanggal_peminjaman"
                    class="absolute bottom-9 left-8 bg-white text-sm font-regular px-1">Tanggal
                    pinjam</label>
                <input type="date" id="tanggal_peminjaman" name="tanggal_peminjaman"
                    class="rounded-3xl px-4 py-3 text-sm focus:border-dark-green focus:ring-dark-green w-full"
                    min="{{ date('Y-m-d') }}" required>
            </div>

            <div class="flex flex-col w-full max-w-96 relative mt-8">
                <label for="tanggal_selesai" class="absolute bottom-9 left-8 bg-white text-sm font-regular px-1">Tanggal
                    selesai</label>
                <input type="date" id="tanggal_selesai" name="tanggal_selesai"
                    class="rounded-3xl px-4 py-3 text-sm border focus:border-dark-green focus:ring-dark-green w-full"
                    required>
            </div>

            <div class="flex flex-col w-full max-w-96 relative mt-8">
                <label for="alasan" class="absolute bottom-9 left-8 bg-white text-sm font-regular px-1">Alasan
                    Peminjaman</label>
                <input type="text" id="alasan" name="alasan"
                    class="rounded-3xl px-4 py-3 text-sm border focus:border-dark-green focus:ring-dark-green w-full"
                    placeholder="Sertakan alasan yang jelas" minlength="10" required>
            </div>

            <div class="flex flex-col w-full max-w-96 relative mt-8">
                <label for="barang_search" class="absolute bottom-9 left-8 bg-white text-sm font-regular px-1">Barang
                    yang dipinjam</label>
                <div class="dropdown w-full">
                    <input type="text" id="barang_search"
                        class="rounded-3xl px-4 py-3 text-sm border w-full focus:border-dark-green focus:ring-dark-green"
                        placeholder="Pilih atau cari barang yang dipinjam" autocomplete="off">
                    <div id="dropdown-content"
                        class="dropdown-content hidden absolute z-10 w-full mt-1 bg-white border rounded-md shadow-lg max-h-60 overflow-y-auto">
                        @if($inventaris->count() > 0)
                            @foreach ($inventaris as $item)
                                @if ($item['is_available'])
                                    <div class="p-3 hover:bg-gray-100 cursor-pointer text-sm item-option"
                                        data-id="{{ $item['id'] }}" data-nama="{{ $item['nama'] }}"
                                        data-tersedia="{{ $item['tersedia'] }}" data-kuantitas="{{ $item['kuantitas'] }}">
                                        <div class="font-medium">{{ $item['nama'] }}</div>
                                        <div class="text-xs text-gray-500 stock-info-{{ $item['id'] }}">
                                            Tersedia: <span class="tersedia-{{ $item['id'] }}">{{ $item['tersedia'] }}</span> dari {{ $item['kuantitas'] }}
                                            @if($item['tersedia'] <= 5)
                                                <span class="text-red-500 font-medium ml-1">⚠️ Terbatas!</span>
                                            @endif
                                        </div>
                                        @if($item['deskripsi'])
                                            <div class="text-xs text-gray-400 mt-1">{{ Str::limit($item['deskripsi'], 50) }}</div>
                                        @endif
                                    </div>
                                @endif
                            @endforeach
                        @else
                            <div class="p-3 text-sm text-gray-500">
                                Tidak ada barang yang tersedia saat ini
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div id="selected-items" class="flex flex-wrap w-full max-w-96 gap-2 mt-2">
                <!-- Selected items will be displayed here -->
            </div>

            <!-- Stock refresh button -->
            <div class="flex justify-center w-full max-w-96 mt-4">
                <button type="button" id="refresh-stock"
                    class="text-sm text-blue-600 hover:text-blue-800 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Refresh Stok
                </button>
            </div>

            <button type="submit" id="submit-btn"
                class="bg-dark-digikom text-white w-full max-w-96 rounded-3xl py-3 mt-8 disabled:opacity-50 disabled:cursor-not-allowed">
                Selanjutnya
            </button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dropdownContent = document.getElementById('dropdown-content');
            const barangSearch = document.getElementById('barang_search');
            const selectedItemsContainer = document.getElementById('selected-items');
            const form = document.getElementById('peminjaman-form');
            const submitBtn = document.getElementById('submit-btn');
            const refreshBtn = document.getElementById('refresh-stock');
            const loadingIndicator = document.getElementById('loading-indicator');

            // State management
            let selectedItems = [];
            let stockCheckTimeout;

            // Initialize form state
            submitBtn.disabled = true;

            // Set minimum date for tanggal_selesai based on tanggal_peminjaman
            document.getElementById('tanggal_peminjaman').addEventListener('change', function() {
                const startDate = new Date(this.value);
                const minEndDate = new Date(startDate);
                minEndDate.setDate(minEndDate.getDate() + 1);

                document.getElementById('tanggal_selesai').min = minEndDate.toISOString().split('T')[0];
            });

            // Dropdown functionality
            barangSearch.addEventListener('focus', function() {
                dropdownContent.classList.remove('hidden');
            });

            document.addEventListener('click', function(event) {
                if (!event.target.closest('.dropdown')) {
                    dropdownContent.classList.add('hidden');
                }
            });

            // Search functionality
            barangSearch.addEventListener('input', function() {
                const query = this.value.toLowerCase();
                const options = document.querySelectorAll('.item-option');

                options.forEach(option => {
                    const text = option.textContent.toLowerCase();
                    option.style.display = text.includes(query) ? 'block' : 'none';
                });
            });

            // Item selection
            document.querySelectorAll('.item-option').forEach(option => {
                option.addEventListener('click', function() {
                    const itemId = this.getAttribute('data-id');
                    const itemNama = this.getAttribute('data-nama');
                    const itemTersedia = parseInt(this.getAttribute('data-tersedia'));

                    if (!selectedItems.includes(itemId) && itemTersedia > 0) {
                        addSelectedItem(itemId, itemNama);
                        selectedItems.push(itemId);
                        updateSubmitButton();
                    }

                    barangSearch.value = '';
                    dropdownContent.classList.add('hidden');
                });
            });

            // Add selected item tag
            function addSelectedItem(id, nama) {
                const tag = document.createElement('div');
                tag.className = 'flex items-center bg-gray-200 rounded-full px-3 py-1 text-sm border border-black';
                tag.innerHTML = `
                    <span>${nama}</span>
                    <button type="button" class="ml-2 text-gray-500 hover:text-gray-700" data-id="${id}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="black" viewBox="0 0 16 16">
                            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                        </svg>
                    </button>
                `;

                // Add hidden input
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'id_inventaris[]';
                hiddenInput.value = id;
                tag.appendChild(hiddenInput);

                // Remove functionality
                tag.querySelector('button').addEventListener('click', function() {
                    const itemId = this.getAttribute('data-id');
                    selectedItems = selectedItems.filter(id => id !== itemId);
                    tag.remove();
                    updateSubmitButton();
                });

                selectedItemsContainer.appendChild(tag);
            }

            // Update submit button state
            function updateSubmitButton() {
                submitBtn.disabled = selectedItems.length === 0;
            }

            // Real-time stock checking
            function checkStockAvailability(inventarisIds) {
                if (inventarisIds.length === 0) return Promise.resolve([]);

                return fetch('/peminjaman/check-availability', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ ids: inventarisIds })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .catch(error => {
                    console.error('Error checking stock:', error);
                    return [];
                });
            }

            // Update stock display
            function updateStockDisplay(stockData) {
                stockData.forEach(item => {
                    const option = document.querySelector(`[data-id="${item.id}"]`);
                    if (option) {
                        // Update data attributes
                        option.setAttribute('data-tersedia', item.tersedia);
                        option.setAttribute('data-kuantitas', item.kuantitas);

                        // Update display
                        const stockInfo = option.querySelector(`.stock-info-${item.id}`);
                        const tersediaSpan = option.querySelector(`.tersedia-${item.id}`);

                        if (stockInfo && tersediaSpan) {
                            tersediaSpan.textContent = item.tersedia;

                            // Update warning
                            const warningSpan = stockInfo.querySelector('.text-red-500');
                            if (item.tersedia <= 5 && !warningSpan) {
                                stockInfo.innerHTML += ' <span class="text-red-500 font-medium ml-1">⚠️ Terbatas!</span>';
                            } else if (item.tersedia > 5 && warningSpan) {
                                warningSpan.remove();
                            }
                        }

                        // Hide/show based on availability
                        if (!item.is_available) {
                            option.style.display = 'none';
                            // Remove from selected if no longer available
                            if (selectedItems.includes(item.id.toString())) {
                                removeFromSelected(item.id.toString());
                                showNotification(`${item.nama} sudah tidak tersedia`, 'warning');
                            }
                        } else {
                            option.style.display = 'block';
                        }
                    }
                });
            }

            // Remove item from selected
            function removeFromSelected(itemId) {
                const selectedTag = document.querySelector(`button[data-id="${itemId}"]`);
                if (selectedTag) {
                    selectedTag.click();
                }
            }

            // Show notification
            function showNotification(message, type = 'info') {
                let notification = document.getElementById('stock-notification');
                if (!notification) {
                    notification = document.createElement('div');
                    notification.id = 'stock-notification';
                    document.body.appendChild(notification);
                }

                const typeClasses = {
                    'info': 'bg-blue-100 border-blue-400 text-blue-800',
                    'warning': 'bg-yellow-100 border-yellow-400 text-yellow-800',
                    'error': 'bg-red-100 border-red-400 text-red-800',
                    'success': 'bg-green-100 border-green-400 text-green-800'
                };

                notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 max-w-sm border-l-4 ${typeClasses[type] || typeClasses['info']}`;
                notification.innerHTML = `
                    <div class="flex items-start">
                        <div class="ml-3 w-0 flex-1">
                            <p class="text-sm font-medium">${message}</p>
                        </div>
                        <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-gray-400 hover:text-gray-600">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>
                `;

                setTimeout(() => {
                    if (notification && notification.parentElement) {
                        notification.remove();
                    }
                }, 5000);
            }

            // Refresh stock button
            refreshBtn.addEventListener('click', function() {
                this.disabled = true;
                this.innerHTML = '<div class="animate-spin rounded-full h-4 w-4 border-b-2 border-blue-600 mr-1"></div>Memuat...';

                const allIds = Array.from(document.querySelectorAll('.item-option')).map(option => option.getAttribute('data-id'));

                checkStockAvailability(allIds).then(stockData => {
                    updateStockDisplay(stockData);
                    showNotification('Stok berhasil diperbarui', 'success');
                }).catch(error => {
                    showNotification('Gagal memperbarui stok', 'error');
                }).finally(() => {
                    this.disabled = false;
                    this.innerHTML = `
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Refresh Stok
                    `;
                });
            });

            // Auto-refresh stock every 30 seconds for selected items
            setInterval(() => {
                if (selectedItems.length > 0) {
                    checkStockAvailability(selectedItems).then(stockData => {
                        updateStockDisplay(stockData);

                        const unavailableItems = stockData.filter(item => !item.is_available);
                        if (unavailableItems.length > 0) {
                            const itemNames = unavailableItems.map(item => item.nama).join(', ');
                            showNotification(`Perhatian: ${itemNames} sudah tidak tersedia`, 'warning');
                        }
                    });
                }
            }, 30000);

            // Form submission with final validation
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                if (selectedItems.length === 0) {
                    showNotification('Pilih minimal satu barang yang akan dipinjam', 'error');
                    return;
                }

                // Show loading
                loadingIndicator.classList.remove('hidden');
                submitBtn.disabled = true;

                // Final stock validation
                checkStockAvailability(selectedItems).then(stockData => {
                    const unavailableItems = stockData.filter(item => !item.is_available);

                    if (unavailableItems.length > 0) {
                        const itemNames = unavailableItems.map(item => item.nama).join(', ');
                        showNotification(`Barang berikut sudah tidak tersedia: ${itemNames}. Silakan refresh halaman.`, 'error');
                        return;
                    }

                    // All available, submit form
                    this.submit();
                }).catch(error => {
                    console.error('Error checking stock before submit:', error);
                    // Submit anyway if error
                    this.submit();
                }).finally(() => {
                    loadingIndicator.classList.add('hidden');
                    submitBtn.disabled = selectedItems.length === 0;
                });
            });
        });
    </script>
</x-app-layout>
