<x-app-layout>
    <section class="min-h-screen relative overflow-hidden flex flex-col justify-center items-center px-4">

        <img class="absolute right-0 top-0" src="{{ asset('images/Ellipse3.png') }}" alt="Ellipse 3">
        <img class="absolute left-0 bottom-0 w-48" src="{{ asset('images/Ellipse4.png') }}" alt="Ellipse 3">
        <img class="absolute z-10 w-96 h-96 right-8 top-24 hidden md:block" src="{{ asset('images/Illustration1.png') }}"
            alt="Illustration 1">
        <img class="absolute z-10 w-96 h-96 left-8 bottom-12 hidden md:block"
            src="{{ asset('images/Illustration2.png') }}" alt="Illustration 2">
        @auth
            <div class="flex justify-center items-center h-screen w-full flex-col gap-4">
                <a class="w-80 py-3 flex justify-center items-center rounded-xl bg-dark-green text-white hover:bg-dark-green-4 transition-all duration-200 z-10"
                    href="{{ route('peminjaman.start') }}">Pinjam barang</a>
                <a class="w-80 py-3 flex justify-center items-center rounded-xl bg-light-green text-black hover:bg-light-green-2 transition-all duration-200 z-10"
                    href="{{ route('peminjaman.status') }}">Status peminjaman</a>
            </div>
        @else
            <div class="flex flex-col gap-4 justify-center items-center h-screen w-full text-center px-4">
                <h1 class="text-3xl md:text-5xl font-semibold text-red-600">Ups! Anda belum login</h1>
                <p class="text-black text-lg md:text-xl">Silahkan login terlebih dahulu sebelum meminjam</p>
            </div>
        @endauth
    </section>
</x-app-layout>
