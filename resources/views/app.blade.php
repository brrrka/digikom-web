<x-app-layout>
    <section class="flex h-screen justify-center items-center bg-white overflow-hidden">
        <div class="h-full w-full flex justify-center items-center">
            <div class="flex-1 text-wrap relative h-full">
                <img class="absolute top-24 w-24" src="{{ asset('images/Item1.png') }}" alt="Item 1" loading="lazy"> 
                <img class="absolute top-24 right-0 w-36" src="{{ asset('images/Item2.png') }}" alt="Item 2" loading="lazy"> 
                <div class="carousel-text absolute inset-0 h-full pl-12 pr-5 flex flex-col justify-center transition-all duration-300 ease-in-out opacity-0">
                    <h1 class="text-5xl text-dark font-bold mb-4 leading-tight">
                        Building the <span class="text-primary">Future of Computing,</span> One Byte at a Time
                    </h1>
                    <p class="text-dark text-lg">
                        With a focus on <span class="text-primary">digital systems and computer architecture,</span> we are crafting solutions that pave the way for smarter, faster, and more efficient computing. Together, let's build the future of technology, one byte at a time.
                    </p>
                </div>
                <div class="carousel-text absolute inset-0 h-full pl-12 pr-5 flex flex-col justify-center transition-all duration-300 ease-in-out opacity-0">
                    <h1 class="text-5xl text-dark font-bold mb-4 leading-tight">
                        <span class="text-primary">Empowering</span> Ideas Through Digital and Computer Architecture
                    </h1>
                    <p class="text-dark text-lg">
                        Unlock the potential of innovation with cutting-edge research in digital systems and computer architecture. We transform ideas into impactful solutions, driving the <span class="text-primary">evolution of modern technology.</span>
                    </p>
                </div>
                <div class="carousel-text absolute inset-0 h-full pl-12 pr-5 flex flex-col justify-center transition-all duration-300 ease-in-out opacity-0">
                    <h1 class="text-5xl text-dark font-bold mb-4 leading-tight">
                        Discover, Design, Deliver: <span class="text-primary">The Art of Computer Architecture</span>
                    </h1>
                    <p class="text-dark text-lg">
                        Explore the limitless possibilities of technology with groundbreaking research in computer architecture. From concept to creation, we <span class="text-primary">design and deliver solutions</span> that shape the future of computing.
                    </p>
                </div>
            </div>
            <div class="flex-1 flex justify-center items-center h-full relative">
                <div class="absolute inset-0 right-2 top-2 h-full flex items-center justify-center z-10">
                    <img src="{{ asset('images/Ellipse.png') }}" class="w-96" alt="Ellips" loading="lazy">
                </div>
                <div class="carousel-image absolute inset-0 h-full flex items-center justify-center transition-all duration-300 ease-in-out opacity-0">
                    <img class="w-96 bg-light rounded-full" src="{{ asset('images/Inti.png') }}" alt="Inti" loading="lazy">
                </div>
                <div class="carousel-image absolute inset-0 h-full flex items-center justify-center transition-all duration-300 ease-in-out opacity-0">
                    <img class="w-96 bg-light rounded-full" src="{{ asset('images/IPI.png') }}" alt="IPI" loading="lazy">
                </div>
                <div class="carousel-image absolute inset-0 h-full flex items-center justify-center transition-all duration-300 ease-in-out opacity-0">
                    <img class="w-96 bg-light rounded-full" src="{{ asset('images/Multimedia.png') }}" alt="Multimedia" loading="lazy">
                </div>
                <div class="carousel-image absolute inset-0 h-full flex items-center justify-center transition-all duration-300 ease-in-out opacity-0">
                    <img class="w-96 bg-light rounded-full" src="{{ asset('images/RnD.png') }}" alt="RnD" loading="lazy">
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const textItems = document.querySelectorAll(".carousel-text");
            const imageItems = document.querySelectorAll(".carousel-image");
            let currentTextIndex = 0;
            let currentImageIndex = 0;

            // Set initial state
            textItems[0].classList.remove("opacity-0");
            textItems[0].classList.add("opacity-100");
            imageItems[0].classList.remove("opacity-0");
            imageItems[0].classList.add("opacity-100");

            function showNextItem() {
                // Hide current items
                textItems[currentTextIndex].classList.remove("opacity-100");
                textItems[currentTextIndex].classList.add("opacity-0");
                imageItems[currentImageIndex].classList.remove("opacity-100");
                imageItems[currentImageIndex].classList.add("opacity-0");

                // Calculate next indices separately
                currentTextIndex = (currentTextIndex + 1) % textItems.length;
                currentImageIndex = (currentImageIndex + 1) % imageItems.length;

                // Show next items
                textItems[currentTextIndex].classList.remove("opacity-0");
                textItems[currentTextIndex].classList.add("opacity-100");
                imageItems[currentImageIndex].classList.remove("opacity-0");
                imageItems[currentImageIndex].classList.add("opacity-100");
            }

            // Change slide every 3 seconds
            setInterval(showNextItem, 3000);
        });
    </script>
</x-app-layout>