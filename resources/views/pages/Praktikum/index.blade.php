<x-app-layout>
    <section class="pt-24 pb-4 md:pt-0 md:pb-0 md:h-screen relative flex justify-center items-center overflow-hidden text-dark-green bg-gradient-to-t from-light-green to-white">
        <img class="absolute top-0 right-0 z-0" src="{{ asset('images/Ellipse3.png') }}" alt="Ellipse 3">
        <img class="absolute -bottom-20 w-64 left-0 z-0" src="{{ asset('images/Ellipse4.png') }}" alt="Ellipse 4">

        <div class="flex gap-6 md:gap-10 lg:gap-16 justify-center items-center h-full w-full md:h-fit lg:h-full flex-wrap">
            @foreach ($praktikums as $praktikum)    
                <a href="{{ route('moduls.praktikum', $praktikum->slug) }}" class="lg:h-72 h-80 w-64 border border-dark shadow-xl hover:bg-gradient-to-t from-primary to-gray-950 hover:text-white transition-all duration-500 bg-white rounded-2xl flex justify-center items-center flex-col gap-2 z-10">
                    <img src="{{ asset('storage/' . $praktikum->image) }}" alt="">
                    <p class="text-center text-pretty text-sm">{{ $praktikum->name }}</p>
                </a>
            @endforeach
        </div>
    </section>
</x-app-layout>