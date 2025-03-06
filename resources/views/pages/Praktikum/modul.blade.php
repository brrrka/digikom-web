<x-app-layout>
    <section class="min-h-screen relative pt-24 bg-gradient-to-t from-light-blue to-white overflow-hidden">
        <img src="{{ asset('images/Ellipse9.png') }}" class="absolute left-0 bottom-0 md:bottom-28 w-36" alt="">
        <img src="{{ asset('images/Ellipse7.png') }}" class="absolute top-32 right-12 md:right-44 w-28 md:w-40" alt="">
        <img src="{{ asset('images/Ellipse8.png') }}" class="absolute top-32 right-16 md:right-48 w-28 md:w-40" alt="">
        
        <div class="container absolute px-4 md:px-12 mx-auto flex items-center mb-4">
            <a class="flex-none text-dark-green" href="{{ route('praktikum.index') }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
            </a>
            <h1 class="text-lg md:text-2xl flex-1 font-bold text-center text-dark-green tracking-wide">
                {{ $praktikum->name }}
            </h1>
        </div>
        
        <div class="container mx-auto px-4 lg:px-20 gap-8 md:gap-10 lg:gap-x-6 pt-12 flex-wrap flex justify-center items-start">
            @forelse ($moduls as $modul)
                <div class="w-40 md:w-72 lg:w-52 relative group">
                    <div class="h-14 w-full text-black shadow-md bg-light-green hover:bg-gradient-to-r from-gray-950 to-dark-green hover:text-white transition-all duration-300 cursor-pointer flex justify-center items-center rounded-2xl mb-2 modul">
                        <p>Modul {{ $modul->modul_ke }}</p>
                    </div>
                    
                    <div class="w-full bg-white p-4 rounded-2xl shadow-lg hidden group-hover:block transition-all duration-300 ease-in-out z-10">
                        <a href="{{ route('moduls.download', $modul->id) }}" class="flex justify-center items-center text-white h-10 w-full bg-gradient-to-r from-gray-950 to-dark-green text-sm rounded-lg mb-4">
                            Download Modul
                        </a>
                        <a href="#"
                            class="flex justify-center items-center text-dark-green-3 h-10 w-full bg-transparent p-0.5 bg-gradient-to-r from-gray-950 to-dark-green-2 text-sm rounded-lg">
                            <div class="flex justify-center items-center w-full h-full rounded-md bg-white gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M13.19 8.688a4.5 4.5 0 0 1 1.242 7.244l-4.5 4.5a4.5 4.5 0 0 1-6.364-6.364l1.757-1.757m13.35-.622 1.757-1.757a4.5 4.5 0 0 0-6.364-6.364l-4.5 4.5a4.5 4.5 0 0 0 1.242 7.244" />
                                </svg>
                                <p>Video Modul</p>
                            </div>
                        </a>
                    </div>
                </div>
            @empty
                <div class="fixed inset-0 w-full bg-black/50 z-50 flex justify-center items-center">
                    <div class="bg-white px-2 w-85 h-52 md:h-64 md:w-96 flex flex-col justify-center items-center rounded-3xl gap-4">
                        <img src="{{ asset('images/waiting-sand.gif') }}" alt="">
                        <p class="text-dark text-sm">Modul belum diupload. Mohon ditunggu ya!</p>
                        <a href="{{ route('praktikum.index') }}"
                            class="rounded-xl bg-dark-green-2 px-8 py-2 text-white">
                            Kembali
                        </a>
                    </div>
                </div>
            @endforelse
        </div>
    </section>
</x-app-layout>
