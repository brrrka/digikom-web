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
        <form action="" class="mt-12 flex flex-col items-center gap-8">
            @csrf
            <div class="flex flex-col w-96 relative">
                <label for="" class="absolute bottom-9 left-8 bg-white text-sm font-regular">Tanggal
                    pinjam</label>
                <input type="date" class="rounded-3xl px-4 py-3 text-sm">
            </div>
            <div class="flex flex-col w-96 relative">
                <label for="" class="absolute bottom-9 left-8 bg-white text-sm font-regular">Tanggal
                    selesai</label>
                <input type="date" class="rounded-3xl px-4 py-3 text-sm">
            </div>
            <div class="flex flex-col w-96 relative">
                <label for="" class="absolute bottom-9 left-8 bg-white text-sm font-regular">Alasan
                    Peminjaman</label>
                <input type="text" class="rounded-3xl px-4 py-3 text-sm">
            </div>
            <div class="flex flex-col w-96 relative">
                <label for="" class="absolute bottom-9 left-8 bg-white text-sm font-regular">Barang yang
                    dipinjam</label>
                <input type="date" class="rounded-3xl px-4 py-3 text-sm">
            </div>
            <button type="submit" class="bg-dark-digikom text-white w-96 rounded-3xl py-3">
                Selanjutnya
            </button>
        </form>
    </div>
</x-app-layout>
