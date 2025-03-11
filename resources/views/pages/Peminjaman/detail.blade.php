<x-app-layout>
    <div class="min-h-screen h-full flex flex-col overflow-hidden px-4 md:px-48">
        <div class="flex justify-center mt-32 relative">
            <a class="absolute left-0 flex-none text-black" href="{{ route('peminjaman.status') }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
            </a>
            <h1 class="text-2xl font-bold self-center">Detail Peminjaman</h1>
        </div>

        <div class="mt-12 relative">
            <div class="mx-auto max-w-2xl">
                <div class="bg-white rounded-2xl p-12 border-2 border-black shadow-sm">
                    <div class="mb-6">
                        <span class="text-lg font-bold text-green-digikom">ID Peminjaman:</span>
                        <span class="text-lg">P00{{ $peminjaman->id }}</span>
                    </div>

                    <div class="flex flex-row justify-between mb-8">
                        <div>
                            <span class="text-md font-semibold">Tanggal pinjam:</span>
                            <span>{{ \Carbon\Carbon::parse($peminjaman->tanggal_peminjaman)->format('d/m/Y') }}</span>
                        </div>
                        <div>
                            <span class="text-md font-semibold">Tanggal selesai:</span>
                            <span>{{ \Carbon\Carbon::parse($peminjaman->tanggal_selesai)->format('d/m/Y') }}</span>
                        </div>
                    </div>

                    <div class="mb-6">
                        <span class="text-md font-semibold">Status:</span>
                        <span
                            class="px-3 py-1 ml-2 rounded-full
                            {{ $peminjaman->status === 'disetujui'
                                ? 'bg-[#D7EEB9] text-green-800'
                                : ($peminjaman->status === 'ditolak'
                                    ? 'bg-red-400 text-white'
                                    : 'bg-[#F1E0DF] text-[#B16D67]') }}">
                            {{ ucfirst($peminjaman->status) }}
                        </span>
                    </div>

                    <div class="bg-gray-200 rounded-lg p-4 my-4">
                        <div class="flex justify-between py-2">
                            <div>{{ $peminjaman->inventaris->nama }}</div>
                            <div>{{ $peminjaman->kuantitas }}</div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <span class="text-md font-semibold">Alasan pinjam:</span>
                        <p class="mt-2 p-3 bg-gray-100 rounded-lg">{{ $peminjaman->alasan }}</p>
                    </div>

                    @if ($peminjaman->catatan)
                        <div class="mt-6">
                            <span class="text-md font-semibold">Catatan Admin:</span>
                            <p class="mt-2 p-3 bg-gray-100 rounded-lg">{{ $peminjaman->catatan }}</p>
                        </div>
                    @endif
                </div>

                @if ($peminjaman->status === 'disetujui')
                    <div class="mt-8 mb-12">
                        <a href="{{ route('peminjaman.download', $peminjaman->id) }}"
                            class="flex justify-center items-center bg-green-digikom text-white w-full rounded-3xl py-3 hover:bg-[#4B2E2C] transition duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 9.75v6.75m0 0l-3-3m3 3l3-3m-8.25 6a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75z" />
                            </svg>
                            Unduh Bukti Peminjaman
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
