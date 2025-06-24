<x-app-layout>
    <x-slot name="title">Artikel & Berita - Digikom Lab</x-slot>

    <!-- Hero Section -->
    <section class="relative pt-24 pb-16">
        <div class="container mx-auto px-6 text-center">
            <h1 class="text-4xl md:text-6xl font-bold text-dark-digikom mb-6">
                Artikel & <span class="text-primary">Berita</span>
            </h1>
            <p class="text-lg md:text-xl text-dark-digikom/80 max-w-2xl mx-auto mb-8">
                Temukan wawasan terbaru tentang teknologi digital, arsitektur komputer, dan inovasi dalam dunia
                komputasi
            </p>

            <!-- Search Bar -->
            <div class="max-w-lg mx-auto">
                <form method="GET" class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari artikel..."
                        class="w-full px-6 py-4 rounded-full border-2 border-primary/20 focus:border-primary outline-none bg-white/80 backdrop-blur-sm text-dark-digikom placeholder-dark-digikom/60">
                    <button type="submit"
                        class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-primary hover:bg-primary/90 text-white p-3 rounded-full transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </section>

    <!-- Filter & Stats Section -->
    <section class="py-8 bg-white/50 backdrop-blur-sm sticky top-0 z-30 border-b border-gray-200/50">
        <div class="container mx-auto px-6">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <!-- Filter Tabs -->
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('artikel.index') }}"
                        class="px-4 py-2 rounded-full text-sm font-medium transition-colors {{ !request('tag') ? 'bg-primary text-white' : 'bg-gray-100 text-dark-digikom hover:bg-gray-200' }}">
                        Semua
                    </a>
                    @foreach ($popular_tags as $tag => $count)
                        <a href="{{ route('artikel.index', ['tag' => $tag]) }}"
                            class="px-4 py-2 rounded-full text-sm font-medium transition-colors {{ request('tag') == $tag ? 'bg-primary text-white' : 'bg-gray-100 text-dark-digikom hover:bg-gray-200' }}">
                            {{ ucfirst($tag) }} ({{ $count }})
                        </a>
                    @endforeach
                </div>

                <!-- Stats -->
                <div class="text-sm text-dark-digikom/70">
                    Menampilkan {{ $artikels->count() }} dari {{ $artikels->total() }} artikel
                </div>
            </div>
        </div>
    </section>

    <!-- Articles Grid -->
    <section class="py-16 min-h-screen">
        <div class="container mx-auto px-6">
            @if ($artikels->count() > 0)
                <!-- Featured Article (First Article) -->
                @if ($artikels->isNotEmpty() && !request('search') && !request('tag'))
                    <div class="mb-16">
                        <h2 class="text-2xl font-bold text-dark-digikom mb-8">Artikel Terbaru</h2>
                        @php $featured = $artikels->first() @endphp
                        <article
                            class="group relative bg-white rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2">
                            <div class="md:flex">
                                <div class="md:w-1/2 relative overflow-hidden">
                                    @if ($featured->image)
                                        <img src="{{ Storage::url($featured->image) }}" alt="{{ $featured->title }}"
                                            class="w-full h-64 md:h-80 object-cover group-hover:scale-110 transition-transform duration-700">
                                    @else
                                        <div
                                            class="w-full h-64 md:h-80 bg-gradient-to-br from-primary/20 to-dark-digikom/20 flex items-center justify-center">
                                            <svg class="w-16 h-16 text-primary/40" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                                                </path>
                                            </svg>
                                        </div>
                                    @endif
                                    <div
                                        class="absolute inset-0 bg-black/20 group-hover:bg-black/10 transition-colors duration-300">
                                    </div>
                                </div>
                                <div class="md:w-1/2 p-8 md:p-12 flex flex-col justify-center">
                                    <div class="flex items-center gap-3 mb-4">
                                        <span
                                            class="px-3 py-1 bg-primary/10 text-primary text-xs font-medium rounded-full">
                                            Featured
                                        </span>
                                        <span class="text-dark-digikom/60 text-sm">
                                            {{ $featured->published_at->diffForHumans() }}
                                        </span>
                                    </div>
                                    <h3
                                        class="text-2xl md:text-3xl font-bold text-dark-digikom mb-4 group-hover:text-primary transition-colors">
                                        {{ $featured->title }}
                                    </h3>
                                    <p class="text-dark-digikom/70 mb-6 line-clamp-3">
                                        {{ $featured->excerpt }}
                                    </p>
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-8 h-8 bg-primary/20 rounded-full flex items-center justify-center">
                                                <span
                                                    class="text-xs font-medium text-primary">{{ substr($featured->user->name ?? 'A', 0, 1) }}</span>
                                            </div>
                                            <span
                                                class="text-sm text-dark-digikom/70">{{ $featured->user->name ?? 'Anonymous' }}</span>
                                        </div>
                                        <a href="{{ route('artikel.show', $featured->slug ?? $featured->id) }}"
                                            class="inline-flex items-center gap-2 text-primary hover:text-primary/80 font-medium group/link">
                                            Baca Selengkapnya
                                            <svg class="w-4 h-4 group-hover/link:translate-x-1 transition-transform"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </article>
                    </div>
                @endif

                <!-- Articles Grid -->
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach ($artikels->skip($artikels->isNotEmpty() && !request('search') && !request('tag') ? 1 : 0) as $artikel)
                        <article
                            class="group bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                            <!-- Image -->
                            <div class="relative overflow-hidden">
                                @if ($artikel->image)
                                    <img src="{{ Storage::url($artikel->image) }}" alt="{{ $artikel->title }}"
                                        class="w-full h-48 object-cover group-hover:scale-110 transition-transform duration-500">
                                @else
                                    <div
                                        class="w-full h-48 bg-gradient-to-br from-primary/10 to-dark-digikom/10 flex items-center justify-center">
                                        <svg class="w-12 h-12 text-primary/40" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                                            </path>
                                        </svg>
                                    </div>
                                @endif
                                <div class="absolute top-4 left-4">
                                    <span
                                        class="px-2 py-1 bg-white/90 backdrop-blur-sm text-dark-digikom text-xs rounded-full">
                                        {{ $artikel->reading_time }} min read
                                    </span>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="p-6">
                                <div class="flex items-center gap-2 mb-3">
                                    <span class="text-primary/80 text-sm">
                                        {{ $artikel->published_at->format('M d, Y') }}
                                    </span>
                                    <span class="w-1 h-1 bg-primary/40 rounded-full"></span>
                                    <span class="text-dark-digikom/60 text-sm">
                                        {{ $artikel->user->name ?? 'Anonymous' }}
                                    </span>
                                </div>

                                <h3
                                    class="text-xl font-bold text-dark-digikom mb-3 group-hover:text-primary transition-colors line-clamp-2">
                                    {{ $artikel->title }}
                                </h3>

                                <p class="text-dark-digikom/70 text-sm mb-4 line-clamp-3">
                                    {{ $artikel->excerpt }}
                                </p>

                                <!-- Tags -->
                                @if ($artikel->tags)
                                    <div class="flex flex-wrap gap-1 mb-4">
                                        @foreach (array_slice(explode(',', $artikel->tags), 0, 2) as $tag)
                                            <span class="px-2 py-1 bg-primary/10 text-primary text-xs rounded-full">
                                                #{{ trim($tag) }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif

                                <a href="{{ route('artikel.show', $artikel->slug ?? $artikel->id) }}"
                                    class="inline-flex items-center gap-2 text-primary hover:text-primary/80 font-medium text-sm group/link">
                                    Baca Artikel
                                    <svg class="w-4 h-4 group-hover/link:translate-x-1 transition-transform"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                    </svg>
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if ($artikels->hasPages())
                    <div class="mt-16 flex justify-center">
                        {{ $artikels->appends(request()->query())->links('pagination::tailwind') }}
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="text-center py-16">
                    <div class="max-w-md mx-auto">
                        <svg class="w-16 h-16 text-primary/40 mx-auto mb-6" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                            </path>
                        </svg>
                        <h3 class="text-xl font-bold text-dark-digikom mb-2">Artikel Tidak Ditemukan</h3>
                        <p class="text-dark-digikom/60 mb-6">
                            @if (request('search'))
                                Tidak ada artikel yang cocok dengan pencarian "{{ request('search') }}"
                            @elseif(request('tag'))
                                Tidak ada artikel dengan tag "{{ request('tag') }}"
                            @else
                                Belum ada artikel yang dipublikasi
                            @endif
                        </p>
                        @if (request('search') || request('tag'))
                            <a href="{{ route('artikel.index') }}"
                                class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-white rounded-full hover:bg-primary/90 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Lihat Semua Artikel
                            </a>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </section>

    <!-- Login/Register CTA Section (hanya tampil jika belum login) -->
    @guest
        <section class="py-16 bg-gradient-to-r from-primary/10 to-dark-digikom/10">
            <div class="container mx-auto px-6 text-center">
                <h2 class="text-3xl font-bold text-dark-digikom mb-4">
                    Bergabunglah dengan <span class="text-primary">Komunitas</span>
                </h2>
                <p class="text-dark-digikom/70 mb-8 max-w-xl mx-auto">
                    Daftar sekarang untuk mendapatkan akses penuh ke semua artikel dan fitur eksklusif Digikom Lab
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('register') }}"
                        class="px-8 py-3 bg-primary text-white rounded-full hover:bg-primary/90 transition-colors font-medium">
                        Daftar Sekarang
                    </a>
                    <a href="{{ route('login') }}"
                        class="px-8 py-3 border border-primary text-primary rounded-full hover:bg-primary hover:text-white transition-colors font-medium">
                        Masuk
                    </a>
                </div>
            </div>
        </section>
    @endguest

    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</x-app-layout>
