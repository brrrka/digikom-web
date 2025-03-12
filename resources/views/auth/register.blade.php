<x-app-layout>
    <div class="py-20 -mb-20"> <!-- Tambahkan padding atas bawah untuk jarak dari navbar dan footer -->
        <div
            class="min-h-[calc(100vh-160px)] flex items-center justify-center bg-gradient-to-b from-white via-white to-[#ffffec] relative overflow-hidden">

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

            <div class="bg-white py-8 px-24 rounded-3xl shadow-2xl w-full max-w-xl mx-4 relative my-8">
                <h2 class="text-2xl font-semibold text-dark-green-2 mb-2 text-center">Daftar</h2>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif


                <form method="POST" action="{{ route('register') }}" class="space-y-8">
                    @csrf

                    <div class="mb-6 relative">
                        <label for="email"
                            class="absolute -top-3 text-sm text-dark-green ml-8 bg-white px-1">Email</label>
                        <input type="email" id="email" name="email"
                            class="w-full px-8 py-3 rounded-3xl border-2 border-primary focus:border-primary focus:ring-2 focus:ring-primary transition-colors placeholder-[#FBF8B5]"
                            required placeholder="Ketik Email">
                    </div>

                    <div class="mb-6 relative">
                        <label for="username"
                            class="absolute -top-3 text-sm text-dark-green ml-8 bg-white px-1">Username</label>
                        <input type="text" id="name" name="name"
                            class="w-full px-8 py-3 rounded-3xl border-2 border-primary focus:border-primary focus:ring-2 focus:ring-primary transition-colors placeholder-[#FBF8B5]"
                            required placeholder="Ketik Username">
                    </div>

                    <div class="mb-6 relative">
                        <label for="nim"
                            class="absolute -top-3 text-sm text-dark-green ml-8 bg-white px-1">NIM</label>
                        <input type="text" id="nim" name="nim"
                            class="w-full px-8 py-3 rounded-3xl border-2 border-primary focus:border-primary focus:ring-2 focus:ring-primary transition-colors placeholder-[#FBF8B5]"
                            required placeholder="Ketik NIM">
                    </div>


                    <div class="mb-6 relative">
                        <label for="password"
                            class="absolute -top-3 text-sm text-dark-green ml-8 bg-white px-1">Password</label>
                        <input type="password" id="password" name="password"
                            class="w-full px-8 py-3 rounded-3xl border-2 border-primary focus:border-primary focus:ring-2 focus:ring-primary transition-colors placeholder-[#FBF8B5]"
                            required placeholder="Ketik Password">
                    </div>

                    <div class="mb-6 relative">
                        <label for="password_confirmation"
                            class="absolute -top-3 text-sm text-dark-green ml-8 bg-white px-1">Confirm
                            Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            class="w-full px-8 py-3 rounded-3xl border-2 border-primary focus:border-primary focus:ring-2 focus:ring-primary transition-colors placeholder-[#FBF8B5]"
                            required placeholder="Ketik Password">
                    </div>

                    <button type="submit"
                        class="w-full py-4 px-4 bg-dark-green-2 hover:bg-[#5d745a] text-white rounded-3xl transition-colors mt-8">
                        Daftar
                    </button>

                    <div class="text-center text-sm text-dark-green-2 mt-6">
                        Sudah memiliki akun?
                        <a href="{{ route('login') }}" class="text-[#718B6D] hover:underline font-semibold">Masuk</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
