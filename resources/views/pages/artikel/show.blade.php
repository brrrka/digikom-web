<x-app-layout>
    <x-slot name="title">{{ $artikel->title }} - Digikom Lab</x-slot>

    <!-- Back Button & Breadcrumb -->
    <section class="py-6 bg-white/50 backdrop-blur-sm border-b border-gray-200/50">
        <div class="container mx-auto px-6">
            <nav class="flex items-center gap-3 text-sm">
                <a href="{{ route('artikel.index') }}"
                    class="flex items-center gap-2 text-primary hover:text-primary/80 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Artikel
                </a>
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <span class="text-dark-digikom/70 truncate">{{ Str::limit($artikel->title, 50) }}</span>
            </nav>
        </div>
    </section>

    <!-- Article Header -->
    <section class="py-12 md:py-20">
        <div class="container mx-auto px-6">
            <div class="max-w-4xl mx-auto">
                <!-- Meta Info -->
                <div class="flex flex-wrap items-center gap-4 mb-6 text-sm">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-primary/20 rounded-full flex items-center justify-center">
                            <span
                                class="text-sm font-medium text-primary">{{ substr($artikel->user->name ?? 'A', 0, 1) }}</span>
                        </div>
                        <div>
                            <div class="font-medium text-dark-digikom">{{ $artikel->user->name ?? 'Anonymous' }}</div>
                            <div class="text-dark-digikom/60">Author</div>
                        </div>
                    </div>
                    <div class="hidden md:block w-1 h-1 bg-gray-300 rounded-full"></div>
                    <div class="text-dark-digikom/60">
                        {{ $artikel->published_at->format('M d, Y') }}
                    </div>
                    <div class="hidden md:block w-1 h-1 bg-gray-300 rounded-full"></div>
                    <div class="flex items-center gap-1 text-dark-digikom/60">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ $artikel->reading_time }} min read
                    </div>
                </div>

                <!-- Title -->
                <h1 class="text-3xl md:text-5xl font-bold text-dark-digikom mb-6 leading-tight">
                    {{ $artikel->title }}
                </h1>

                <!-- Tags -->
                @if ($artikel->tags)
                    <div class="flex flex-wrap gap-2 mb-8">
                        @foreach (explode(',', $artikel->tags) as $tag)
                            <a href="{{ route('artikel.index', ['tag' => trim($tag)]) }}"
                                class="px-3 py-1 bg-primary/10 text-primary text-sm rounded-full hover:bg-primary/20 transition-colors">
                                #{{ trim($tag) }}
                            </a>
                        @endforeach
                    </div>
                @endif

                <!-- Featured Image -->
                @if ($artikel->image)
                    <div class="mb-12 rounded-2xl overflow-hidden shadow-lg">
                        <img src="{{ Storage::url($artikel->image) }}" alt="{{ $artikel->title }}"
                            class="w-full h-64 md:h-96 object-cover">
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Article Content -->
    <section class="pb-16">
        <div class="container mx-auto px-6">
            <div class="max-w-4xl mx-auto">
                <div class="flex gap-12">
                    <!-- Main Content -->
                    <article class="flex-1">
                        <div class="prose prose-lg max-w-none">
                            <div class="text-dark-digikom/80 leading-relaxed text-lg">
                                {!! $artikel->content !!}
                            </div>
                        </div>

                        <!-- Article Footer -->
                        <div class="mt-12 pt-8 border-t border-gray-200">
                            <!-- Share Buttons -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <span class="text-dark-digikom font-medium">Bagikan:</span>
                                    <div class="flex gap-2">
                                        <a href="https://twitter.com/intent/tweet?text={{ urlencode($artikel->title) }}&url={{ urlencode(request()->url()) }}"
                                            target="_blank"
                                            class="w-10 h-10 bg-blue-400 text-white rounded-full flex items-center justify-center hover:bg-blue-500 transition-colors">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                                            </svg>
                                        </a>
                                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}"
                                            target="_blank"
                                            class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center hover:bg-blue-700 transition-colors">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                                            </svg>
                                        </a>
                                        <a href="https://wa.me/?text={{ urlencode($artikel->title . ' - ' . request()->url()) }}"
                                            target="_blank"
                                            class="w-10 h-10 bg-green-500 text-white rounded-full flex items-center justify-center hover:bg-green-600 transition-colors">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347" />
                                            </svg>
                                        </a>
                                        <button onclick="copyToClipboard()"
                                            class="w-10 h-10 bg-gray-500 text-white rounded-full flex items-center justify-center hover:bg-gray-600 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <!-- Reading Progress -->
                                <div class="text-sm text-dark-digikom/60">
                                    <span id="reading-progress">0%</span> dibaca
                                </div>
                            </div>

                            <!-- Author Bio -->
                            <div class="mt-8 p-6 bg-gray-50 rounded-2xl">
                                <div class="flex items-start gap-4">
                                    <div
                                        class="w-16 h-16 bg-primary/20 rounded-full flex items-center justify-center flex-shrink-0">
                                        <span
                                            class="text-xl font-bold text-primary">{{ substr($artikel->user->name ?? 'A', 0, 1) }}</span>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="text-xl font-bold text-dark-digikom mb-2">
                                            {{ $artikel->user->name ?? 'Anonymous' }}</h4>
                                        <p class="text-dark-digikom/70 text-sm">
                                            Penulis artikel tentang teknologi digital dan arsitektur komputer di Digikom
                                            Lab.
                                            Berdedikasi untuk berbagi pengetahuan dan inovasi dalam dunia komputasi.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </article>

                    <!-- Sidebar (Desktop only) -->
                    <aside class="hidden lg:block w-80 pl-8">
                        <div class="sticky top-24">
                            <!-- Table of Contents -->
                            <div class="bg-white rounded-2xl p-6 shadow-lg mb-8">
                                <h3 class="text-lg font-bold text-dark-digikom mb-4">Daftar Isi</h3>
                                <div id="table-of-contents" class="space-y-2 text-sm">
                                    <!-- Will be populated by JavaScript -->
                                </div>
                            </div>

                            <!-- Previous/Next Articles -->
                            <div class="bg-white rounded-2xl p-6 shadow-lg">
                                <h3 class="text-lg font-bold text-dark-digikom mb-4">Navigasi Artikel</h3>
                                <div id="article-navigation" class="space-y-4">
                                    @php
                                        // Ambil artikel sebelumnya
                                        $previousArticle = \App\Models\Artikel::where('status', 'published')
                                            ->whereNotNull('published_at')
                                            ->where('published_at', '<', $artikel->published_at)
                                            ->orderBy('published_at', 'desc')
                                            ->first();

                                        // Ambil artikel sesudahnya
                                        $nextArticle = \App\Models\Artikel::where('status', 'published')
                                            ->whereNotNull('published_at')
                                            ->where('published_at', '>', $artikel->published_at)
                                            ->orderBy('published_at', 'asc')
                                            ->first();
                                    @endphp

                                    @if ($previousArticle)
                                        <div class="group">
                                            <div class="text-xs text-dark-digikom/60 mb-1">Artikel Sebelumnya</div>
                                            <a href="{{ route('artikel.show', $previousArticle->slug ?? $previousArticle->id) }}"
                                                class="block">
                                                <h4
                                                    class="font-medium text-dark-digikom group-hover:text-primary transition-colors text-sm line-clamp-2">
                                                    {{ $previousArticle->title }}
                                                </h4>
                                            </a>
                                        </div>
                                    @endif

                                    @if ($nextArticle)
                                        <div class="group">
                                            <div class="text-xs text-dark-digikom/60 mb-1">Artikel Selanjutnya</div>
                                            <a href="{{ route('artikel.show', $nextArticle->slug ?? $nextArticle->id) }}"
                                                class="block">
                                                <h4
                                                    class="font-medium text-dark-digikom group-hover:text-primary transition-colors text-sm line-clamp-2">
                                                    {{ $nextArticle->title }}
                                                </h4>
                                            </a>
                                        </div>
                                    @endif

                                    @if (!$previousArticle && !$nextArticle)
                                        <p class="text-dark-digikom/50 text-sm">Tidak ada artikel lain</p>
                                    @endif
                                </div>
                                <a href="{{ route('artikel.index') }}"
                                    class="inline-flex items-center gap-2 text-primary hover:text-primary/80 font-medium text-sm mt-4">
                                    Lihat Semua Artikel
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </aside>
                </div>
            </div>
        </div>
    </section>

    <!-- Previous/Next Articles Section (Mobile & Additional) -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-6">
            <div class="max-w-4xl mx-auto">
                <h2 class="text-2xl md:text-3xl font-bold text-dark-digikom mb-8 text-center">
                    Artikel <span class="text-primary">Lainnya</span>
                </h2>

                <div class="grid md:grid-cols-2 gap-6">
                    @if ($previousArticle)
                        <article
                            class="group bg-white rounded-xl overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                            <div class="relative overflow-hidden">
                                @if ($previousArticle->image)
                                    <img src="{{ Storage::url($previousArticle->image) }}"
                                        alt="{{ $previousArticle->title }}"
                                        class="w-full h-40 object-cover group-hover:scale-110 transition-transform duration-500">
                                @else
                                    <div
                                        class="w-full h-40 bg-gradient-to-br from-primary/10 to-dark-digikom/10 flex items-center justify-center">
                                        <svg class="w-8 h-8 text-primary/40" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                                            </path>
                                        </svg>
                                    </div>
                                @endif
                                <div class="absolute top-4 left-4">
                                    <span
                                        class="px-2 py-1 bg-blue-500 text-white text-xs rounded-full">Sebelumnya</span>
                                </div>
                            </div>
                            <div class="p-4">
                                <div class="text-xs text-primary/80 mb-2">
                                    {{ $previousArticle->published_at->diffForHumans() }}</div>
                                <h3
                                    class="font-bold text-dark-digikom group-hover:text-primary transition-colors text-sm mb-2 line-clamp-2">
                                    {{ $previousArticle->title }}
                                </h3>
                                <p class="text-xs text-dark-digikom/70 line-clamp-2 mb-3">
                                    {{ \Illuminate\Support\Str::limit(strip_tags($previousArticle->content), 150) }}
                                </p>
                                <a href="{{ route('artikel.show', $previousArticle->slug ?? $previousArticle->id) }}"
                                    class="inline-flex items-center gap-1 text-primary hover:text-primary/80 font-medium text-xs">
                                    Baca Artikel
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                    </svg>
                                </a>
                            </div>
                        </article>
                    @endif

                    @if ($nextArticle)
                        <article
                            class="group bg-white rounded-xl overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                            <div class="relative overflow-hidden">
                                @if ($nextArticle->image)
                                    <img src="{{ Storage::url($nextArticle->image) }}"
                                        alt="{{ $nextArticle->title }}"
                                        class="w-full h-40 object-cover group-hover:scale-110 transition-transform duration-500">
                                @else
                                    <div
                                        class="w-full h-40 bg-gradient-to-br from-primary/10 to-dark-digikom/10 flex items-center justify-center">
                                        <svg class="w-8 h-8 text-primary/40" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                                            </path>
                                        </svg>
                                    </div>
                                @endif
                                <div class="absolute top-4 left-4">
                                    <span
                                        class="px-2 py-1 bg-green-500 text-white text-xs rounded-full">Selanjutnya</span>
                                </div>
                            </div>
                            <div class="p-4">
                                <div class="text-xs text-primary/80 mb-2">
                                    {{ $nextArticle->published_at->diffForHumans() }}</div>
                                <h3
                                    class="font-bold text-dark-digikom group-hover:text-primary transition-colors text-sm mb-2 line-clamp-2">
                                    {{ $nextArticle->title }}
                                </h3>
                                <p class="text-xs text-dark-digikom/70 line-clamp-2 mb-3">
                                    {{ \Illuminate\Support\Str::limit(strip_tags($nextArticle->content), 150) }}</p>
                                <a href="{{ route('artikel.show', $nextArticle->slug ?? $nextArticle->id) }}"
                                    class="inline-flex items-center gap-1 text-primary hover:text-primary/80 font-medium text-xs">
                                    Baca Artikel
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                    </svg>
                                </a>
                            </div>
                        </article>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Login/Register CTA Section (hanya tampil jika belum login) -->
    @guest
        <section class="py-16 bg-gradient-to-r from-primary/10 to-dark-digikom/10">
            <div class="container mx-auto px-6 text-center">
                <h2 class="text-2xl md:text-3xl font-bold text-dark-digikom mb-4">
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Reading Progress
            const article = document.querySelector('article');
            const progressElement = document.getElementById('reading-progress');

            function updateReadingProgress() {
                const articleTop = article.offsetTop;
                const articleHeight = article.offsetHeight;
                const windowHeight = window.innerHeight;
                const scrollTop = window.pageYOffset;

                const progress = Math.min(
                    Math.max((scrollTop - articleTop + windowHeight) / articleHeight * 100, 0),
                    100
                );

                if (progressElement) {
                    progressElement.textContent = Math.round(progress) + '%';
                }
            }

            window.addEventListener('scroll', updateReadingProgress);
            updateReadingProgress();

            // Generate Table of Contents
            const tocContainer = document.getElementById('table-of-contents');
            if (tocContainer) {
                const headings = article.querySelectorAll('h1, h2, h3, h4, h5, h6');

                if (headings.length > 0) {
                    headings.forEach((heading, index) => {
                        heading.id = `heading-${index}`;

                        const tocItem = document.createElement('a');
                        tocItem.href = `#heading-${index}`;
                        tocItem.className =
                            'block py-1 text-dark-digikom/70 hover:text-primary transition-colors';
                        tocItem.textContent = heading.textContent;
                        tocItem.style.paddingLeft = `${(parseInt(heading.tagName.slice(1)) - 1) * 12}px`;

                        tocContainer.appendChild(tocItem);
                    });
                } else {
                    tocContainer.innerHTML = '<p class="text-dark-digikom/50 text-sm">Tidak ada daftar isi</p>';
                }
            }
        });

        // Copy to clipboard function
        function copyToClipboard() {
            navigator.clipboard.writeText(window.location.href).then(function() {
                showNotification('Link artikel berhasil disalin!');
            }).catch(function() {
                // Fallback for older browsers
                const textArea = document.createElement("textarea");
                textArea.value = window.location.href;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                showNotification('Link artikel berhasil disalin!');
            });
        }

        // Show notification
        function showNotification(message) {
            const notification = document.createElement('div');
            notification.className =
                'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform duration-300';
            notification.textContent = message;
            document.body.appendChild(notification);

            // Animate in
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 100);

            // Remove after 3 seconds
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 3000);
        }
    </script>

    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .prose {
            color: inherit;
        }

        .prose h1,
        .prose h2,
        .prose h3,
        .prose h4,
        .prose h5,
        .prose h6 {
            color: #1f2937;
            font-weight: 700;
            margin-top: 2rem;
            margin-bottom: 1rem;
        }

        .prose p {
            margin-bottom: 1.5rem;
            line-height: 1.8;
        }

        .prose img {
            border-radius: 0.75rem;
            margin: 2rem 0;
        }

        .prose blockquote {
            border-left: 4px solid #10b981;
            padding-left: 1rem;
            margin: 2rem 0;
            font-style: italic;
            background: #f0f9ff;
            padding: 1rem;
            border-radius: 0.5rem;
        }

        .prose code {
            background: #f3f4f6;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.875em;
        }

        .prose pre {
            background: #1f2937;
            color: #f9fafb;
            padding: 1rem;
            border-radius: 0.5rem;
            overflow-x: auto;
            margin: 1.5rem 0;
        }

        .prose ul,
        .prose ol {
            margin: 1.5rem 0;
            padding-left: 1.5rem;
        }

        .prose li {
            margin: 0.5rem 0;
        }
    </style>
</x-app-layout>
