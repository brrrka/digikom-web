<x-app-layout>
    <div class="min-h-screen flex flex-col px-4 md:px-48">
        <div class="flex justify-center mt-32 relative">
            <a class="absolute left-0 flex-none text-black" href="javascript:history.back()">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
            </a>
            <h1 class="text-2xl font-bold self-center">Pinjam barang</h1>
        </div>

        <form action="{{ route('peminjaman.store') }}" method="POST" class="mt-12 relative">
            @csrf
            <input type="hidden" name="tanggal_peminjaman" value="{{ $tanggal_peminjaman }}">
            <input type="hidden" name="tanggal_selesai" value="{{ $tanggal_selesai }}">
            <input type="hidden" name="alasan" value="{{ $alasan }}">

            <h2 class="absolute font-semibold text-md -top-3 md:left-[274px] left-4 bg-white px-2">Detail peminjaman
            </h2>

            @foreach ($selectedItems as $item)
                <input type="hidden" name="id_inventaris[]" value="{{ $item->id }}">
                <input type="hidden" name="kuantitas[{{ $item->id }}]" value="{{ $quantities[$item->id] }}">
            @endforeach

            <div class="mx-auto max-w-2xl">
                <div class="bg-white rounded-2xl p-12 border-2 border-black shadow-sm">


                    <div class="flex flex-row justify-between mb-8">
                        <div>
                            <span class="text-md font-semibold">Tanggal pinjam:</span>
                            <span>{{ \Carbon\Carbon::parse($tanggal_peminjaman)->format('d/m/Y') }}</span>
                        </div>
                        <div>
                            <span class="text-md font-semibold">Tanggal selesai:</span>
                            <span>{{ \Carbon\Carbon::parse($tanggal_selesai)->format('d/m/Y') }}</span>
                        </div>
                    </div>

                    <div class="bg-gray-100 rounded-lg p-4 my-4">
                        @php $totalItems = 0; @endphp
                        @foreach ($selectedItems as $item)
                            @if (isset($quantities[$item->id]) && $quantities[$item->id] > 0)
                                @php $totalItems++; @endphp
                                <div class="flex justify-between py-1">
                                    <div>{{ $item->nama }}</div>
                                    <div>{{ $quantities[$item->id] }}</div>
                                </div>
                            @endif
                        @endforeach

                        @if ($totalItems == 0)
                            <div class="text-center text-red-500">Tidak ada barang yang dipilih</div>
                        @endif
                    </div>

                    <div class="mt-4">
                        <span class="text-sm font-semibold">Alasan pinjam:</span>
                        <span class="text-sm">{{ $alasan }}</span>
                    </div>
                </div>

                <div class="mt-8 mb-12">
                    <button type="submit" class="bg-dark-digikom text-white w-full rounded-3xl py-3">
                        Pinjam
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
