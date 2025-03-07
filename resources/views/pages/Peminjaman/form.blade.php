<x-app-layout>
    <div class="min-h-screen flex flex-col px-48">
        <div class="flex justify-center mt-32 relative">
            <a class="absolute left-0 flex-none text-black" href="{{ route('peminjaman') }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
            </a>
            <h1 class="text-2xl font-bold self-center">Pinjam Barang</h1>
        </div>
        <form action="{{ route('peminjaman.quantity') }}" method="POST" class="mt-12 flex flex-col items-center">
            @csrf
            <div class="flex flex-col w-96 relative">
                <label for="tanggal_peminjaman"
                    class="absolute bottom-9 left-8 bg-white text-sm font-regular px-1">Tanggal
                    pinjam</label>
                <input type="date" id="tanggal_peminjaman" name="tanggal_peminjaman"
                    class="rounded-3xl px-4 py-3 text-sm focus:border-dark-green focus:ring-dark-green" required>
            </div>
            <div class="flex flex-col w-96 relative mt-8">
                <label for="tanggal_selesai" class="absolute bottom-9 left-8 bg-white text-sm font-regular px-1">Tanggal
                    selesai</label>
                <input type="date" id="tanggal_selesai" name="tanggal_selesai"
                    class="rounded-3xl px-4 py-3 text-sm border focus:border-dark-green focus:ring-dark-green" required>
            </div>
            <div class="flex flex-col w-96 relative mt-8">
                <label for="alasan" class="absolute bottom-9 left-8 bg-white text-sm font-regular px-1">Alasan
                    Peminjaman</label>
                <input type="text" id="alasan" name="alasan"
                    class="rounded-3xl px-4 py-3 text-sm border focus:border-dark-green focus:ring-dark-green"
                    placeholder="Sertakan alasan yang jelas" required>
            </div>
            <div class="flex flex-col w-96 relative mt-8">
                <label for="barang_search" class="absolute bottom-9 left-8 bg-white text-sm font-regular px-1 ">Barang
                    yang
                    dipinjam</label>
                <div class="dropdown w-full">
                    <input type="text" id="barang_search"
                        class="rounded-3xl px-4 py-3 text-sm border w-full focus:border-dark-green focus:ring-dark-green"
                        placeholder="Pilih atau cari barang yang dipinjam">
                    <div id="dropdown-content"
                        class="dropdown-content hidden absolute z-10 w-full mt-1 bg-white border rounded-md shadow-lg max-h-60 overflow-y-auto ">
                        @foreach ($inventaris as $item)
                            @if ($item['is_available'])
                                <div class="p-3 hover:bg-gray-100 cursor-pointer text-sm item-option "
                                    data-id="{{ $item['id'] }}" data-nama="{{ $item['nama'] }}">
                                    {{ $item['nama'] }}
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>

            <div id="selected-items" class="flex flex-wrap w-96 gap-2 mt-2">
            </div>

            <button type="submit" class="bg-dark-digikom text-white w-96 rounded-3xl py-3 mt-8">
                Selanjutnya
            </button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dropdownContent = document.getElementById('dropdown-content');
            const barangSearch = document.getElementById('barang_search');
            const selectedItemsContainer = document.getElementById('selected-items');
            const form = document.querySelector('form');

            // Menyimpan ID barang yang sudah dipilih
            let selectedItems = [];

            // Event listener untuk input pencarian
            barangSearch.addEventListener('focus', function() {
                dropdownContent.classList.remove('hidden');
            });

            // Menutup dropdown saat klik di luar
            document.addEventListener('click', function(event) {
                if (!event.target.closest('.dropdown')) {
                    dropdownContent.classList.add('hidden');
                }
            });

            // Filter barang saat mengetik
            barangSearch.addEventListener('input', function() {
                const query = this.value.toLowerCase();
                const options = document.querySelectorAll('.item-option');

                options.forEach(option => {
                    const text = option.textContent.toLowerCase();
                    if (text.includes(query)) {
                        option.style.display = 'block';
                    } else {
                        option.style.display = 'none';
                    }
                });
            });

            document.querySelectorAll('.item-option').forEach(option => {
                option.addEventListener('click', function() {
                    const itemId = this.getAttribute('data-id');
                    const itemNama = this.getAttribute('data-nama');

                    if (!selectedItems.includes(itemId)) {
                        addSelectedItem(itemId, itemNama);
                        selectedItems.push(itemId);
                    }

                    barangSearch.value = '';
                    dropdownContent.classList.add('hidden');
                });
            });

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

                // Tambahkan hidden input untuk form submission
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'id_inventaris[]';
                hiddenInput.value = id;
                tag.appendChild(hiddenInput);

                // Event untuk menghapus tag
                tag.querySelector('button').addEventListener('click', function() {
                    const itemId = this.getAttribute('data-id');
                    selectedItems = selectedItems.filter(id => id !== itemId);
                    tag.remove();
                });

                selectedItemsContainer.appendChild(tag);
            }

            // Validasi form sebelum submit
            form.addEventListener('submit', function(e) {
                if (selectedItems.length === 0) {
                    e.preventDefault();
                    alert('Pilih minimal satu barang yang akan dipinjam');
                }
            });
        });
    </script>
</x-app-layout>
