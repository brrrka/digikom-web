<x-app-layout>
    <section class="h-screen relative overflow-hidden text-dark-green bg-gradient-to-t from-light-green to-white">
        <img class="absolute top-0 right-0" src="{{ asset('images/Ellipse3.png') }}" alt="Ellipse 3">
        <img class="absolute -bottom-20 w-64 left-0" src="{{ asset('images/Ellipse4.png') }}" alt="Ellipse 4">
        <div class="absolute inset-0 z-10 flex justify-between items-center container mx-auto px-24">
            @foreach ($praktikums as $praktikum)    
                <a href="{{ route('moduls.praktikum', $praktikum->slug) }}" class="h-72 w-64 border border-dark shadow-xl hover:bg-gradient-to-t from-primary to-gray-950 hover:text-white transition-all duration-500 bg-white rounded-2xl flex justify-center items-center flex-col gap-2">
                    <img src="{{ asset('storage/' . $praktikum->image) }}" alt="">
                    <p class="text-center text-pretty text-sm">{{ $praktikum->name }}</p>
                </a>
            @endforeach
        </div>
    </section>
</x-app-layout>