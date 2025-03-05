<x-app-layout>
    <div class="min-h-screen bg-gradient-to-b from-white via-white to-[#eef8e2] flex flex-col px-48">

        <div class="absolute right-0 top-40">
            <img src="{{ asset('images/productinspection.png') }}" class="w-64" />
        </div>
        <div class="absolute right-64 top-80">
            <img src="{{ asset('images/researchanalysisreport.png') }}" class="w-64" />
        </div>

        <div class="flex justify-center mt-32 relative">
            <a class="absolute left-0 flex-none text-black" href="{{ route('peminjaman') }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
            </a>

            <h1 class="text-2xl font-bold self-center">Pinjam Barang</h1>
        </div>

        <div class="mt-16 flex flex-col ">
            <h2 class="font-bold">Ketentuan durasi peminjaman alat</h2>
            <p>Jangka pendek: 1 - 14 hari sejak tanggal peminjaman</p>
            <p>Jangka panjang: 3 minggu - 6 bulan sejak tanggal peminjaman</p>
            <p class="mt-12 break-words max-w-1/2"><span class="font-bold">catatan:</span> untuk peminjaman jangka
                panjang
                mesti
                dilakukan <span class="font-bold">pengecekan</span>
            </p>
            <p>kondisi barang setiap 2 minggu sekali ke LAB DIGIKOM</p>
        </div>
        <div class="mt-12">
            <a class="py-3 bg-dark-green-2 text-white px-16 rounded-3xl" href="{{ Route('peminjaman.form') }}">
                Selanjutnya
            </a>
        </div>
    </div>
</x-app-layout>
