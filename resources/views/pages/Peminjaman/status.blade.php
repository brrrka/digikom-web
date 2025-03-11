<x-app-layout>
    <div
        class="min-h-screen h-full flex flex-col bg-gradient-to-b from-white via-white to-[#F8D0D0] overflow-hidden px-4 md:px-48">
        <div class="flex justify-center mt-32 relative">
            <a class="absolute left-0 flex-none text-black" href="{{ route('peminjaman.form') }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
            </a>
            <h1 class="text-2xl font-bold self-center">Pinjam barang</h1>
        </div>

        <!-- Table Header -->
        <div class="grid grid-cols-12 gap-4 mt-16">
            <div class="col-span-1 px-4 py-3 bg-[#B16D67] text-white text-center rounded-tl-2xl">No</div>
            <div class="col-span-2 px-4 py-3 bg-[#B16D67] text-white text-center">ID Pinjaman</div>
            <div class="col-span-2 px-4 py-3 bg-[#B16D67] text-white text-center">Tanggal Pinjam</div>
            <div class="col-span-3 px-4 py-3 bg-[#B16D67] text-white text-center">Tanggal Pengembalian</div>
            <div class="col-span-2 px-4 py-3 bg-[#B16D67] text-white text-center">Status</div>
            <div
                class="col-span-2 px-4 py-3 bg-gradient-to-r from-[#B16D67] to-[#4B2E2C] text-white text-center rounded-tr-2xl">
                Aksi</div>
        </div>

        <!-- Table Body -->
        <div class="grid grid-cols-12 gap-4 mt-4">
            @forelse ($peminjaman as $index => $pinjam)
                @php
                    $rowColor = match ($pinjam->status) {
                        'disetujui' => 'bg-[#D7EEB9]',
                        'ditolak' => 'bg-red-400',
                        default => 'bg-[#F1E0DF]',
                    };
                @endphp

                <div class="col-span-1 px-4 py-3 {{ $rowColor }} text-center">{{ $index + 1 }}</div>
                <div class="col-span-2 px-4 py-3 {{ $rowColor }} text-center">P00{{ $pinjam->id }}</div>
                <div class="col-span-2 px-4 py-3 {{ $rowColor }} text-center">
                    {{ \Carbon\Carbon::parse($pinjam->tanggal_peminjaman)->format('d/m/Y') }}
                </div>
                <div class="col-span-3 px-4 py-3 {{ $rowColor }} text-center">
                    {{ \Carbon\Carbon::parse($pinjam->tanggal_selesai)->format('d/m/Y') }}
                </div>
                <div class="col-span-2 px-4 py-3 {{ $rowColor }} text-center">
                    <span class="px-2 py-1 italic">
                        {{ ucfirst($pinjam->status) }}
                    </span>
                </div>
                <div class="col-span-2 px-4 py-3 {{ $rowColor }} text-center">
                    <div class="flex justify-center space-x-4">
                        <a href="{{ route('peminjaman.show', $pinjam->id) }}"
                            class="text-gray-600 hover:text-gray-900">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                class="size-6">
                                <path fill-rule="evenodd"
                                    d="M5.625 1.5c-1.036 0-1.875.84-1.875 1.875v17.25c0 1.035.84 1.875 1.875 1.875h12.75c1.035 0 1.875-.84 1.875-1.875V12.75A3.75 3.75 0 0 0 16.5 9h-1.875a1.875 1.875 0 0 1-1.875-1.875V5.25A3.75 3.75 0 0 0 9 1.5H5.625ZM7.5 15a.75.75 0 0 1 .75-.75h7.5a.75.75 0 0 1 0 1.5h-7.5A.75.75 0 0 1 7.5 15Zm.75 2.25a.75.75 0 0 0 0 1.5H12a.75.75 0 0 0 0-1.5H8.25Z"
                                    clip-rule="evenodd" />
                                <path
                                    d="M12.971 1.816A5.23 5.23 0 0 1 14.25 5.25v1.875c0 .207.168.375.375.375H16.5a5.23 5.23 0 0 1 3.434 1.279 9.768 9.768 0 0 0-6.963-6.963Z" />
                            </svg>

                        </a>
                        @if ($pinjam->status === 'disetujui')
                            <a href="{{ route('peminjaman.download', $pinjam->id) }}"
                                class="text-gray-600 hover:text-gray-900">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                    class="size-6">
                                    <path fill-rule="evenodd"
                                        d="M12 2.25a.75.75 0 0 1 .75.75v11.69l3.22-3.22a.75.75 0 1 1 1.06 1.06l-4.5 4.5a.75.75 0 0 1-1.06 0l-4.5-4.5a.75.75 0 1 1 1.06-1.06l3.22 3.22V3a.75.75 0 0 1 .75-.75Zm-9 13.5a.75.75 0 0 1 .75.75v2.25a1.5 1.5 0 0 0 1.5 1.5h13.5a1.5 1.5 0 0 0 1.5-1.5V16.5a.75.75 0 0 1 1.5 0v2.25a3 3 0 0 1-3 3H5.25a3 3 0 0 1-3-3V16.5a.75.75 0 0 1 .75-.75Z"
                                        clip-rule="evenodd" />
                                </svg>


                            </a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-12 py-6 px-4 text-center text-gray-500 bg-white rounded-b-2xl">
                    Belum ada riwayat peminjaman.
                </div>
            @endforelse
            <div class="col-span-12 mt-4 mb-8">
                <div class="flex justify-center items-center">
                    @if ($peminjaman->onFirstPage())
                        <span class="px-3 py-1 rounded-l-lg bg-[#F1E0DF] text-[#B16D67] opacity-50 cursor-not-allowed">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                            </svg>
                        </span>
                    @else
                        <a href="{{ $peminjaman->previousPageUrl() }}"
                            class="px-3 py-1 rounded-l-lg bg-[#F1E0DF] text-[#B16D67] hover:bg-[#B16D67] hover:text-white transition duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                            </svg>
                        </a>
                    @endif

                    @foreach ($peminjaman->getUrlRange(1, $peminjaman->lastPage()) as $page => $url)
                        <a href="{{ $url }}"
                            class="px-4 py-1 {{ $page == $peminjaman->currentPage() ? 'bg-[#B16D67] text-white' : 'bg-[#F1E0DF] text-[#B16D67] hover:bg-[#B16D67] hover:text-white' }} transition duration-300">
                            {{ $page }}
                        </a>
                    @endforeach

                    @if ($peminjaman->hasMorePages())
                        <a href="{{ $peminjaman->nextPageUrl() }}"
                            class="px-3 py-1 rounded-r-lg bg-[#F1E0DF] text-[#B16D67] hover:bg-[#B16D67] hover:text-white transition duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                            </svg>
                        </a>
                    @else
                        <span class="px-3 py-1 rounded-r-lg bg-[#F1E0DF] text-[#B16D67] opacity-50 cursor-not-allowed">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                            </svg>
                        </span>
                    @endif
                </div>
                <div class="text-center text-sm text-[#B16D67] mt-2">
                    Halaman {{ $peminjaman->currentPage() }} dari {{ $peminjaman->lastPage() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
