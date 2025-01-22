<x-app-layout>
    <section class="flex h-dvh justify-center items-center relative" id="home">
        <img class="absolute w-32 right-0 bottom-0" src="{{ asset('images/Item3.png') }}" alt="">
        <div class="h-full w-full flex justify-center items-center">
            <div class="flex-1 text-wrap relative h-full">
                <img class="absolute top-28 w-24" src="{{ asset('images/Item1.png') }}" alt="Item 1" loading="lazy"> 
                <img class="absolute top-24 right-0 w-36" src="{{ asset('images/Item2.png') }}" alt="Item 2" loading="lazy"> 
                <img class="absolute bottom-24 right-0 w-40" src="{{ asset('images/Ellipse2.png') }}" alt="Item 2" loading="lazy"> 
                <div class="carousel-text absolute inset-0 top-4 h-full pl-12 pr-5 flex flex-col justify-center transition-all duration-300 ease-in-out opacity-0">
                    <h1 class="text-5xl text-dark font-bold mb-4 leading-tight">
                        Building the <span class="text-primary">Future of Computing,</span> One Byte at a Time
                    </h1>
                    <p class="text-dark text-lg">
                        With a focus on <span class="text-primary">digital systems and computer architecture,</span> we are crafting solutions that pave the way for smarter, faster, and more efficient computing. Together, let's build the future of technology, one byte at a time.
                    </p>
                </div>
                <div class="carousel-text absolute inset-0 top-4 h-full pl-12 pr-5 flex flex-col justify-center transition-all duration-300 ease-in-out opacity-0">
                    <h1 class="text-5xl text-dark font-bold mb-4 leading-tight">
                        <span class="text-primary">Empowering</span> Ideas Through Digital and Computer Architecture
                    </h1>
                    <p class="text-dark text-lg">
                        Unlock the potential of innovation with cutting-edge research in digital systems and computer architecture. We transform ideas into impactful solutions, driving the <span class="text-primary">evolution of modern technology.</span>
                    </p>
                </div>
                <div class="carousel-text absolute inset-0 top-4 h-full pl-12 pr-5 flex flex-col justify-center transition-all duration-300 ease-in-out opacity-0">
                    <h1 class="text-5xl text-dark font-bold mb-4 leading-tight">
                        Discover, Design, Deliver: <span class="text-primary">The Art of Computer Architecture</span>
                    </h1>
                    <p class="text-dark text-lg">
                        Explore the limitless possibilities of technology with groundbreaking research in computer architecture. From concept to creation, we <span class="text-primary">design and deliver solutions</span> that shape the future of computing.
                    </p>
                </div>

                <a href="" class="absolute bottom-12 left-12 rounded-full bg-primary px-8 py-2 text-black font-semibold">
                    Learn More
                </a>
            </div>
            <div class="flex-1 flex items-center h-full relative">
                <div class="absolute inset-0 right-14 top-2 h-full flex items-center justify-end z-10">
                    <img src="{{ asset('images/Ellipse1.png') }}" class="w-96" alt="Ellips" loading="lazy">
                </div>
                <div class="carousel-image absolute inset-0 right-12 h-full flex items-center justify-end transition-all duration-300 ease-in-out opacity-0">
                    <img class="w-96 bg-light rounded-full" src="{{ asset('images/Inti.png') }}" alt="Inti" loading="lazy">
                </div>
                <div class="carousel-image absolute inset-0 right-12 h-full flex items-center justify-end transition-all duration-300 ease-in-out opacity-0">
                    <img class="w-96 bg-light rounded-full" src="{{ asset('images/IPI.png') }}" alt="IPI" loading="lazy">
                </div>
                <div class="carousel-image absolute inset-0 right-12 h-full flex items-center justify-end transition-all duration-300 ease-in-out opacity-0">
                    <img class="w-96 bg-light rounded-full" src="{{ asset('images/Multimedia.png') }}" alt="Multimedia" loading="lazy">
                </div>
                <div class="carousel-image absolute inset-0 right-12 h-full flex items-center justify-end transition-all duration-300 ease-in-out opacity-0">
                    <img class="w-96 bg-light rounded-full" src="{{ asset('images/RnD.png') }}" alt="RnD" loading="lazy">
                </div>
            </div>
        </div>
    </section>

    <section class="pt-28 text-dark relative h-screen w-full" id="about">
        <div class="relative w-full">
            <div class="absolute inset-0 max-w-2xl z-10 pl-12 pr-12">
                <h5 class="font-bold text-primary">ABOUT</h5>
                <h1 class="text-4xl leading-tight font-bold my-4">Your <span class="text-primary">Gateway</span> to <span class="text-primary">Cutting-Edge</span> Digital and Architecture Computer</h1>
                <p class="">We're a vibrant hub where researchers, students, and innovators collaborate to push the boundaries of digital and computer architecture. Through hands-on projects, interdisciplinary teamwork, and a commitment to impactful solutions, we empower every member to turn ideas into technological breakthroughs.</p>
            </div>
            <img src="" alt="">
            <img class="absolute right-0 -top-4 max-w-2xl z-20" src="{{ asset('images/Item4.png') }}" alt="Item 4">
            <div class="absolute inset-0 -top-16 z-0">
                <img src="{{ asset('images/Item5.png') }}" alt="">
            </div>
        </div>
    </section>

    <section class="py-12">
        <h1 class="text-primary text-4xl font-bold tracking-wide text-center mb-8">How We're Here for You</h1>
        <div class="grid grid-cols-10 gap-3 text-white">
            <div class="col-span-4 grid grid-rows-4 gap-4">
                <div class="row-span-1 bg-primary/5 h-14 rounded-r-2xl"></div>
                <div class="row-span-1 grid grid-cols-6 gap-3">
                    <div class="col-span-2 bg-primary/5 rounded-r-2xl"></div>
                    <div class="col-span-3 bg-primary rounded-2xl grid place-items-center px-2">
                        <p class="text-center text-sm font-bold tracking-wide">Competition & Hackathons</p>
                    </div>
                    <div class="col-span-1 bg-primary/5 rounded-2xl"></div>
                </div>
                <div class="row-span-2 grid grid-cols-6 gap-3">
                    <div class="col-span-2 bg-primary/5 rounded-r-2xl"></div>
                    <div class="col-span-3 bg-primary rounded-2xl flex justify-center items-center px-4 py-2">
                        <p class="text-center text-sm">
                            Help you develop creativity, resilience, and teamwork while gaining invaluable experience in a competitive environment.
                        </p>
                    </div>
                    <div class="col-span-1 bg-primary/5 rounded-2xl"></div>
                </div>
            </div>
            <div class="col-span-2 grid grid-rows-4 gap-3">
                <div class="row-span-1 bg-dark rounded-2xl px-4 py-2 flex justify-center items-center">
                    <p class="text-center text-sm font-bold tracking-wide">Practice</p>
                </div>
                <div class="row-span-2 bg-dark rounded-2xl px-4 py-2 flex justify-center items-center">
                    <p class="text-center text-sm">Learn and practice about digital logic and computer architecture.</p>
                </div>
                <div class="row-span-1 bg-dark/5 rounded-2xl"></div>
            </div>
            <div class="col-span-4 grid grid-rows-4 gap-3">
                <div class="row-span-1 bg-red/5 h-14 rounded-l-2xl"></div>
                <div class="row-span-1 grid grid-cols-6 gap-3">
                    <div class="col-span-1 bg-red/5 rounded-2xl"></div>
                    <div class="col-span-3 bg-red rounded-2xl px-4 py-2 flex justify-center items-center">
                        <p class="text-center text-sm font-bold tracking-wide">Project-Based Learning</p>
                    </div>
                    <div class="col-span-2 bg-red/10 rounded-l-xl"></div>
                </div>
                <div class="row-span-2 grid grid-cols-6 gap-3">
                    <div class="col-span-1 bg-red/5 rounded-2xl"></div>
                    <div class="col-span-3 bg-red rounded-2xl px-4 py-2 flex justify-center items-center">
                        <p class="text-center text-sm">Builds critical thinking and problem-solving through hands-on tasks and collaboration</p>
                    </div>
                    <div class="col-span-2 bg-red/5 rounded-l-xl"></div>
                </div>
            </div>
        </div>
    </section>

    <x-footer></x-footer>
    
</x-app-layout>