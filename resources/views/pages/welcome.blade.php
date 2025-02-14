<x-app-layout>
    <section class="flex h-dvh justify-center items-center relative" id="home">
        <img class="absolute top-44 w-32 left-0 lg:top-28 lg:w-20" src="{{ asset('images/Item1.png') }}" alt="Item 1" loading="lazy"> 
        <img class="absolute w-32 right-0 bottom-48 lg:bottom-0" src="{{ asset('images/Item3.png') }}" alt="Item 3">
        <img class="absolute w-16 left-0 bottom-24 lg:-bottom-20" src="{{ asset('images/Item6.png') }}" alt="Item 6">
        <div class="h-full w-full flex justify-center items-center">
            <div class="flex-1 text-wrap relative h-full">
                <img class="absolute top-44 w-48 right-0 lg:top-24 lg:w-36" src="{{ asset('images/Item2.png') }}" alt="Item 2" loading="lazy"> 
                <img class="absolute bottom-96 lg:bottom-24 right-0 w-40" src="{{ asset('images/Ellipse2.png') }}" alt="Item 2" loading="lazy"> 
                <div class="carousel-text absolute inset-0 -top-10 lg:top-4 h-full pl-12 pr-5 flex flex-col justify-center transition-all duration-300 ease-in-out opacity-0">
                    <h1 class="text-3xl lg:text-5xl text-dark-digikom font-bold mb-4">
                        Building the <span class="text-primary">Future of Computing,</span> One Byte at a Time
                    </h1>
                    <p class="text-dark-digikom lg:text-lg text-sm">
                        With a focus on <span class="text-primary">digital systems and computer architecture,</span> we are crafting solutions that pave the way for smarter, faster, and more efficient computing. Together, let's build the future of technology, one byte at a time.
                    </p>
                </div>
                <div class="carousel-text absolute inset-0 -top-10 lg:top-4 h-full pl-12 pr-5 flex flex-col justify-center transition-all duration-300 ease-in-out opacity-0">
                    <h1 class="text-3xl lg:text-5xl text-dark-digikom font-bold mb-4">
                        <span class="text-primary">Empowering</span> Ideas Through Digital and Computer Architecture
                    </h1>
                    <p class="text-dark-digikom lg:text-lg text-sm">
                        Unlock the potential of innovation with cutting-edge research in digital systems and computer architecture. We transform ideas into impactful solutions, driving the <span class="text-primary">evolution of modern technology.</span>
                    </p>
                </div>
                <div class="carousel-text absolute inset-0 -top-10 lg:top-4 h-full pl-12 pr-5 flex flex-col justify-center transition-all duration-300 ease-in-out opacity-0">
                    <h1 class="text-3xl lg:text-5xl text-dark-digikom font-bold mb-4">
                        Discover, Design, Deliver: <span class="text-primary">The Art of Computer Architecture</span>
                    </h1>
                    <p class="text-dark-digikom lg:text-lg text-sm">
                        Explore the limitless possibilities of technology with groundbreaking research in computer architecture. From concept to creation, we <span class="text-primary">design and deliver solutions</span> that shape the future of computing.
                    </p>
                </div>

                <a href="" class="absolute bottom-96 lg:bottom-16 left-12 rounded-full bg-primary px-8 py-2 text-black font-semibold">
                    Learn More
                </a>
            </div>
            <div class="lg:flex-1 w-96 flex-none flex items-center h-full relative">
                <div class="absolute inset-0 right-14 -top-6 lg:top-2 h-full flex items-center justify-end z-10">
                    <img src="{{ asset('images/Ellipse1.png') }}" class="lg:w-96 w-80" alt="Ellips" loading="lazy">
                </div>
                <div class="carousel-image absolute inset-0 -top-8 lg:-top-0 right-12 h-full flex items-center justify-end transition-all duration-300 ease-in-out opacity-0">
                    <img class="lg:w-96 w-80 bg-light rounded-full" src="{{ asset('images/Inti.png') }}" alt="Inti" loading="lazy">
                </div>
                <div class="carousel-image absolute inset-0 -top-8 lg:-top-0 right-12 h-full flex items-center justify-end transition-all duration-300 ease-in-out opacity-0">
                    <img class="lg:w-96 w-80 bg-light rounded-full" src="{{ asset('images/IPI.png') }}" alt="IPI" loading="lazy">
                </div>
                <div class="carousel-image absolute inset-0 -top-8 lg:-top-0 right-12 h-full flex items-center justify-end transition-all duration-300 ease-in-out opacity-0">
                    <img class="lg:w-96 w-80 bg-light rounded-full" src="{{ asset('images/Multimedia.png') }}" alt="Multimedia" loading="lazy">
                </div>
                <div class="carousel-image absolute inset-0 -top-8 lg:-top-0 right-12 h-full flex items-center justify-end transition-all duration-300 ease-in-out opacity-0">
                    <img class="lg:w-96 w-80 bg-light rounded-full" src="{{ asset('images/RnD.png') }}" alt="RnD" loading="lazy">
                </div>
            </div>
        </div>
    </section>

    <section class="pt-28 text-dark-digikom relative overflow-hidden min-h-150 lg:h-screen w-full" id="about">
        <div class="relative w-full">
            <div class="absolute inset-0 max-w-xl lg:max-w-2xl z-30 pl-12 pr-12">
                <h5 class="font-bold text-primary">ABOUT</h5>
                <h1 class="text-4xl leading-tight font-bold my-4">Your <span class="text-primary">Gateway</span> to <span class="text-primary">Cutting-Edge</span> Digital and Architecture Computer</h1>
                <p class="">We're a vibrant hub where researchers, students, and innovators collaborate to push the boundaries of digital and computer architecture. Through hands-on projects, interdisciplinary teamwork, and a commitment to impactful solutions, we empower every member to turn ideas into technological breakthroughs.</p>
            </div>
            <img src="" alt="">
            <img class="absolute -right-96 -top-20 lg:-top-4 lg:-right-10 min-w-md lg:max-w-2xl z-20" src="{{ asset('images/Item4.png') }}" alt="Item 4">
            <div class="absolute lg:min-w-full min-w-xl inset-0 -top-52 lg:-top-16 z-0">
                <img class="min-w-full" src="{{ asset('images/Item5.png') }}" alt="Item 5">
            </div>
        </div>
    </section>

    <section class="py-20 lg:py-12">
        <h1 class="text-primary text-4xl font-bold tracking-wide text-center mb-8">How We're Here for You</h1>
        <div class="grid grid-cols-10 gap-8 lg:gap-4 text-white">
            <div class="col-span-10 lg:col-span-4 grid grid-rows-4 gap-4">
                <div class="row-span-1 bg-primary/5 h-14 rounded-r-2xl"></div>
                <div class="row-span-1 grid grid-cols-6 gap-3">
                    <div class="col-span-2 lg:col-span-2 bg-primary/5 rounded-r-2xl"></div>
                    <div class="col-span-2 lg:col-span-3 bg-primary rounded-2xl grid place-items-center px-2">
                        <p class="text-center text-sm font-bold tracking-wide">Competition & Hackathons</p>
                    </div>
                    <div class="col-span-2 lg:col-span-1 bg-primary/5 rounded-2xl"></div>
                </div>
                <div class="row-span-2 grid grid-cols-6 gap-3">
                    <div class="col-span-2 lg:col-span-2 bg-primary/5 rounded-r-2xl"></div>
                    <div class="col-span-2 lg:col-span-3 bg-primary rounded-2xl flex justify-center items-center px-4 py-2">
                        <p class="text-center text-sm">
                            Help you develop creativity, resilience, and teamwork while gaining invaluable experience in a competitive environment.
                        </p>
                    </div>
                    <div class="col-span-2 lg:col-span-1 bg-primary/5 rounded-2xl"></div>
                </div>
            </div>
            <div class="col-span-10 lg:col-span-2 grid grid-rows-4 gap-4">
                <div class="row-span-1 grid grid-cols-6 gap-3">
                    <div class="col-span-2 lg:hidden h-14 bg-dark-digikom/5 rounded-2xl"></div>
                    <div class="col-span-2 lg:col-span-6 grid place-items-center rounded-2xl px-2 bg-dark-digikom ">
                        <p class="text-center text-sm font-bold tracking-wide">Practice</p>
                    </div>
                    <div class="col-span-2 lg:hidden bg-dark-digikom/5 rounded-2xl"></div>
                </div>
                <div class="row-span-2 grid grid-cols-6 gap-3">
                    <div class="col-span-2 lg:hidden bg-dark-digikom/5 rounded-2xl"></div>
                    <div class="col-span-2 lg:col-span-6 grid place-items-center rounded-2xl px-2 bg-dark-digikom ">
                        <p class="text-center text-sm">Learn and practice about digital logic and computer architecture.</p>
                    </div>
                    <div class="col-span-2 lg:hidden bg-dark-digikom/5 rounded-2xl"></div>
                </div>
                <div class="row-span-1 bg-dark-digikom/5 rounded-2xl"></div>
            </div>
            <div class="col-span-10 lg:col-span-4 grid grid-rows-4 gap-4">
                <div class="row-span-1 bg-red-digikom/5 h-14 rounded-l-2xl"></div>
                <div class="row-span-1 grid grid-cols-6 gap-3">
                    <div class="col-span-2 lg:col-span-1 bg-red-digikom/5 rounded-2xl"></div>
                    <div class="col-span-2 lg:col-span-3 bg-red-digikom rounded-2xl px-4 py-2 flex justify-center items-center">
                        <p class="text-center text-sm font-bold tracking-wide">Project-Based Learning</p>
                    </div>
                    <div class="col-span-2 lg:col-span-2 bg-red-digikom/10 rounded-l-xl"></div>
                </div>
                <div class="row-span-2 grid grid-cols-6 gap-3">
                    <div class="col-span-2 lg:col-span-1 bg-red-digikom/5 rounded-2xl"></div>
                    <div class="col-span-2 lg:col-span-3 bg-red-digikom rounded-2xl px-4 py-2 flex justify-center items-center">
                        <p class="text-center text-sm">Builds critical thinking and problem-solving through hands-on tasks and collaboration</p>
                    </div>
                    <div class="col-span-2 lg:col-span-2 bg-red-digikom/5 rounded-l-xl"></div>
                </div>
            </div>
        </div>
    </section>
    
</x-app-layout>