<x-app-layout>
    <section class="min-h-screen relative overflow-hidden" x-data="{ activeTab: 'visi' }">
        <img class="absolute top-28 left-0 w-56" src="{{ asset('images/Ellipse10.png') }}" alt="Ellipse 10">
        <img class="absolute top-28 right-0 w-36" src="{{ asset('images/Ellipse11.png') }}" alt="Ellipse 11">
        <div class="absolute inset-0 flex justify-center items-center px-24">
            <div class="flex-none flex flex-col gap-4 justify-center items-center pt-8">
                <div class="flex justify-center items-center flex-col gap-4">
                    <button 
                        @click="activeTab = 'visi'" 
                        :class="{ 'text-white': activeTab === 'visi', 'text-black': activeTab === 'misi' }"
                        class="flex justify-center items-center h-12 w-40 bg-transparent p-0.5 bg-gradient-to-r from-gray-950 to-dark-green-2 text-sm rounded-2xl overflow-hidden"
                    >
                        <div 
                            :class="{ 'bg-transparent': activeTab === 'visi', 'bg-white': activeTab === 'misi' }"
                            class="flex justify-center items-center w-full h-full rounded-xl"
                        >
                            Visi
                        </div>
                    </button>
                    <button 
                        @click="activeTab = 'misi'" 
                        :class="{ 'text-white': activeTab === 'misi', 'text-black': activeTab === 'visi' }"
                        class="flex justify-center items-center h-12 w-40 bg-transparent p-0.5 bg-gradient-to-r from-gray-950 to-dark-green-2 text-sm rounded-2xl overflow-hidden"
                    >
                        <div 
                            :class="{ 'bg-transparent': activeTab === 'misi', 'bg-white': activeTab === 'visi' }"
                            class="flex justify-center items-center w-full h-full rounded-xl"
                        >
                            Misi
                        </div>
                    </button>
                </div>
                <img class="w-60" src="{{ asset('images/Illustration3.png') }}" alt="Illustration 3">
            </div>
            <div class="flex-1">
                <div class="h-96 max-w-4xl bg-dark-green-4 text-white rounded-2xl px-8">
                    <div 
                        x-show="activeTab === 'visi'"
                        class="w-full h-full flex gap-4 flex-col justify-center items-start"
                    >
                        <h1 class="text-3xl font-bold">VISI DIGIKOM</h1>
                        <p class="text-lg text-pretty">Lorem ipsum dolor sit amet consectetur adipisicing elit. Illum ipsam repellat dicta labore ex minus, culpa ea qui commodi delectus nulla. Accusamus tenetur quisquam exercitationem libero? Illo aliquid architecto perspiciatis doloribus tempora, eveniet rerum labore quidem debitis minus! Alias nesciunt, cupiditate neque dolor iusto molestiae, ducimus et consectetur doloremque, a dolorem tempore quae? Corporis dolor aliquid, praesentium fuga ratione consequatur.</p>
                    </div>
                    <div 
                        x-show="activeTab === 'misi'"
                        class="w-full h-full flex gap-4 flex-col justify-center items-start"
                    >
                        <h1 class="text-3xl font-bold">MISI DIGIKOM</h1>
                        <p class="text-lg text-pretty">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Ut impedit ab inventore? Laudantium unde quod eveniet dolorum est, reiciendis sit dolor eum ipsum. Eligendi ipsam repellat et excepturi, fugit perferendis nulla quis facilis quod sit tempore accusamus, rerum autem rem pariatur illum facere reprehenderit! Quis cum dolorem praesentium nulla fuga illo explicabo delectus voluptas id doloribus impedit expedita hic enim suscipit, voluptatibus nostrum cumque architecto maiores perspiciatis voluptate ab ut atque minima? Voluptate, nulla architecto.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>