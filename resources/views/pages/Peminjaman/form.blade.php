<x-app-layout>
    <!-- CSRF Token for AJAX requests -->
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <div class="min-h-screen flex flex-col px-4 md:px-48">

        @if($errors->any())
            <div class="mt-20 mb-4 max-w-96 mx-auto">
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex">
                        <svg class="h-5 w-5 text-red-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <h3 class="text-sm font-medium text-red-800 mb-2">Terdapat kesalahan pada form:</h3>
                            <ul class="text-sm text-red-700 list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif
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
                    class="rounded-3xl px-4 py-3 text-sm border focus:border-dark-green focus:ring-dark-green w-full"
                    min="{{ date('Y-m-d') }}" value="{{ old('tanggal_peminjaman') }}" required>
                <div id="error-tanggal_peminjaman" class="hidden text-red-500 text-xs mt-1 ml-4"></div>
            </div>

            <div class="flex flex-col w-full max-w-96 relative mt-8">
                <label for="tanggal_selesai" class="absolute bottom-9 left-8 bg-white text-sm font-regular px-1">Tanggal
                    selesai</label>
                <input type="date" id="tanggal_selesai" name="tanggal_selesai"
                    class="rounded-3xl px-4 py-3 text-sm border focus:border-dark-green focus:ring-dark-green w-full"
                    value="{{ old('tanggal_selesai') }}" required>
                <div id="error-tanggal_selesai" class="hidden text-red-500 text-xs mt-1 ml-4"></div>
            </div>

            <div class="flex flex-col w-full max-w-96 relative mt-8">
                <label for="alasan" class="absolute bottom-9 left-8 bg-white text-sm font-regular px-1">Alasan
                    Peminjaman</label>
                <input type="text" id="alasan" name="alasan"
                    class="rounded-3xl px-4 py-3 text-sm border focus:border-dark-green focus:ring-dark-green w-full"
                    placeholder="Sertakan alasan yang jelas" minlength="10" value="{{ old('alasan') }}" required>
                <div id="error-alasan" class="hidden text-red-500 text-xs mt-1 ml-4"></div>
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
            <div id="error-barang" class="hidden text-red-500 text-xs mt-1 ml-4 w-full max-w-96"></div>

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

            // Form persistence functions
            function saveFormState() {
                const formState = {
                    tanggal_peminjaman: document.getElementById('tanggal_peminjaman').value,
                    tanggal_selesai: document.getElementById('tanggal_selesai').value,
                    alasan: document.getElementById('alasan').value,
                    selectedItems: selectedItems.map(id => ({
                        id: id,
                        nama: document.querySelector(`button[data-id="${id}"]`)?.closest('.flex').querySelector('span').textContent || ''
                    }))
                };
                localStorage.setItem('peminjaman_form_state', JSON.stringify(formState));
            }

            function loadFormState() {
                const savedState = localStorage.getItem('peminjaman_form_state');
                if (savedState) {
                    try {
                        const formState = JSON.parse(savedState);

                        // Restore form fields
                        if (formState.tanggal_peminjaman) {
                            document.getElementById('tanggal_peminjaman').value = formState.tanggal_peminjaman;
                        }
                        if (formState.tanggal_selesai) {
                            document.getElementById('tanggal_selesai').value = formState.tanggal_selesai;
                        }
                        if (formState.alasan) {
                            document.getElementById('alasan').value = formState.alasan;
                        }

                        // Restore selected items
                        if (formState.selectedItems && formState.selectedItems.length > 0) {
                            formState.selectedItems.forEach(item => {
                                // Check if item is still available in the dropdown
                                const option = document.querySelector(`.item-option[data-id="${item.id}"]`);
                                if (option && !selectedItems.includes(item.id)) {
                                    selectedItems.push(item.id);
                                    addSelectedItem(item.id, item.nama);
                                }
                            });
                        }

                        updateSubmitButton();
                    } catch (e) {
                        console.error('Error loading form state:', e);
                        localStorage.removeItem('peminjaman_form_state');
                    }
                }
            }

            function clearFormState() {
                localStorage.removeItem('peminjaman_form_state');
            }

            // Initialize form state
            submitBtn.disabled = true;

            // Handle server validation errors display
            @if($errors->any())
                // Highlight fields with server errors
                @foreach($errors->keys() as $field)
                    @if($field === 'id_inventaris')
                        clearFieldError('barang');
                        const barangSearch = document.getElementById('barang_search');
                        if (barangSearch) {
                            barangSearch.classList.add('border-red-500', 'ring-red-500');
                        }
                        const errorBag = document.getElementById('error-barang');
                        if (errorBag) {
                            errorBag.textContent = '{{ $errors->first('id_inventaris') }}';
                            errorBag.classList.remove('hidden');
                        }
                    @else
                        const input{{ ucfirst(str_replace('_', '', $field)) }} = document.getElementById('{{ $field }}');
                        if (input{{ ucfirst(str_replace('_', '', $field)) }}) {
                            input{{ ucfirst(str_replace('_', '', $field)) }}.classList.add('border-red-500', 'ring-red-500');
                        }
                        const error{{ ucfirst(str_replace('_', '', $field)) }} = document.getElementById('error-{{ $field }}');
                        if (error{{ ucfirst(str_replace('_', '', $field)) }}) {
                            error{{ ucfirst(str_replace('_', '', $field)) }}.textContent = '{{ $errors->first($field) }}';
                            error{{ ucfirst(str_replace('_', '', $field)) }}.classList.remove('hidden');
                        }
                    @endif
                @endforeach
            @else
                // Load saved form state if no server validation errors
                loadFormState();
            @endif

            // Add event listeners to clear errors and save state when user starts typing/changing values
            document.getElementById('tanggal_peminjaman').addEventListener('change', function() {
                clearFieldError('tanggal_peminjaman');
                saveFormState();
            });

            document.getElementById('tanggal_selesai').addEventListener('change', function() {
                clearFieldError('tanggal_selesai');
                saveFormState();
            });

            document.getElementById('alasan').addEventListener('input', function() {
                clearFieldError('alasan');
                // Debounce save to avoid too many saves
                clearTimeout(stockCheckTimeout);
                stockCheckTimeout = setTimeout(saveFormState, 500);
            });

            // Clear barang error when items are selected/deselected
            function clearBarangError() {
                clearFieldError('barang');
            }

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
                        clearBarangError(); // Clear barang error when item is selected
                        saveFormState(); // Save state when item is selected
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

                    // Clear error if there are still selected items, otherwise keep it for validation
                    if (selectedItems.length > 0) {
                        clearBarangError();
                    }
                    saveFormState(); // Save state when item is removed
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

            // Use global notification system (loaded via notifications.js)
            function showNotification(message, type = 'info', duration = 5000) {
                return window.notifications.show(message, type, duration);
            }

            // Form validation functions
            function validateForm() {
                let isValid = true;
                const errors = {};

                // Clear previous errors
                clearFieldErrors();

                // Validate tanggal_peminjaman
                const tanggalPeminjaman = document.getElementById('tanggal_peminjaman').value;
                if (!tanggalPeminjaman) {
                    errors.tanggal_peminjaman = 'Tanggal peminjaman wajib diisi';
                    isValid = false;
                } else {
                    const today = new Date().toISOString().split('T')[0];
                    if (tanggalPeminjaman < today) {
                        errors.tanggal_peminjaman = 'Tanggal peminjaman tidak boleh kurang dari hari ini';
                        isValid = false;
                    }
                }

                // Validate tanggal_selesai
                const tanggalSelesai = document.getElementById('tanggal_selesai').value;
                if (!tanggalSelesai) {
                    errors.tanggal_selesai = 'Tanggal selesai wajib diisi';
                    isValid = false;
                } else if (tanggalPeminjaman && tanggalSelesai <= tanggalPeminjaman) {
                    errors.tanggal_selesai = 'Tanggal selesai harus setelah tanggal peminjaman';
                    isValid = false;
                }

                // Validate alasan
                const alasan = document.getElementById('alasan').value.trim();
                if (!alasan) {
                    errors.alasan = 'Alasan peminjaman wajib diisi';
                    isValid = false;
                } else if (alasan.length < 10) {
                    errors.alasan = 'Alasan peminjaman minimal 10 karakter';
                    isValid = false;
                }

                // Validate selected items
                if (selectedItems.length === 0) {
                    errors.barang = 'Pilih minimal satu barang yang akan dipinjam';
                    isValid = false;
                }

                // Display errors
                if (!isValid) {
                    displayFieldErrors(errors);
                    // Also show SweetAlert for first error
                    const firstError = Object.values(errors)[0];
                    window.swalError(firstError, {
                        title: 'Form Tidak Valid'
                    });
                }

                return isValid;
            }

            function clearFieldErrors() {
                // Remove error classes
                document.querySelectorAll('input').forEach(input => {
                    input.classList.remove('border-red-500', 'ring-red-500');
                    input.classList.add('border-gray-300');
                });

                // Hide error messages
                document.querySelectorAll('[id^="error-"]').forEach(errorDiv => {
                    errorDiv.classList.add('hidden');
                    errorDiv.textContent = '';
                });
            }

            function displayFieldErrors(errors) {
                Object.keys(errors).forEach(field => {
                    // Highlight input field (except for 'barang' which is a special case)
                    if (field !== 'barang') {
                        const input = document.getElementById(field);
                        if (input) {
                            input.classList.remove('border-gray-300');
                            input.classList.add('border-red-500', 'ring-red-500');
                        }
                    } else {
                        // Highlight the search input for barang selection
                        const barangSearch = document.getElementById('barang_search');
                        if (barangSearch) {
                            barangSearch.classList.remove('border-gray-300');
                            barangSearch.classList.add('border-red-500', 'ring-red-500');
                        }
                    }

                    // Show error message
                    const errorDiv = document.getElementById(`error-${field}`);
                    if (errorDiv) {
                        errorDiv.textContent = errors[field];
                        errorDiv.classList.remove('hidden');
                    }
                });

                // Show general notification
                const errorMessages = Object.values(errors);
                showNotification(`Form tidak valid: ${errorMessages[0]}`, 'error', 6000);
            }

            function clearFieldError(fieldId) {
                if (fieldId === 'barang') {
                    // Clear barang search input error
                    const barangSearch = document.getElementById('barang_search');
                    if (barangSearch) {
                        barangSearch.classList.remove('border-red-500', 'ring-red-500');
                        barangSearch.classList.add('border-gray-300');
                    }
                } else {
                    const input = document.getElementById(fieldId);
                    if (input) {
                        input.classList.remove('border-red-500', 'ring-red-500');
                        input.classList.add('border-gray-300');
                    }
                }

                const errorDiv = document.getElementById(`error-${fieldId}`);
                if (errorDiv) {
                    errorDiv.classList.add('hidden');
                    errorDiv.textContent = '';
                }
            }

            // Refresh stock button
            refreshBtn.addEventListener('click', function() {
                const originalContent = this.innerHTML;
                this.disabled = true;
                this.classList.add('opacity-75');
                this.innerHTML = `
                    <div class="flex items-center">
                        <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-blue-600 mr-2"></div>
                        <span>Memperbarui...</span>
                    </div>
                `;

                const allIds = Array.from(document.querySelectorAll('.item-option')).map(option => option.getAttribute('data-id'));

                checkStockAvailability(allIds).then(stockData => {
                    if (stockData && stockData.length > 0) {
                        updateStockDisplay(stockData);
                        showNotification('Stok berhasil diperbarui!', 'success', 3000);
                    } else {
                        showNotification('Tidak ada data stok yang ditemukan', 'warning', 3000);
                    }
                }).catch(error => {
                    console.error('Error refreshing stock:', error);
                    showNotification('Gagal memperbarui stok. Silakan coba lagi.', 'error', 4000);
                }).finally(() => {
                    setTimeout(() => {
                        this.disabled = false;
                        this.classList.remove('opacity-75');
                        this.innerHTML = originalContent;
                    }, 500); // Small delay to prevent rapid clicking
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

                // Client-side validation first
                if (!validateForm()) {
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
                        window.swalError(
                            `Barang berikut sudah tidak tersedia:\n\n${itemNames}\n\nSilakan refresh halaman untuk memperbarui data.`,
                            {
                                title: 'Stok Tidak Tersedia'
                            }
                        );
                        return;
                    }

                    // All available, clear form state and submit form
                    clearFormState();
                    this.submit();
                }).catch(error => {
                    console.error('Error checking stock before submit:', error);
                    // Submit anyway if error, but clear form state first
                    clearFormState();
                    this.submit();
                }).finally(() => {
                    loadingIndicator.classList.add('hidden');
                    submitBtn.disabled = selectedItems.length === 0;
                });
            });
        });
    </script>
</x-app-layout>
