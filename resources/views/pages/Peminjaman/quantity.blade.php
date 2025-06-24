<x-app-layout>
    <!-- CSRF Token for AJAX requests -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        // Fallback untuk variabel yang mungkin tidak ada
        $tanggal_peminjaman = $tanggal_peminjaman ?? (old('tanggal_peminjaman') ?? '');
        $tanggal_selesai = $tanggal_selesai ?? (old('tanggal_selesai') ?? '');
        $alasan = $alasan ?? (old('alasan') ?? '');
        $selectedItems = $selectedItems ?? collect();
    @endphp

    <div class="min-h-screen flex flex-col px-4 md:px-48">
        <div class="flex justify-center mt-32 relative">
            <a class="absolute left-0 flex-none text-black" href="{{ route('peminjaman.form') }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
            </a>
            <h1 class="text-2xl font-bold self-center">Pinjam barang</h1>
        </div>

        @if ($selectedItems->isEmpty())
            <div class="mt-12 mx-auto max-w-2xl">
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center">
                    <div class="text-yellow-800">
                        <svg class="w-12 h-12 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.963-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                        <h3 class="text-lg font-semibold mb-2">Tidak Ada Barang Dipilih</h3>
                        <p class="text-sm mb-4">Silakan kembali ke halaman sebelumnya untuk memilih barang yang akan
                            dipinjam.</p>
                        <a href="{{ route('peminjaman.form') }}"
                            class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Kembali ke Form
                        </a>
                    </div>
                </div>
            </div>
        @else
            <!-- Loading indicator -->
            <div id="loading-indicator"
                class="hidden fixed top-0 left-0 w-full h-full bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center">
                <div class="bg-white p-4 rounded-lg">
                    <div class="flex items-center">
                        <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-dark-digikom"></div>
                        <span class="ml-2">Memvalidasi ketersediaan barang...</span>
                    </div>
                </div>
            </div>

            <form action="{{ route('peminjaman.confirm') }}" method="POST" class="mt-12" id="quantity-form">
                @csrf
                <!-- Hidden fields to carry over data from previous form -->
                <input type="hidden" name="tanggal_peminjaman" value="{{ $tanggal_peminjaman }}">
                <input type="hidden" name="tanggal_selesai" value="{{ $tanggal_selesai }}">
                <input type="hidden" name="alasan" value="{{ $alasan }}">

                <div class="mx-auto max-w-2xl relative">
                    <div class="absolute -top-3 left-8 bg-white px-2 font-bold">Barang yang akan dipinjam</div>

                    <div class="bg-white rounded-2xl p-4 border-2 border-black shadow-sm divide-y">
                        <div class="flex flex-row justify-between px-8 mt-4 font-semibold">
                            <div class="w-2/3 text-left">Nama barang</div>
                            <div class="w-1/3 text-center">Jumlah yang dipinjam</div>
                        </div>

                        @foreach ($selectedItems as $item)
                            <div class="px-8 py-4 flex items-center item-row" data-id="{{ $item->id }}"
                                data-max="{{ $item->tersedia ?? 0 }}">
                                <div class="w-2/3">
                                    <div class="font-medium text-left">{{ $item->nama }}</div>
                                    <div class="text-sm text-gray-600 mt-1">
                                        Stok tersedia:
                                        <span
                                            class="font-semibold tersedia-{{ $item->id }}
                                            @if (($item->tersedia ?? 0) <= 3) text-red-600
                                            @elseif(($item->tersedia ?? 0) <= 10) text-yellow-600
                                            @else text-green-600 @endif">
                                            {{ $item->tersedia ?? 0 }} unit
                                        </span>
                                        @if (($item->tersedia ?? 0) <= 5)
                                            <span class="text-red-500 text-xs font-medium ml-1">⚠️ Terbatas!</span>
                                        @endif
                                    </div>
                                    @if ($item->deskripsi)
                                        <div class="text-xs text-gray-500 mt-1">{{ Str::limit($item->deskripsi, 60) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="w-1/3 flex items-center justify-center">
                                    <button type="button"
                                        class="decrease-btn py-2 px-3 text-gray-700 hover:bg-gray-100 rounded-l-lg border border-gray-300 transition-colors duration-200"
                                        data-id="{{ $item->id }}">−</button>
                                    <div class="w-24 text-center py-2">
                                        <span id="quantity-text-{{ $item->id }}"
                                            class="font-semibold text-lg">0</span>
                                        <div class="text-xs text-gray-500">max <span
                                                class="max-qty-{{ $item->id }}">{{ $item->tersedia ?? 0 }}</span>
                                        </div>
                                        <input type="hidden" name="kuantitas[{{ $item->id }}]"
                                            id="quantity-{{ $item->id }}" value="0">
                                    </div>
                                    <button type="button"
                                        class="increase-btn py-2 px-3 text-gray-700 hover:bg-gray-100 rounded-r-lg border border-gray-300 transition-colors duration-200"
                                        data-id="{{ $item->id }}" data-max="{{ $item->tersedia ?? 0 }}">+</button>
                                </div>
                            </div>
                            <input type="hidden" name="id_inventaris[]" value="{{ $item->id }}">
                        @endforeach
                    </div>

                    <!-- Summary Info -->
                    <div id="selection-summary" class="hidden mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="text-sm text-blue-800">
                            <div class="flex justify-between items-center mb-2">
                                <span class="font-semibold">Total barang dipinjam:</span>
                                <span id="total-quantity" class="font-bold text-lg">0 unit</span>
                            </div>
                            <div id="selected-items-list" class="text-xs text-blue-600"></div>
                        </div>
                    </div>

                    <!-- Warning untuk stok terbatas -->
                    <div id="stock-warning" class="hidden mt-4 p-4 bg-yellow-50 border border-yellow-400 rounded-lg">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-yellow-600 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <div class="text-sm text-yellow-800">
                                <div class="font-semibold mb-1">Perhatian!</div>
                                <div id="warning-text"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Stock refresh section -->
                    <div class="flex justify-center mt-4">
                        <button type="button" id="refresh-stock"
                            class="text-sm text-blue-600 hover:text-blue-800 flex items-center transition-colors duration-200">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                </path>
                            </svg>
                            <span>Refresh Stok</span>
                        </button>
                    </div>

                    <div class="text-xs text-black mt-4 mb-8 italic">
                        <strong>Catatan:</strong> Pastikan jumlah yang dipinjam sesuai kebutuhan. Stok yang ditampilkan
                        adalah stok tersedia saat ini dan akan diperbarui secara real-time.
                    </div>

                    <div class="flex justify-center">
                        <button type="submit" id="submit-btn"
                            class="bg-dark-digikom text-white w-2/3 rounded-3xl py-3 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200">
                            Selanjutnya
                        </button>
                    </div>
                </div>
            </form>
        @endif
    </div>

    @if (!$selectedItems->isEmpty())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const decreaseButtons = document.querySelectorAll('.decrease-btn');
                const increaseButtons = document.querySelectorAll('.increase-btn');
                const submitButton = document.getElementById('submit-btn');
                const selectionSummary = document.getElementById('selection-summary');
                const totalQuantityEl = document.getElementById('total-quantity');
                const selectedItemsList = document.getElementById('selected-items-list');
                const stockWarning = document.getElementById('stock-warning');
                const warningText = document.getElementById('warning-text');
                const refreshBtn = document.getElementById('refresh-stock');
                const loadingIndicator = document.getElementById('loading-indicator');
                const form = document.getElementById('quantity-form');

                // State management
                let stockData = {};

                // Initialize form state
                submitButton.disabled = true;
                submitButton.classList.add('opacity-50', 'cursor-not-allowed');

                // Initialize quantities to 0
                document.querySelectorAll('input[id^="quantity-"]').forEach(input => {
                    const id = input.id.replace('quantity-', '');
                    const quantityText = document.getElementById(`quantity-text-${id}`);
                    input.value = 0;
                    quantityText.textContent = 0;
                });

                // Update UI based on current selections
                function updateUI() {
                    let totalQuantity = 0;
                    let selectedItems = [];
                    let hasLowStock = false;
                    let lowStockItems = [];

                    document.querySelectorAll('input[id^="quantity-"]').forEach(input => {
                        const quantity = parseInt(input.value);
                        if (quantity > 0) {
                            totalQuantity += quantity;
                            const id = input.id.replace('quantity-', '');
                            const itemRow = document.querySelector(`[data-id="${id}"]`);
                            const itemName = itemRow.querySelector('.font-medium').textContent;
                            const maxStock = parseInt(itemRow.getAttribute('data-max'));

                            selectedItems.push(`${itemName}: ${quantity} unit`);

                            if (maxStock <= 5) {
                                hasLowStock = true;
                                lowStockItems.push(`${itemName} (sisa ${maxStock} unit)`);
                            }
                        }
                    });

                    // Update submit button
                    submitButton.disabled = totalQuantity === 0;
                    if (totalQuantity > 0) {
                        submitButton.classList.remove('opacity-50', 'cursor-not-allowed');
                    } else {
                        submitButton.classList.add('opacity-50', 'cursor-not-allowed');
                    }

                    // Update summary
                    if (totalQuantity > 0) {
                        selectionSummary.classList.remove('hidden');
                        totalQuantityEl.textContent = `${totalQuantity} unit`;
                        selectedItemsList.textContent = selectedItems.join(' • ');
                    } else {
                        selectionSummary.classList.add('hidden');
                    }

                    // Update warning
                    if (hasLowStock && totalQuantity > 0) {
                        stockWarning.classList.remove('hidden');
                        warningText.textContent =
                            `Barang dengan stok terbatas: ${lowStockItems.join(', ')}. Pastikan jumlah yang dipinjam sesuai kebutuhan.`;
                    } else {
                        stockWarning.classList.add('hidden');
                    }
                }

                // Decrease quantity
                decreaseButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const id = this.getAttribute('data-id');
                        const input = document.getElementById(`quantity-${id}`);
                        const quantityText = document.getElementById(`quantity-text-${id}`);
                        let value = parseInt(input.value);

                        if (value > 0) {
                            value -= 1;
                            input.value = value;
                            quantityText.textContent = value;
                            updateUI();
                        }
                    });
                });

                // Increase quantity
                increaseButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const id = this.getAttribute('data-id');
                        const availableQuantity = parseInt(this.getAttribute('data-max'));
                        const input = document.getElementById(`quantity-${id}`);
                        const quantityText = document.getElementById(`quantity-text-${id}`);
                        let value = parseInt(input.value);

                        if (value < availableQuantity) {
                            value += 1;
                            input.value = value;
                            quantityText.textContent = value;
                            updateUI();
                        } else {
                            const itemRow = this.closest('.item-row');
                            const itemName = itemRow.querySelector('.font-medium').textContent;
                            showNotification(
                                `Stok ${itemName} hanya tersedia ${availableQuantity} unit`,
                                'warning');
                        }
                    });
                });

                // Real-time stock checking
                function checkStockAvailability() {
                    const inventarisIds = Array.from(document.querySelectorAll('[data-id]')).map(el => el.getAttribute(
                        'data-id'));

                    if (inventarisIds.length === 0) return Promise.resolve([]);

                    return fetch('/peminjaman/check-availability', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content')
                            },
                            body: JSON.stringify({
                                ids: inventarisIds
                            })
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
                function updateStockDisplay(newStockData) {
                    newStockData.forEach(item => {
                        stockData[item.id] = item;

                        const itemRow = document.querySelector(`[data-id="${item.id}"]`);
                        if (itemRow) {
                            // Update data attributes
                            itemRow.setAttribute('data-max', item.tersedia);

                            // Update increase button max
                            const increaseBtn = itemRow.querySelector('.increase-btn');
                            increaseBtn.setAttribute('data-max', item.tersedia);

                            // Update display text
                            const tersediaSpan = itemRow.querySelector(`.tersedia-${item.id}`);
                            const maxQtySpan = itemRow.querySelector(`.max-qty-${item.id}`);

                            if (tersediaSpan) {
                                tersediaSpan.textContent = `${item.tersedia} unit`;

                                // Update color based on stock level
                                tersediaSpan.className = tersediaSpan.className.replace(
                                    /text-(red|yellow|green)-600/, '');
                                if (item.tersedia <= 3) {
                                    tersediaSpan.classList.add('text-red-600');
                                } else if (item.tersedia <= 10) {
                                    tersediaSpan.classList.add('text-yellow-600');
                                } else {
                                    tersediaSpan.classList.add('text-green-600');
                                }
                            }

                            if (maxQtySpan) {
                                maxQtySpan.textContent = item.tersedia;
                            }

                            // Check if current quantity exceeds available stock
                            const currentQty = parseInt(document.getElementById(`quantity-${item.id}`).value);
                            if (currentQty > item.tersedia) {
                                // Adjust quantity to maximum available
                                document.getElementById(`quantity-${item.id}`).value = item.tersedia;
                                document.getElementById(`quantity-text-${item.id}`).textContent = item.tersedia;

                                showNotification(
                                    `Jumlah ${item.nama} disesuaikan dengan stok tersedia (${item.tersedia} unit)`,
                                    'warning');
                            }

                            // Show warning if item is no longer available
                            if (!item.is_available) {
                                // Set quantity to 0
                                document.getElementById(`quantity-${item.id}`).value = 0;
                                document.getElementById(`quantity-text-${item.id}`).textContent = 0;

                                showNotification(`${item.nama} sudah tidak tersedia`, 'error');
                            }
                        }
                    });

                    updateUI();
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

                    notification.className =
                        `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 max-w-sm border-l-4 ${typeClasses[type] || typeClasses['info']}`;
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
                if (refreshBtn) {
                    refreshBtn.addEventListener('click', function() {
                        this.disabled = true;
                        const originalHTML = this.innerHTML;
                        this.innerHTML =
                            '<div class="animate-spin rounded-full h-4 w-4 border-b-2 border-blue-600 mr-1"></div>Memuat...';

                        checkStockAvailability().then(stockData => {
                            updateStockDisplay(stockData);
                            showNotification('Stok berhasil diperbarui', 'success');
                        }).catch(error => {
                            showNotification('Gagal memperbarui stok', 'error');
                        }).finally(() => {
                            this.disabled = false;
                            this.innerHTML = originalHTML;
                        });
                    });
                }

                // Auto-refresh stock every 30 seconds
                setInterval(() => {
                    checkStockAvailability().then(stockData => {
                        updateStockDisplay(stockData);
                    });
                }, 30000);

                // Form validation before submit
                if (form) {
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();

                        let totalQuantity = 0;
                        let exceedsStock = false;
                        let errorMessage = '';
                        let hasSelection = false;

                        document.querySelectorAll('input[id^="quantity-"]').forEach(input => {
                            const quantity = parseInt(input.value);
                            if (quantity > 0) {
                                hasSelection = true;
                                totalQuantity += quantity;
                                const id = input.id.replace('quantity-', '');
                                const itemRow = document.querySelector(`[data-id="${id}"]`);
                                const maxStock = parseInt(itemRow.getAttribute('data-max'));
                                const itemName = itemRow.querySelector('.font-medium').textContent;

                                if (quantity > maxStock) {
                                    exceedsStock = true;
                                    errorMessage +=
                                        `${itemName}: diminta ${quantity}, tersedia ${maxStock}\n`;
                                }
                            }
                        });

                        if (!hasSelection) {
                            showNotification('Pilih minimal satu barang dengan jumlah lebih dari 0', 'error');
                            return;
                        }

                        if (exceedsStock) {
                            showNotification('Jumlah yang diminta melebihi stok tersedia:\n' + errorMessage,
                                'error');
                            return;
                        }

                        // Show loading and perform final validation
                        loadingIndicator.classList.remove('hidden');
                        submitButton.disabled = true;

                        checkStockAvailability().then(stockData => {
                            // Update display with latest data
                            updateStockDisplay(stockData);

                            // Re-validate after update
                            let finalError = '';
                            let finalValid = true;

                            document.querySelectorAll('input[id^="quantity-"]').forEach(input => {
                                const quantity = parseInt(input.value);
                                if (quantity > 0) {
                                    const id = input.id.replace('quantity-', '');
                                    const currentStock = stockData.find(item => item.id
                                        .toString() === id);

                                    if (!currentStock || !currentStock.is_available) {
                                        finalValid = false;
                                        finalError +=
                                            `${currentStock ? currentStock.nama : 'Item'} sudah tidak tersedia\n`;
                                    } else if (quantity > currentStock.tersedia) {
                                        finalValid = false;
                                        finalError +=
                                            `${currentStock.nama}: diminta ${quantity}, tersedia ${currentStock.tersedia}\n`;
                                    }
                                }
                            });

                            if (!finalValid) {
                                showNotification('Validasi stok gagal:\n' + finalError +
                                    '\nSilakan sesuaikan jumlah peminjaman.', 'error');
                                return;
                            }

                            // All validations passed, submit form
                            if (confirm(`Konfirmasi peminjaman ${totalQuantity} unit barang?`)) {
                                this.submit();
                            }
                        }).catch(error => {
                            console.error('Error during final validation:', error);
                            // Submit anyway if error checking
                            if (confirm(
                                    `Konfirmasi peminjaman ${totalQuantity} unit barang? (Validasi stok tidak dapat dilakukan)`
                                )) {
                                this.submit();
                            }
                        }).finally(() => {
                            loadingIndicator.classList.add('hidden');
                            submitButton.disabled = totalQuantity === 0;
                        });
                    });
                }
            });
        </script>
    @endif
</x-app-layout>
