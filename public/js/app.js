function toggleMenu() {
    const mobileMenu = document.getElementById("mobileMenu");
    if (mobileMenu.classList.contains("-translate-y-2")) {
        mobileMenu.classList.remove(
            "-translate-y-2",
            "opacity-0",
            "pointer-events-none"
        );
        mobileMenu.classList.add("translate-y-0", "opacity-100");
    } else {
        mobileMenu.classList.add(
            "-translate-y-2",
            "opacity-0",
            "pointer-events-none"
        );
        mobileMenu.classList.remove("translate-y-0", "opacity-100");
    }
}

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
