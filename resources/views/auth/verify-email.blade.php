<x-app-layout>
    <div class="max-w-xl min-h-screen mx-auto text-center flex flex-col justify-center align-center items-center">
        <h2 class="text-2xl font-bold">Verifikasi Email Anda</h2>
        <p class="mt-4 text-gray-600">Kami telah mengirimkan email verifikasi ke alamat yang Anda daftarkan. Jika belum
            menerima, klik tombol di bawah.</p>

        @if (session('status') == 'verification-link-sent')
            <p class="mt-2 text-green-600">Email verifikasi baru telah dikirim!</p>
        @endif

        <form method="POST" action="{{ route('verification.send') }}" class="mt-6">
            @csrf
            <button type="submit" class="px-4 py-2 bg-primary text-white rounded">
                Kirim Ulang Email Verifikasi
            </button>
        </form>
    </div>
</x-app-layout>
