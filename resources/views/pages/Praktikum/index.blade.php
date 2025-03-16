<x-app-layout>
    <section
        class="pt-24 pb-4 md:pt-0 md:pb-0 md:h-screen relative flex justify-center items-center overflow-hidden text-dark-green bg-gradient-to-t from-light-green to-white">
        <img class="absolute top-0 right-0 z-0" src="{{ asset('images/Ellipse3.png') }}" alt="Ellipse 3">
        <img class="absolute -bottom-20 w-64 left-0 z-0" src="{{ asset('images/Ellipse4.png') }}" alt="Ellipse 4">

        <div
            class="flex gap-24 md:gap-10 lg:gap-16 justify-center items-center h-full w-full md:h-fit lg:h-full flex-wrap py-24">
            @foreach ($praktikums as $praktikum)
                <div class="card-container relative z-10">
                    <a href="{{ route('moduls.praktikum', $praktikum->slug) }}"
                        class="digital-signal-box lg:h-[310px] h-80 w-64 border-2 border-primary shadow-xl hover:bg-gradient-to-t from-primary to-gray-950 hover:text-white transition-all duration-500 bg-white rounded-3xl flex justify-center items-center flex-col gap-2 relative overflow-hidden p-8">
                        <div
                            class="digital-signal-animation absolute w-full h-8 bottom-64 opacity-0 transition-opacity duration-300">
                            <svg width="100%" height="100%" viewBox="0 0 450 80" preserveAspectRatio="none">
                                <path class="digital-wave"
                                    d="M0,80 L0,20 L50,20 L50,80 L100,80 L100,20 L150,20 L150,80 L200,80 L200,20 L250,20 L250,80 L300,80 L300,20 L350,20 L350,80 L400,80 L400,20 L450,20 L450,80 L500,80 L500,20 L550,20"
                                    stroke="#B1D780" stroke-width="4" fill="none" />
                            </svg>
                        </div>

                        <div
                            class="digital-signal-animation absolute w-full h-8 bottom-52 opacity-0 transition-opacity duration-300">
                            <svg width="100%" height="100%" viewBox="0 0 450 80" preserveAspectRatio="none">
                                <path class="digital-wave"
                                    d="M0,80 L0,20 L50,20 L50,80 L100,80 L100,20 L150,20 L150,80 L200,80 L200,20 L250,20 L250,80 L300,80 L300,20 L350,20 L350,80 L400,80 L400,20 L450,20 L450,80 L500,80 L500,20 L550,20"
                                    stroke="#B1D780" stroke-width="4" fill="none" />
                            </svg>
                        </div>

                        <div
                            class="digital-signal-animation absolute w-full h-8 bottom-40 opacity-0 transition-opacity duration-300">
                            <svg width="100%" height="100%" viewBox="0 0 450 80" preserveAspectRatio="none">
                                <path class="digital-wave"
                                    d="M0,80 L0,20 L50,20 L50,80 L100,80 L100,20 L150,20 L150,80 L200,80 L200,20 L250,20 L250,80 L300,80 L300,20 L350,20 L350,80 L400,80 L400,20 L450,20 L450,80 L500,80 L500,20 L550,20"
                                    stroke="#B1D780" stroke-width="4" fill="none" />
                            </svg>
                        </div>

                        <div
                            class="digital-signal-animation absolute w-full h-8 bottom-28 opacity-0 transition-opacity duration-300">
                            <svg width="100%" height="100%" viewBox="0 0 450 80" preserveAspectRatio="none">
                                <path class="digital-wave"
                                    d="M0,80 L0,20 L50,20 L50,80 L100,80 L100,20 L150,20 L150,80 L200,80 L200,20 L250,20 L250,80 L300,80 L300,20 L350,20 L350,80 L400,80 L400,20 L450,20 L450,80 L500,80 L500,20 L550,20"
                                    stroke="#B1D780" stroke-width="4" fill="none" />
                            </svg>
                        </div>

                        <div
                            class="digital-signal-animation absolute w-full h-8 bottom-16 opacity-0 transition-opacity duration-300">
                            <svg width="100%" height="100%" viewBox="0 0 450 80" preserveAspectRatio="none">
                                <path class="digital-wave"
                                    d="M0,80 L0,20 L50,20 L50,80 L100,80 L100,20 L150,20 L150,80 L200,80 L200,20 L250,20 L250,80 L300,80 L300,20 L350,20 L350,80 L400,80 L400,20 L450,20 L450,80 L500,80 L500,20 L550,20"
                                    stroke="#B1D780" stroke-width="4" fill="none" />
                            </svg>
                        </div>

                        <div
                            class="digital-signal-animation absolute w-full h-10 bottom-4 opacity-0 transition-opacity duration-300">
                            <svg width="100%" height="100%" viewBox="0 0 450 80" preserveAspectRatio="none">
                                <path class="digital-wave"
                                    d="M0,80 L0,20 L50,20 L50,80 L100,80 L100,20 L150,20 L150,80 L200,80 L200,20 L250,20 L250,80 L300,80 L300,20 L350,20 L350,80 L400,80 L400,20 L450,20 L450,80 L500,80 L500,20 L550,20"
                                    stroke="#B1D780" stroke-width="4" fill="none" />
                            </svg>
                        </div>

                        <div class="z-10 flex flex-col items-center">
                            <img src="{{ asset('storage/' . $praktikum->image) }}" alt="" class="mb-4">
                            <p class="text-center text-pretty font-bold text-base text-sm">{{ $praktikum->name }}</p>
                        </div>
                    </a>

                    <!-- Separate "Selengkapnya" button container that appears on hover -->
                    <a href="{{ route('moduls.praktikum', $praktikum->slug) }}"
                        class="selengkapnya-button w-64 text-center py-4 px-12 bg-white border-2 border-primary border-t-0 rounded-b-3xl opacity-0 -translate-y-2 absolute transition-all duration-300">
                        <div class="bg-[#D3ECB0] py-2 px-4 rounded-2xl text-sm text-black">
                            Selengkapnya
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </section>

    <style>
        @keyframes digitalSignal {
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(-100px);
            }
        }

        /* Fix 1: Pastikan .digital-signal-animation memiliki posisi z-index yang tepat */
        .digital-signal-animation {
            position: absolute;
            z-index: 1;
        }

        /* Fix 2: Memastikan animasi muncul saat hover */
        .digital-signal-box:hover .digital-signal-animation {
            opacity: 0.7 !important;
        }

        /* Fix 3: Memastikan animasi wave berjalan */
        .digital-signal-box:hover .digital-wave {
            animation: digitalSignal 1.5s linear infinite;
        }

        /* Fix 4: Membuat konten gambar dan teks tetap terlihat di atas animasi */
        .digital-signal-box img,
        .digital-signal-box p {
            position: relative;
            z-index: 5;
        }

        /* Hover effect for selengkapnya button */
        .card-container:hover .selengkapnya-button {
            transform: translateY(0);
            opacity: 1;
            z-index: 20;
        }

        /* Adjust the main box's border-radius when hovering */
        .card-container:hover .digital-signal-box {
            border-bottom-left-radius: 0;
            border-bottom-right-radius: 0;
        }
    </style>
</x-app-layout>
