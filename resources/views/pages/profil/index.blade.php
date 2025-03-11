<x-app-layout>
    <section class="min-h-screen h-full lg:mt-0 mt-16 relative overflow-hidden" x-data="{ activeTab: 'visi' }">
        <!-- Background elements with responsive sizing -->
        <img class="absolute top-28 left-0 w-56 md:w-56 sm:w-32 max-sm:w-24 max-sm:opacity-40"
            src="{{ asset('images/Ellipse10.png') }}" alt="Ellipse 10">
        <img class="absolute top-28 right-0 w-36 md:w-36 sm:w-24 max-sm:w-16 max-sm:opacity-40"
            src="{{ asset('images/Ellipse11.png') }}" alt="Ellipse 11">

        <div
            class="absolute inset-0 flex flex-col md:flex-row justify-center items-center px-4 sm:px-8 md:px-12 lg:px-24 py-16">

            <div class="flex-none flex flex-col gap-4 justify-center items-center mb-8 md:mb-0 mt-24">
                <div class="flex justify-center items-center flex-col gap-4">
                    <button @click="activeTab = 'visi'"
                        :class="{ 'text-white': activeTab === 'visi', 'text-black': activeTab === 'misi' }"
                        class="flex justify-center items-center h-12 w-40 bg-transparent p-0.5 bg-gradient-to-r from-gray-950 to-dark-green-2 text-sm rounded-2xl overflow-hidden">
                        <div :class="{ 'bg-transparent': activeTab === 'visi', 'bg-white': activeTab === 'misi' }"
                            class="flex justify-center items-center w-full h-full rounded-xl">
                            Visi
                        </div>
                    </button>
                    <button @click="activeTab = 'misi'"
                        :class="{ 'text-white': activeTab === 'misi', 'text-black': activeTab === 'visi' }"
                        class="flex justify-center items-center h-12 w-40 bg-transparent p-0.5 bg-gradient-to-r from-gray-950 to-dark-green-2 text-sm rounded-2xl overflow-hidden">
                        <div :class="{ 'bg-transparent': activeTab === 'misi', 'bg-white': activeTab === 'visi' }"
                            class="flex justify-center items-center w-full h-full rounded-xl">
                            Misi
                        </div>
                    </button>
                </div>
                <dotlottie-player src="https://lottie.host/8010dfa6-e30d-4dc7-aa2e-df4750adc72e/kUcd6iKLgo.lottie"
                    class="hidden md:block" background="transparent" speed="1" style="width: 350px; height: 350px"
                    loop autoplay></dotlottie-player>
            </div>

            <div class="flex-1 w-full md:w-auto">
                <div
                    class="h-auto md:h-96 min-h-64 max-w-4xl bg-dark-green-4 text-white rounded-2xl px-4 sm:px-6 md:px-8 py-8">
                    <div x-show="activeTab === 'visi'"
                        class="w-full h-full flex gap-4 flex-col justify-center items-start">
                        <h1 class="text-2xl sm:text-3xl font-bold">VISI DIGIKOM</h1>
                        <p class="text-base sm:text-lg text-pretty">Menjadi laboratorium pendidikan yang unggul dan
                            profesional dalam
                            bidang teknologi, khususnya dalam bidang teknik digital.</p>
                    </div>
                    <div x-show="activeTab === 'misi'"
                        class="w-full h-full flex gap-4 flex-col justify-center items-start">
                        <h1 class="text-2xl sm:text-3xl font-bold">MISI DIGIKOM</h1>
                        <ul class="text-base sm:text-lg text-pretty list-disc px-4 sm:px-6">
                            <li>
                                Membangun sumber daya manusia yang unggul dan profesional di
                                bidang teknik digital.
                            </li>
                            <li>
                                Melaksanakan pendidikan dan pelatihan di bidang teknik digital & mikroprosesor.
                            </li>
                            <li>
                                Memberikan pelayanan terbaik.
                            </li>
                            <li>
                                Melakukan perbaikan yang berkesinambungan baik dalam segi pelayanan maupun sarana
                                prasarana
                                penunjang pendidikan.
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs" type="module"></script>
</x-app-layout>
