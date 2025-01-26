<x-app-layout>
    <section class="lg:max-h-screen bg-gradient-to-t text-dark-green from-light-green to-white">
        <div class="flex justify-between items-center container mx-auto h-screen px-24">
            @foreach ($praktikums as $praktikum)    
                <a href="{{ route('moduls.praktikum', $praktikum->slug) }}" class="h-72 w-64 border border-dark shadow-xl bg-white rounded-2xl flex justify-center items-center flex-col gap-2">
                    <img src="{{ asset('storage/' . $praktikum->image) }}" alt="">
                    <p class="text-center text-pretty text-sm">{{ $praktikum->name }}</p>
                </a>
            @endforeach
        </div>
    </section>
</x-app-layout>