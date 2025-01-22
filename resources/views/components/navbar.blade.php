<nav class="fixed top-0 left-0 right-0 z-50">
    {{-- Main Navbar --}}
    <div class="relative bg-white">
        <div class="max-w-7xl mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                {{-- Logo --}}
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('home') }}">
                        <img src="{{ asset('images/LogoDigikom.png') }}" class="w-16 h-16" alt="Logo Digikom">
                    </a>
                </div>

                {{-- Desktop Menu --}}
                <div class="hidden md:block">
                    <div class="ml-10 flex items-center space-x-4 text-gray-700">
                        <a href="{{ route('home') }}" 
                           class="hover:text-gray-900 px-3 py-2 rounded-md hover:font-medium transition-all duration-200 {{ request()->routeIs('home') ? 'font-semibold text-gray-900' : '' }}">
                            Home
                        </a>
                        <a href="{{ route('praktikum.index') }}" 
                           class="hover:text-gray-900 px-3 py-2 rounded-md hover:font-medium transition-all duration-200 {{ request()->routeIs('praktikum.*') ? 'font-semibold text-gray-900' : '' }}">
                            Praktikum
                        </a>
                        <a href="{{ route('peminjaman.riwayat') }}" 
                           class="hover:text-gray-900 px-3 py-2 rounded-md hover:font-medium transition-all duration-200 {{ request()->routeIs('peminjaman.*') ? 'font-semibold text-gray-900' : '' }}">
                            Peminjaman
                        </a>
                        <a href="{{ route('profile.edit') }}" 
                           class="hover:text-gray-900 px-3 py-2 rounded-md hover:font-medium transition-all duration-200 {{ request()->routeIs('profile.*') ? 'font-semibold text-gray-900' : '' }}">
                            Profil
                        </a>
                    </div>
                </div>

                {{-- Login --}}
                <button class="hidden md:block rounded-full bg-primary px-8 py-2 text-black font-semibold">
                    Login
                </button>

                {{-- Mobile Menu Button --}}
                <div class="md:hidden">
                    <button onclick="toggleMenu()" class="inline-flex items-center justify-center p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
                        <svg id="menuIcon" class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Mobile Menu dengan animasi --}}
    <div id="mobileMenu" class="relative transform transition-all duration-300 ease-in-out md:hidden bg-white shadow-lg -translate-y-2 opacity-0 pointer-events-none">
        <div class="px-2 pt-2 pb-3 space-y-1">
            <a href="{{ route('home') }}" 
               class="block hover:text-gray-900 px-3 py-2 rounded-md hover:font-medium transition-all duration-200 {{ request()->routeIs('home') ? 'font-semibold text-gray-900' : '' }}">
                Home
            </a>
            <a href="{{ route('praktikum.index') }}" 
               class="block hover:text-gray-900 px-3 py-2 rounded-md hover:font-medium transition-all duration-200 {{ request()->routeIs('praktikum.*') ? 'font-semibold text-gray-900' : '' }}">
                Praktikum
            </a>
            <a href="{{ route('peminjaman.riwayat') }}" 
               class="block hover:text-gray-900 px-3 py-2 rounded-md hover:font-medium transition-all duration-200 {{ request()->routeIs('peminjaman.*') ? 'font-semibold text-gray-900' : '' }}">
                Peminjaman
            </a>
            <a href="{{ route('profile.edit') }}" 
               class="block hover:text-gray-900 px-3 py-2 rounded-md hover:font-medium transition-all duration-200 {{ request()->routeIs('profile.*') ? 'font-semibold text-gray-900' : '' }}">
                Profil
            </a>
        </div>
    </div>
</nav>

<script>
function toggleMenu() {
    const mobileMenu = document.getElementById('mobileMenu');
    if (mobileMenu.classList.contains('-translate-y-2')) {
        mobileMenu.classList.remove('-translate-y-2', 'opacity-0', 'pointer-events-none');
        mobileMenu.classList.add('translate-y-0', 'opacity-100');
    } else {
        mobileMenu.classList.add('-translate-y-2', 'opacity-0', 'pointer-events-none');
        mobileMenu.classList.remove('translate-y-0', 'opacity-100');
    }
}
</script>