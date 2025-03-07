<!-- quantity-selection.blade.php -->
<x-app-layout>
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

        <form action="{{ route('peminjaman.confirm') }}" method="POST" class="mt-12">
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
                        <div class="w-1/3 text-center">Jumlah</div>
                    </div>

                    @foreach ($selectedItems as $item)
                        <div class="px-8 py-4 flex items-center">
                            <div class="w-2/3 font-medium text-left">{{ $item->nama }}</div>
                            <div class="w-1/3 flex items-center justify-center">
                                <button type="button" class="decrease-btn py-1 text-gray-700"
                                    data-id="{{ $item->id }}">−</button>
                                <div class="w-16 text-center">
                                    <span id="quantity-text-{{ $item->id }}">0</span><span
                                        class="text-gray-500">/{{ $item->kuantitas }}</span>
                                    <input type="hidden" name="kuantitas[{{ $item->id }}]"
                                        id="quantity-{{ $item->id }}" value="0">
                                </div>
                                <button type="button" class="increase-btn py-1 text-gray-700"
                                    data-id="{{ $item->id }}" data-max="{{ $item->kuantitas }}">+</button>
                            </div>
                        </div>
                        <input type="hidden" name="id_inventaris[]" value="{{ $item->id }}">
                    @endforeach
                </div>

                <div class="text-xs text-black mt-2 mb-8 italic">
                    Catatan: Jumlah barang yang dipinjam tidak boleh melebihi jumlah barang tersedia
                </div>

                <div class="flex justify-center">

                    <button type="submit" class="bg-dark-digikom text-white w-2/3 rounded-3xl py-3 ">
                        Selanjutnya
                    </button>
                </div>

            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle quantity increase/decrease
            const decreaseButtons = document.querySelectorAll('.decrease-btn');
            const increaseButtons = document.querySelectorAll('.increase-btn');
            const submitButton = document.querySelector('button[type="submit"]');

            // Check if any items have quantity > 0 to enable submit button
            function checkSubmitButton() {
                let enableSubmit = false;
                document.querySelectorAll('input[id^="quantity-"]').forEach(input => {
                    if (parseInt(input.value) > 0) {
                        enableSubmit = true;
                    }
                });

                submitButton.disabled = !enableSubmit;
                if (enableSubmit) {
                    submitButton.classList.remove('opacity-50', 'cursor-not-allowed');
                } else {
                    submitButton.classList.add('opacity-50', 'cursor-not-allowed');
                }
            }

            // Initially disable submit button
            submitButton.disabled = true;
            submitButton.classList.add('opacity-50', 'cursor-not-allowed');

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
                        checkSubmitButton();
                    }
                });
            });

            // Increase quantity
            increaseButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const maxQuantity = parseInt(this.getAttribute('data-max'));
                    const input = document.getElementById(`quantity-${id}`);
                    const quantityText = document.getElementById(`quantity-text-${id}`);
                    let value = parseInt(input.value);

                    if (value < maxQuantity) {
                        value += 1;
                        input.value = value;
                        quantityText.textContent = value;
                        checkSubmitButton();
                    }
                });
            });
        });
    </script>
</x-app-layout>
