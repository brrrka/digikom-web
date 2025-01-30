<x-app-layout>
    <section class="min-h-screen relative overflow-hidden">
        <img class="absolute top-28 left-0 w-56" src="{{ asset('images/Ellipse10.png') }}" alt="Ellipse 10">
        <img class="absolute top-28 right-0 w-36" src="{{ asset('images/Ellipse11.png') }}" alt="Ellipse 11">
        <div class="absolute inset-0 flex justify-center items-center px-12">
            <div class="flex-none flex flex-col gap-4 justify-center items-center">
                <div class="flex justify-center items-center flex-col gap-4">
                    <button class="flex justify-center items-center text-black h-12 w-36 bg-transparent p-0.5 bg-gradient-to-r from-gray-950 to-dark-green-2 text-sm rounded-2xl overflow-hidden">
                        <div class="flex justify-center items-center w-full h-full rounded-xl bg-white font-semibold">
                            Visi
                        </div>
                    </button>
                    <button class="flex justify-center items-center text-black h-12 w-36 bg-transparent p-0.5 bg-gradient-to-r from-gray-950 to-dark-green-2 text-sm rounded-2xl overflow-hidden">
                        <div class="flex justify-center items-center w-full h-full rounded-xl bg-white font-semibold">
                            Misi
                        </div>
                    </button>
                </div>
                <img class="w-60" src="{{ asset('images/Illustration3.png') }}" alt="Illustration 3">
            </div>
            <div class="flex-1"></div>
        </div>
    </section>
</x-app-layout>