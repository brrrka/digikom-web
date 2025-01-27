<nav class="fixed top-0 left-0 right-0 z-50">
    {{-- Main Navbar --}}
    <div class="relative bg-white">
        <div class="max-w-7xl mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <div class="flex-shrink-0 flex items-center">
                    {{-- Mobile Menu Button --}}
                    <div class="lg:hidden">
                        <button onclick="toggleMenu()" class="inline-flex items-center justify-center p-2 rounded-md text-primary hover:text-gray-900 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
                            <svg id="menuIcon" class="h-8 w-8" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                    </div>
                    {{-- Logo --}}
                    <a href="{{ route('home') }}">
                        <img src="{{ asset('images/LogoDigikom.png') }}" class="w-12 h-12" alt="Logo Digikom">
                    </a>
                </div>

                {{-- Desktop Menu --}}
                <div class="hidden lg:block">
                    <div class="ml-10 flex items-center space-x-4 text-gray-700">
                        <a href="{{ route('home') }}" 
                           class="hover:text-gray-900 px-3 py-2 rounded-md hover:font-medium transition-all duration-200 {{ request()->routeIs('home') ? 'font-semibold text-gray-900' : '' }}">
                            Home
                        </a>
                        <a href="{{ route('praktikum.index') }}" 
                           class="hover:text-gray-900 px-3 py-2 rounded-md hover:font-medium transition-all duration-200 {{ request()->routeIs('praktikum.*') ? 'font-semibold text-gray-900' : '' }}">
                            Praktikum
                        </a>
                        <a href="{{ route('peminjaman') }}" 
                           class="hover:text-gray-900 px-3 py-2 rounded-md hover:font-medium transition-all duration-200 {{ request()->routeIs('peminjaman.*') ? 'font-semibold text-gray-900' : '' }}">
                            Peminjaman
                        </a>
                        <a href="{{ route('profile.edit') }}" 
                           class="hover:text-gray-900 px-3 py-2 rounded-md hover:font-medium transition-all duration-200 {{ request()->routeIs('profile.*') ? 'font-semibold text-gray-900' : '' }}">
                            Profil
                        </a>
                    </div>
                </div>

                {{-- Login / User Dropdown --}}
                @auth
                    <div class="relative">
                        <button type="button" class="flex text-sm bg-gray-800 rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                            <span class="sr-only">Open user menu</span>
                            <img class="h-8 w-8 rounded-full" src="{{ Auth::user()->profile_photo_url ?? asset('images/default-avatar.png') }}" alt="User profile picture">
                        </button>
                        
                        {{-- User Dropdown Menu --}}
                        <div class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem" tabindex="-1" id="user-menu-item-0">Profil Saya</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Logout</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="hidden md:block rounded-full shadow-md bg-primary px-8 py-2 text-black font-semibold">
                        Login
                    </a>
                @endauth
            </div>
        </div>
    </div>

    {{-- Overlay --}}
    <div id="mobileMenuOverlay" class="fixed inset-0 bg-black opacity-0 invisible transition-all duration-300 z-40" onclick="toggleMenu()"></div>

    {{-- Mobile Menu with Left Slide Animation --}}
    <div id="mobileMenu" class="fixed top-0 left-0 h-full w-64 bg-white shadow-lg transform transition-transform duration-300 ease-in-out -translate-x-full z-50">
        {{-- Close Button --}}
        <button onclick="toggleMenu()" class="absolute top-4 right-4 text-gray-600 hover:text-gray-900">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <div class="px-4 pt-16 pb-3 space-y-1">
            <a href="{{ route('home') }}" 
               class="block hover:text-gray-900 px-3 py-2 rounded-md hover:font-medium transition-all duration-200 {{ request()->routeIs('home') ? 'font-semibold text-gray-900' : '' }}">
                Home
            </a>
            <a href="{{ route('praktikum.index') }}" 
               class="block hover:text-gray-900 px-3 py-2 rounded-md hover:font-medium transition-all duration-200 {{ request()->routeIs('praktikum.*') ? 'font-semibold text-gray-900' : '' }}">
                Praktikum
            </a>
            <a href="{{ route('peminjaman') }}" 
               class="block hover:text-gray-900 px-3 py-2 rounded-md hover:font-medium transition-all duration-200 {{ request()->routeIs('peminjaman.*') ? 'font-semibold text-gray-900' : '' }}">
                Peminjaman
            </a>
            <a href="{{ route('profile.edit') }}" 
               class="block hover:text-gray-900 px-3 py-2 rounded-md hover:font-medium transition-all duration-200 {{ request()->routeIs('profile.*') ? 'font-semibold text-gray-900' : '' }}">
                Profil
            </a>

            @guest
                <a href="{{ route('login') }}" 
                   class="block bg-primary text-center px-3 py-2 rounded-md font-semibold">
                    Login
                </a>
            @endguest
        </div>
    </div>
</nav>
