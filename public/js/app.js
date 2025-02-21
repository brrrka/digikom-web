document.addEventListener("DOMContentLoaded", function () {
    // Toggle Mobile Menu
    function toggleMenu() {
        const mobileMenu = document.getElementById("mobileMenu");
        const mobileMenuOverlay = document.getElementById("mobileMenuOverlay");

        if (mobileMenu.classList.contains("-translate-x-full")) {
            // Open menu
            mobileMenu.classList.remove("-translate-x-full");
            mobileMenu.classList.add("translate-x-0");

            // Show overlay
            mobileMenuOverlay.classList.remove("invisible", "opacity-0");
            mobileMenuOverlay.classList.add("visible", "opacity-50");
        } else {
            // Close menu
            mobileMenu.classList.add("-translate-x-full");
            mobileMenu.classList.remove("translate-x-0");

            // Hide overlay
            mobileMenuOverlay.classList.add("invisible", "opacity-0");
            mobileMenuOverlay.classList.remove("visible", "opacity-50");
        }
    }

    // Attach toggleMenu to window if not already attached
    window.toggleMenu = toggleMenu;

    // User Dropdown Handler
    const userMenuButton = document.getElementById("user-menu-button");
    const userDropdownMenu = document.querySelector('[role="menu"]');

    if (userMenuButton && userDropdownMenu) {
        // Sembunyikan dropdown awalnya
        userDropdownMenu.classList.add("hidden");

        userMenuButton.addEventListener("click", function (event) {
            event.stopPropagation(); // Prevent immediate closing
            userDropdownMenu.classList.toggle("hidden");
        });

        // Tutup dropdown jika mengklik di luar
        document.addEventListener("click", function (event) {
            if (
                !userMenuButton.contains(event.target) &&
                !userDropdownMenu.contains(event.target)
            ) {
                userDropdownMenu.classList.add("hidden");
            }
        });
    }

    // Carousel Handler (if needed)
    const textItems = document.querySelectorAll(".carousel-text");
    const imageItems = document.querySelectorAll(".carousel-image");

    if (textItems.length > 0 && imageItems.length > 0) {
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
    }
});

const item1 = document.querySelectorAll(".item-1");
const item2 = document.querySelectorAll(".item-2");
const item3 = document.querySelectorAll(".item-3");
const itemsTransparent = document.querySelectorAll(".items-transparent");

item1.forEach((item) => {
    item.addEventListener("mouseover", function () {
        item2.forEach((item) => {
            item.classList.add("opacity-45");
        });

        item3.forEach((item) => {
            item.classList.add("opacity-45");
        });

        itemsTransparent.forEach((item) => {
            // Hapus dulu semua class background yang mungkin menggangu
            item.classList.remove("bg-primary/5");
            item.classList.remove("bg-dark-digikom/5");
            item.classList.remove("bg-red-digikom/5");

            // Tambahkan class baru
            item.classList.add("bg-primary");
            item.classList.add("opacity-45");
        });
    });

    item.addEventListener("mouseout", function () {
        item2.forEach((item) => {
            item.classList.remove("opacity-45");
        });

        item3.forEach((item) => {
            item.classList.remove("opacity-45");
        });

        itemsTransparent.forEach((item) => {
            item.classList.remove("bg-primary");
            item.classList.remove("opacity-45");
        });
    });
});

item2.forEach((item) => {
    item.addEventListener("mouseover", function () {
        item1.forEach((item) => {
            item.classList.add("opacity-35");
        });

        item3.forEach((item) => {
            item.classList.add("opacity-35");
        });

        itemsTransparent.forEach((item) => {
            // Hapus dulu semua class background yang mungkin menggangu
            item.classList.remove("bg-primary/5");
            item.classList.remove("bg-dark-digikom/5");
            item.classList.remove("bg-red-digikom/5");

            // Tambahkan class baru
            item.classList.add("bg-dark-digikom");
            item.classList.add("opacity-35");
        });
    });

    item.addEventListener("mouseout", function () {
        item1.forEach((item) => {
            item.classList.remove("opacity-35");
        });

        item3.forEach((item) => {
            item.classList.remove("opacity-35");
        });

        itemsTransparent.forEach((item) => {
            item.classList.remove("bg-dark-digikom");
            item.classList.remove("opacity-35");
        });
    });
});

item3.forEach((item) => {
    item.addEventListener("mouseover", function () {
        item1.forEach((item) => {
            item.classList.add("opacity-35");
        });

        item2.forEach((item) => {
            item.classList.add("opacity-35");
        });

        itemsTransparent.forEach((item) => {
            // Hapus dulu semua class background yang mungkin menggangu
            item.classList.remove("bg-primary/5");
            item.classList.remove("bg-dark-digikom/5");
            item.classList.remove("bg-red-digikom/5");

            // Tambahkan class baru
            item.classList.add("bg-red-digikom");
            item.classList.add("opacity-35");
        });
    });

    item.addEventListener("mouseout", function () {
        item1.forEach((item) => {
            item.classList.remove("opacity-35");
        });

        item2.forEach((item) => {
            item.classList.remove("opacity-35");
        });

        itemsTransparent.forEach((item) => {
            item.classList.remove("bg-red-digikom");
            item.classList.remove("opacity-35");
        });
    });
});
