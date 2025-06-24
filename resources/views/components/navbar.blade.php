<nav class="fixed top-0 left-0 right-0 z-50">
    {{-- Main Navbar --}}
    <div class="relative bg-white">
        <div class="max-w-7xl mx-auto px-8 lg:px-4 py-4">
            <div class="flex justify-between items-center">
                <div class="flex-shrink-0 flex items-center">
                    {{-- Logo --}}
                    <a href="{{ route('home') }}">
                        <img src="{{ asset('images/LogoDigikom.png') }}" class="size-12 md:size-14" alt="Logo Digikom">
                    </a>
                </div>
                {{-- Mobile Menu Button --}}
                <div class="lg:hidden">
                    <button onclick="toggleMenu()"
                        class="inline-flex items-center justify-center p-2 rounded-md text-primary hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
                        <svg id="menuIcon" class="h-8 w-8" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>

                {{-- Desktop Menu --}}
                <div class="hidden lg:block">
                    <div class="ml-10 flex items-center space-x-4 text-gray-700">
                        <a href="{{ route('home') }}"
                            class="hover:text-gray-900 px-3 py-2 rounded-md hover:font-medium transition-all duration-200 {{ request()->routeIs('home') ? 'font-semibold text-gray-900' : '' }}">
                            Home
                        </a>
                        <a href="{{ route('praktikum.index') }}"
                            class="hover:text-gray-900 px-3 py-2 rounded-md hover:font-medium transition-all duration-200 {{ request()->routeIs('praktikum.*') || request()->routeIs('moduls.praktikum') ? 'font-semibold text-gray-900' : '' }}">
                            Praktikum
                        </a>
                        <a href="{{ route('peminjaman') }}"
                            class="hover:text-gray-900 px-3 py-2 rounded-md hover:font-medium transition-all duration-200 {{ request()->routeIs('peminjaman') || request()->routeIs('peminjaman.*') ? 'font-semibold text-gray-900' : '' }}">
                            Peminjaman
                        </a>
                        <a href="{{ route('artikel.index') }}"
                            class="hover:text-gray-900 px-3 py-2 rounded-md hover:font-medium transition-all duration-200 {{ request()->routeIs('artikel.*') ? 'font-semibold text-gray-900' : '' }}">
                            Artikel
                        </a>
                        <a href="{{ route('digikom.index') }}"
                            class="hover:text-gray-900 px-3 py-2 rounded-md hover:font-medium transition-all duration-200 {{ request()->routeIs('digikom.*') ? 'font-semibold text-gray-900' : '' }}">
                            Profil
                        </a>

                    </div>
                </div>

                {{-- Login / User Dropdown --}}
                @auth
                    <div class="relative hidden lg:block" x-data="{ open: false }">
                        <button type="button"
                            class="flex text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-500 focus:ring-white"
                            id="user-menu-button" @click="open = !open" aria-expanded="false" aria-haspopup="true">
                            <span class="sr-only">Open user menu</span>
                            @if (request()->routeIs('profile.*'))
                                <x-heroicon-s-user-circle class="w-10 h-10 text-gray-500" />
                            @else
                                <x-heroicon-o-user-circle class="w-10 h-10 text-gray-500" />
                            @endif
                        </button>

                        <div x-show="open" x-cloak @click.away="open = false"
                            class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none"
                            role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button">
                            <a href="{{ route('profile.edit') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Profil Saya
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}"
                        class="hidden lg:block rounded-full shadow-md bg-primary px-8 py-2 text-black font-semibold">
                        Login
                    </a>
                @endauth
            </div>
        </div>
    </div>

    {{-- Overlay --}}
    <div id="mobileMenuOverlay" class="fixed inset-0 bg-black/50 opacity-0 invisible transition-all duration-300 z-40"
        onclick="toggleMenu()"></div>

    {{-- Mobile Menu with Left Slide Animation --}}
    <div id="mobileMenu"
        class="fixed top-0 left-0 h-full w-64 bg-white shadow-lg transform transition-transform duration-300 ease-in-out -translate-x-full z-50">
        {{-- Close Button --}}
        <button onclick="toggleMenu()" class="absolute top-4 right-4 text-gray-600 hover:text-gray-900">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <div class="px-4 pt-16 pb-3 space-y-1">
            <a href="{{ route('home') }}"
                class="block text-primary px-3 py-2 rounded-full md:rounded-xl hover:font-medium transition-all duration-200 {{ request()->routeIs('home') ? 'text-white bg-primary' : '' }}">
                Home
            </a>
            <a href="{{ route('praktikum.index') }}"
                class="block text-primary px-3 py-2 rounded-full md:rounded-xl hover:font-medium transition-all duration-200 {{ request()->routeIs('praktikum.*') || request()->routeIs('moduls.praktikum') ? 'text-white bg-primary' : '' }}">
                Praktikum
            </a>
            <a href="{{ route('peminjaman') }}"
                class="block text-primary px-3 py-2 rounded-full md:rounded-xl hover:font-medium transition-all duration-200 {{ request()->routeIs('peminjaman') || request()->routeIs('peminjaman.*') ? 'text-white bg-primary' : '' }}">
                Peminjaman
            </a>
            <a href="{{ route('artikel.index') }}"
                class="block text-primary px-3 py-2 rounded-full md:rounded-xl hover:font-medium transition-all duration-200 {{ request()->routeIs('artikel.*') ? 'text-white bg-primary' : '' }}">
                Artikel
            </a>
            <a href="{{ route('digikom.index') }}"
                class="block text-primary px-3 py-2 rounded-full md:rounded-xl hover:font-medium transition-all duration-200 {{ request()->routeIs('digikom.*') ? 'text-white bg-primary' : '' }}">
                Profil
            </a>
            @auth
                {{-- Username Dropdown Menu --}}
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                        class="flex items-center justify-between w-full px-3 py-2 text-primary rounded-xl transition-all duration-200 {{ request()->routeIs('profile.*') ? 'text-white bg-primary' : '' }}">
                        <span>{{ Auth::user()->name }}</span>
                        <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }"
                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div x-show="open" class="pl-4 space-y-1">
                        <a href="{{ route('profile.edit') }}"
                            class="block px-3 py-2 text-primary rounded-xl transition-all duration-200 {{ request()->routeIs('profile.*') ? 'font-semibold' : '' }}">
                            Profil Saya
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="block w-full text-left px-3 py-2 text-primary hover:font-medium transition-all duration-200">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}"
                    class="inline-block bg-primary text-primary border border-primary bg-transparent hover:bg-primary hover:text-white transition-all duration-200 text-center px-8 py-2 rounded-full shadow-md">
                    Login
                </a>
            @endauth
        </div>
    </div>
</nav>
