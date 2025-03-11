<x-app-layout>
    <section class="flex h-dvh justify-center items-center overflow-hidden relative" id="home">
        <img class="block absolute top-28 w-16 left-0 md:top-44 md:w-32 lg:top-28 lg:w-20"
            src="{{ asset('images/Item1.png') }}" alt="Item 1" loading="lazy">
        <img class="block absolute bottom-64 -right-20 w-36 md:w-32 md:right-0 md:bottom-48 lg:bottom-0"
            src="{{ asset('images/Item3.png') }}" alt="Item 3">
        <img class="block md:hidden lg:block absolute w-48 -left-28 -bottom-6 right-0 md:top-44 lg:top-24 lg:left-125 lg:w-36"
            src="{{ asset('images/Item2.png') }}" alt="Item 2" loading="lazy">
        <img class="hidden md:block absolute bottom-96 lg:bottom-28 lg:left-125 right-0 w-40"
            src="{{ asset('images/Ellipse2.png') }}" alt="Ellipse 2" loading="lazy">
        <div class="h-full w-full flex justify-center items-center flex-wrap flex-col md:flex-row">
            <div class="flex-1 text-center md:text-start w-full md:order-1 order-2 text-wrap relative h-full">
                <div
                    class="carousel-text absolute inset-0 top-0 md:-top-10 lg:top-4 h-full md:pl-12 px-6 md:pr-5 flex flex-col justify-center transition-all duration-300 ease-in-out opacity-0">
                    <h1 class="text-xl md:text-3xl lg:text-5xl text-dark-digikom font-bold mb-4 text-pretty">
                        Building the <span class="text-primary">Future of Computing,</span> One Byte at a Time
                    </h1>
                    <p class="text-dark-digikom lg:text-lg text-sm">
                        With a focus on <span class="text-primary">digital systems and computer architecture,</span> we
                        are crafting solutions that pave the way for smarter, faster, and more efficient computing.
                        Together, let's build the future of technology, one byte at a time.
                    </p>
                </div>

                <div
                    class="carousel-text absolute inset-0 top-0 md:-top-10 lg:top-4 h-full md:pl-12 px-6 md:pr-5 flex flex-col justify-center transition-all duration-300 ease-in-out opacity-0">
                    <h1 class="text-xl md:text-3xl lg:text-5xl text-dark-digikom font-bold mb-4 text-pretty">
                        <span class="text-primary">Empowering</span> Ideas Through Digital and Computer Architecture
                    </h1>
                    <p class="text-dark-digikom lg:text-lg text-sm">
                        Unlock the potential of innovation with cutting-edge research in digital systems and computer
                        architecture. We transform ideas into impactful solutions, driving the <span
                            class="text-primary">evolution of modern technology.</span>
                    </p>
                </div>

                <div
                    class="carousel-text absolute inset-0 top-0 md:-top-10 lg:top-4 h-full md:pl-12 px-6 md:pr-5 flex flex-col justify-center transition-all duration-300 ease-in-out opacity-0">
                    <h1 class="text-xl md:text-3xl lg:text-5xl text-dark-digikom font-bold mb-4 text-pretty">
                        Discover, Design, Deliver: <span class="text-primary">The Art of Computer Architecture</span>
                    </h1>
                    <p class="text-dark-digikom lg:text-lg text-sm">
                        Explore the limitless possibilities of technology with groundbreaking research in computer
                        architecture. From concept to creation, we <span class="text-primary">design and deliver
                            solutions</span> that shape the future of computing.
                    </p>
                </div>

                <a href=""
                    class="hidden absolute bottom-4 left-1/2 -translate-x-1/2 md:translate-x-0 md:left-12 md:bottom-96 lg:bottom-16 rounded-full bg-primary px-8 py-2 text-black font-semibold">
                    Learn More
                </a>
            </div>
            <div class="flex-1 justify-center w-full md:order-2 order-1 flex items-center h-full relative">
                <div
                    class="absolute inset-0 md:right-14 top-16 right-2 md:-top-6 lg:top-2 h-full flex items-center md:justify-end justify-center z-10">
                    <img src="{{ asset('images/Ellipse1.png') }}" class="lg:w-96 md:w-80 w-60" alt="Ellips"
                        loading="lazy">
                </div>
                <div
                    class="carousel-image absolute inset-0 top-15 md:-top-8 lg:-top-0 md:right-12 h-full flex items-center md:justify-end justify-center transition-all duration-300 ease-in-out opacity-0">
                    <img class="lg:w-96 md:w-80 w-60 bg-light rounded-full" src="{{ asset('images/Inti.png') }}"
                        alt="Inti" loading="lazy">
                </div>
                <div
                    class="carousel-image absolute inset-0 top-15 md:-top-8 lg:-top-0 md:right-12 h-full flex items-center md:justify-end justify-center transition-all duration-300 ease-in-out opacity-0">
                    <img class="lg:w-96 md:w-80 w-60 bg-light rounded-full" src="{{ asset('images/IPI.png') }}"
                        alt="IPI" loading="lazy">
                </div>
                <div
                    class="carousel-image absolute inset-0 top-15 md:-top-8 lg:-top-0 md:right-12 h-full flex items-center md:justify-end justify-center transition-all duration-300 ease-in-out opacity-0">
                    <img class="lg:w-96 md:w-80 w-60 bg-light rounded-full" src="{{ asset('images/Multimedia.png') }}"
                        alt="Multimedia" loading="lazy">
                </div>
                <div
                    class="carousel-image absolute inset-0 top-15 md:-top-8 lg:-top-0 md:right-12 h-full flex items-center md:justify-end justify-center transition-all duration-300 ease-in-out opacity-0">
                    <img class="lg:w-96 md:w-80 w-60 bg-light rounded-full" src="{{ asset('images/RnD.png') }}"
                        alt="RnD" loading="lazy">
                </div>
            </div>
        </div>
    </section>

    <section
        class="pt-16 lg:pt-28 text-dark-digikom overflow-x-hidden overflow-y-visible lg:overflow-visible relative min-h-125 md:min-h-150 lg:h-screen w-full"
        id="about">
        <img class="block absolute w-8 bottom-0 z-50 md:w-16 left-0 md:-top-60 lg:-top-18"
            src="{{ asset('images/Item6.png') }}" alt="Item 6">
        <div class="relative w-full lg:overflow-hidden lg:h-full">
            <div class="absolute inset-0 max-w-lg lg:max-w-2xl z-30 px-5 md:pl-12 md:pr-12">
                <h5 class="font-bold text-primary">ABOUT</h5>
                <h1 class="text-xl md:text-4xl leading-tight font-bold my-4 text-pretty">Your <span
                        class="text-primary">Gateway</span> to <span class="text-primary">Cutting-Edge</span> Digital
                    and Architecture Computer</h1>
                <p class="text-sm md:text-base">We're a vibrant hub where <span class="text-dark-green">researchers,
                        students, and innovators collaborate to push the boundaries of digital and computer
                        architecture.</span> Through hands-on projects, interdisciplinary teamwork, and a commitment to
                    impactful solutions, we empower every member to turn ideas into technological breakthroughs.</p>
            </div>

            <!-- Carousel images -->
            <img id="carousel-img-0"
                class="carousel-images absolute min-w-115 top-18 -right-56 md:-right-115 md:-top-16 lg:-top-4 lg:-right-10 md:min-w-md lg:max-w-2xl z-20 opacity-100 transition-opacity duration-500"
                src="{{ asset('images/Item4.png') }}" alt="Item 4">
            <img id="carousel-img-1"
                class="carousel-images absolute min-w-115 top-18 -right-56 md:-right-115 md:-top-16 lg:-top-4 lg:-right-10 md:min-w-md lg:max-w-2xl z-20 opacity-0 transition-opacity duration-500"
                src="{{ asset('images/Item4-b.png') }}" alt="Item 4-b">
            <img id="carousel-img-2"
                class="carousel-images absolute min-w-115 top-18 -right-56 md:-right-115 md:-top-16 lg:-top-4 lg:-right-10 md:min-w-md lg:max-w-2xl z-20 opacity-0 transition-opacity duration-500"
                src="{{ asset('images/Item4-c.png') }}" alt="Item 4-c">
            <img id="carousel-img-3"
                class="carousel-images absolute min-w-115 top-18 -right-56 md:-right-115 md:-top-16 lg:-top-4 lg:-right-10 md:min-w-md lg:max-w-2xl z-20 opacity-0 transition-opacity duration-500"
                src="{{ asset('images/Item4-d.png') }}" alt="Item 4-d">

            <!-- Background image (static) -->
            <img class="absolute lg:min-w-full md:min-w-xl min-w-md top-4 md:-top-40 lg:-top-16 z-0"
                src="{{ asset('images/Item5.png') }}" alt="Item 5">
        </div>
    </section>

    <section class="py-8 md:py-20 lg:py-12">
        <h1 class="text-primary text-2xl md:text-5xl font-bold tracking-wide text-center mb-4 md:mb-8">How We're Here
            for You</h1>
        <div class="hidden md:grid grid-cols-10 gap-8 lg:gap-4 text-white">
            <div class="col-span-10 lg:col-span-4 grid grid-rows-4 gap-4">
                <div
                    class="row-span-1 bg-primary/15 h-14 rounded-r-2xl items-transparent-primary transition-all duration-200">
                </div>
                <div class="row-span-1 grid grid-cols-6 gap-3">
                    <div
                        class="col-span-2 lg:col-span-2 bg-primary/15 rounded-r-2xl items-transparent-primary transition-all duration-200">
                    </div>
                    <div
                        class="col-span-2 lg:col-span-3 bg-primary rounded-2xl grid place-items-center px-2 item-1 transition-all duration-200">
                        <p class="text-center text-sm font-bold tracking-wide transition-all duration-200">Competition
                            &
                            Hackathons</p>
                    </div>
                    <div
                        class="col-span-2 lg:col-span-1 bg-primary/15 rounded-2xl items-transparent-primary transition-all duration-200">
                    </div>
                </div>
                <div class="row-span-2 grid grid-cols-6 gap-3">
                    <div
                        class="col-span-2 lg:col-span-2 bg-primary/15 rounded-r-2xl items-transparent-primary transition-all duration-200">
                    </div>
                    <div
                        class="col-span-2 lg:col-span-3 bg-primary rounded-2xl flex justify-center items-center px-4 py-2 item-1 transition-all duration-200">

                        <p class="text-center text-sm">
                            Help you develop creativity, resilience, and teamwork while gaining invaluable experience in
                            a competitive environment.
                        </p>
                    </div>
                    <div
                        class="col-span-2 lg:col-span-1 bg-primary/15 rounded-2xl items-transparent-primary transition-all duration-200">
                    </div>
                </div>
            </div>
            <div class="col-span-10 lg:col-span-2 grid grid-rows-4 gap-4">
                <div class="row-span-1 grid grid-cols-6 gap-3">
                    <div
                        class="col-span-2 lg:hidden h-14 bg-dark-digikom/15 rounded-2xl items-transparent-dark transition-all duration-200">
                    </div>
                    <div
                        class="col-span-2 lg:col-span-6 grid place-items-center rounded-2xl px-2 bg-dark-digikom item-2 transition-all duration-200">
                        <p class="text-center text-sm font-bold tracking-wide">Practice</p>
                    </div>
                    <div
                        class="col-span-2 lg:hidden bg-dark-digikom/15 rounded-2xl items-transparent-dark transition-all duration-200">
                    </div>
                </div>
                <div class="row-span-2 grid grid-cols-6 gap-3">
                    <div
                        class="col-span-2 lg:hidden bg-dark-digikom/15 rounded-2xl items-transparent-dark transition-all duration-200">
                    </div>
                    <div
                        class="col-span-2 lg:col-span-6 grid place-items-center rounded-2xl px-2 bg-dark-digikom item-2 transition-all duration-200">
                        <p class="text-center text-sm">Learn and practice about digital logic and computer
                            architecture.</p>
                    </div>
                    <div
                        class="col-span-2 lg:hidden bg-dark-digikom/15 rounded-2xl items-transparent-dark transition-all duration-200">
                    </div>
                </div>
                <div
                    class="row-span-1 bg-dark-digikom/15 rounded-2xl items-transparent-dark transition-all duration-200">
                </div>
            </div>
            <div class="col-span-10 lg:col-span-4 grid grid-rows-4 gap-4">
                <div
                    class="row-span-1 bg-red-digikom/15 h-14 rounded-l-2xl items-transparent-red transition-all duration-200">
                </div>
                <div class="row-span-1 grid grid-cols-6 gap-3">
                    <div
                        class="col-span-2 lg:col-span-1 bg-red-digikom/15 rounded-2xl items-transparent-red transition-all duration-200">
                    </div>
                    <div
                        class="col-span-2 lg:col-span-3 bg-red-digikom rounded-2xl px-4 py-2 flex justify-center items-center item-3 transition-all duration-200">
                        <p class="text-center text-sm font-bold tracking-wide">Project-Based Learning</p>
                    </div>
                    <div
                        class="col-span-2 lg:col-span-2 bg-red-digikom/15 rounded-l-xl items-transparent-red transition-all duration-200">
                    </div>
                </div>
                <div class="row-span-2 grid grid-cols-6 gap-3">
                    <div
                        class="col-span-2 lg:col-span-1 bg-red-digikom/15 rounded-2xl items-transparent-red transition-all duration-200">
                    </div>
                    <div
                        class="col-span-2 lg:col-span-3 bg-red-digikom rounded-2xl px-4 py-2 flex justify-center items-center item-3 transition-all duration-200">
                        <p class="text-center text-sm">Builds critical thinking and problem-solving through hands-on
                            tasks and collaboration</p>
                    </div>
                    <div
                        class="col-span-2 lg:col-span-2 bg-red-digikom/15 rounded-l-xl items-transparent-red transition-all duration-200">
                    </div>

                </div>
            </div>
        </div>

        <div
            class="flex justify-start items-center md:hidden p-8 space-x-6 text-white snap-x snap-mandatory overflow-x-scroll hide-scrollbar">
            <div
                class="flex-shrink-0 snap-center w-72 h-44 bg-primary rounded-2xl flex flex-col gap-2 justify-center items-center text-center px-4">
                <h5 class="font-bold">Practice</h5>
                <p class="text-sm">
                    Learn and practice about digital logic and computer architecture.
                </p>
            </div>
            <div
                class="flex-shrink-0 snap-center w-72 h-44 bg-red-digikom rounded-2xl flex flex-col gap-2 justify-center items-center text-center px-4">
                <h5 class="font-bold">Project-Based Learning</h5>
                <p class="text-sm">
                    Builds critical thinking and problem-solving through hands-on tasks and collaboration
                </p>
            </div>
            <div
                class="flex-shrink-0 snap-center w-72 h-44 bg-dark-digikom rounded-2xl flex flex-col gap-2 justify-center items-center text-center px-4">
                <h5 class="font-bold">Competition & Hackathons</h5>
                <p class="text-sm">
                    Help you develop creativity, resilience, and teamwork while gaining invaluable experience in a
                    competitive environment.
                </p>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const images = document.querySelectorAll('.carousel-images');
            let currentImageIndex = 0;

            function showNextImage() {
                const nextImageIndex = (currentImageIndex + 1) % images.length;

                images[nextImageIndex].classList.replace('opacity-0', 'opacity-100');

                setTimeout(() => {
                    images[currentImageIndex].classList.replace('opacity-100', 'opacity-0');
                    currentImageIndex = nextImageIndex;
                }, 50);
            }

            setInterval(showNextImage, 2000);
        });
    </script>

</x-app-layout>
