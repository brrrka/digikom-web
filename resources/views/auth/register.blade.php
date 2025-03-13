<x-app-layout>
    <div class="py-20 -mb-20">
        <div
            class="min-h-[calc(100vh-160px)] flex items-center justify-center bg-gradient-to-b from-white via-white to-[#ffffec] relative overflow-hidden">

            <!-- Background images (tetap sama seperti sebelumnya) -->
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

            <!-- Form Registrasi -->
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

                    <!-- Field Email, Username, dan NIM (tetap sama seperti sebelumnya) -->
                    <div class="mb-6 relative">
                        <label for="email"
                            class="absolute -top-3 text-sm text-dark-green ml-8 bg-white px-1">Email</label>
                        <input type="email" id="email" name="email"
                            class="w-full px-8 py-3 rounded-3xl border-2 border-primary focus:border-primary focus:ring-2 focus:ring-primary transition-colors placeholder-[#FBF8B5]"
                            required placeholder="Ketik Email" value="{{ old('email') }}">
                    </div>

                    <div class="mb-6 relative">
                        <label for="username"
                            class="absolute -top-3 text-sm text-dark-green ml-8 bg-white px-1">Username</label>
                        <input type="text" id="name" name="name"
                            class="w-full px-8 py-3 rounded-3xl border-2 border-primary focus:border-primary focus:ring-2 focus:ring-primary transition-colors placeholder-[#FBF8B5]"
                            required placeholder="Ketik Username" value="{{ old('name') }}">
                    </div>

                    <div class="mb-6 relative">
                        <label for="nim"
                            class="absolute -top-3 text-sm text-dark-green ml-8 bg-white px-1">NIM</label>
                        <input type="text" id="nim" name="nim"
                            class="w-full px-8 py-3 rounded-3xl border-2 border-primary focus:border-primary focus:ring-2 focus:ring-primary transition-colors placeholder-[#FBF8B5]"
                            required placeholder="Ketik NIM" value="{{ old('nim') }}">
                    </div>

                    <!-- Field Password dengan Fitur Intip -->
                    <div class="mb-6 relative">
                        <label for="password"
                            class="absolute -top-3 text-sm text-dark-green ml-8 bg-white px-1 z-10">Password</label>
                        <div class="relative">
                            <input type="password" id="password" name="password"
                                class="w-full px-8 py-3 rounded-3xl border-2 border-primary focus:border-primary focus:ring-2 focus:ring-primary transition-colors placeholder-[#FBF8B5]"
                                required placeholder="Ketik Password">
                            <span class="absolute right-4 top-1/2 transform -translate-y-1/2 cursor-pointer"
                                onclick="togglePasswordVisibility('password')">
                                <i class="fas fa-eye bg-gray-100" id="togglePassword" style="color: #718B6D;"></i>
                            </span>
                        </div>
                    </div>

                    <div class="mb-6 relative">
                        <label for="password_confirmation"
                            class="absolute -top-3 text-sm text-dark-green ml-8 bg-white px-1 z-10">Confirm
                            Password</label>
                        <div class="relative">
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                class="w-full px-8 py-3 rounded-3xl border-2 border-primary focus:border-primary focus:ring-2 focus:ring-primary transition-colors placeholder-[#FBF8B5]"
                                required placeholder="Ketik Password">
                            <span class="absolute right-4 top-1/2 transform -translate-y-1/2 cursor-pointer"
                                onclick="togglePasswordVisibility('password_confirmation')">
                                <i class="fas fa-eye" id="togglePasswordConfirmation" style="color: #718B6D;"></i>
                            </span>
                        </div>
                    </div>

                    <!-- Tombol Daftar -->
                    <button type="submit"
                        class="w-full py-4 px-4 bg-dark-green-2 hover:bg-[#5d745a] text-white rounded-3xl transition-colors mt-8">
                        Daftar
                    </button>

                    <!-- Link ke Halaman Login -->
                    <div class="text-center text-sm text-dark-green-2 mt-6">
                        Sudah memiliki akun?
                        <a href="{{ route('login') }}" class="text-[#718B6D] hover:underline font-semibold">Masuk</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Script untuk Toggle Password Visibility -->
    <script>
        function togglePasswordVisibility(fieldId) {
            const passwordField = document.getElementById(fieldId);
            const toggleIcon = document.querySelector(`[onclick="togglePasswordVisibility('${fieldId}')"] i`);

            if (passwordField.type === "password") {
                passwordField.type = "text";
                toggleIcon.classList.remove("fa-eye");
                toggleIcon.classList.add("fa-eye-slash");
            } else {
                passwordField.type = "password";
                toggleIcon.classList.remove("fa-eye-slash");
                toggleIcon.classList.add("fa-eye");
            }
        }
    </script>

    <!-- Tambahkan Font Awesome untuk Ikon Mata -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</x-app-layout>
