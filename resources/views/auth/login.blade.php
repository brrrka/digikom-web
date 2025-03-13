<x-app-layout>
    <div
        class="min-h-screen flex items-center justify-center bg-gradient-to-b from-white via-white to-[#eef8e2] relative overflow-hidden">

        <div class="absolute right-0 top-40">
            <img src="{{ asset('images/Group11.png') }}" />
        </div>
        <div class="absolute right-[286px] top-[86px]">
            <img src="{{ asset('images/Rectangle7.png') }}" />
        </div>
        <div class="absolute right-72 top-24">
            <img src="{{ asset('images/Rectangle8.png') }}" />
        </div>
        <div class="absolute left-[350px] top-72">
            <img src="{{ asset('images/Group12.png') }}" />
        </div>
        <div class="absolute left-0 top-40">
            <img src="{{ asset('images/Group2.png') }}" />
        </div>
        <div class="absolute left-20 top-48">
            <img src="{{ asset('images/Ellipse62.png') }}" />
        </div>


        <div class="bg-white py-8 px-24 rounded-3xl shadow-2xl w-full max-w-xl mt-4 relative">
            <h2 class="text-2xl font-semibold text-dark-green-2 mb-0 text-center">Masuk</h2>

            <form method="POST" action="{{ route('login') }}" class="mt-12 space-y-10">
                @csrf
                <div class="relative">
                    <label for="email"
                        class="absolute -top-3 text-sm text-dark-green ml-8 bg-white px-1">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                        class="w-full px-8 py-3 rounded-3xl border-2 border-primary focus:border-primary focus:ring-2 focus:ring-primary transition-colors placeholder-light-green-3"
                        required placeholder="Ketik Email">
                </div>

                <div class="relative">
                    <label for="password"
                        class="absolute -top-3 text-sm text-dark-green ml-8 bg-white px-1">Password</label>
                    <input type="password" id="password" name="password"
                        class="w-full px-8 py-3 rounded-3xl border-2 border-primary focus:border-primary focus:ring-2 focus:ring-primary transition-colors placeholder-light-green-3"
                        required placeholder="Ketik Password">
                </div>

                <button type="submit"
                    class="w-full py-4 px-4 bg-dark-green-2 hover:bg-dark-green-6 text-white rounded-3xl transition-colors">
                    Masuk
                </button>
            </form>
            <div class="text-center text-sm text-dark-green-2 mt-4">
                Belum memiliki akun?
                <a href="{{ route('register') }}" class="text-dark-green-5 hover:underline font-semibold">Daftar</a>
            </div>
        </div>
    </div>
</x-app-layout>
