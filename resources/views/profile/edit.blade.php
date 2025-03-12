<x-app-layout>
    <!-- Decorative images with responsive adjustments -->
    <div class="absolute right-24 top-40 z-10 md:block hidden">
        <img src="{{ asset('images/ArrangingFiles.png') }}" class="w-48" />
    </div>
    <div class="absolute right-0 top-0 md:block hidden">
        <img src="{{ asset('images/Ellipse14.png') }}" class="w-48" />
    </div>
    <div class="absolute left-0 top-96 md:block hidden">
        <img src="{{ asset('images/Ellipse15.png') }}" class="w-48" />
    </div>
    <div class="absolute left-16 top-[480px] md:block hidden">
        <img src="{{ asset('images/SignUpForm.png') }}" class="w-64" />
    </div>

    <div class="min-h-screen bg-gradient-to-b from-white via-white to-[#eef8e2] flex flex-col items-center">
        <h1 class="mt-20 md:mt-32 text-xl md:text-2xl font-bold">Akun Saya</h1>
        <div class="mt-6 md:mt-8 w-full md:w-2/3 lg:w-1/2 xl:w-1/3 px-4 md:px-0">
            <div class="w-full mx-auto sm:px-6 lg:px-8 space-y-6">
                <!-- Card Pertama (Profile Update) -->
                <div class="p-4 sm:p-8 bg-[#F1FAE4] shadow sm:rounded-lg">
                    <div class="max-w-xl mx-auto">
                        <section>
                            <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                                @csrf
                            </form>

                            <form method="post" action="{{ route('profile.update') }}"
                                class="flex flex-col justify-center items-center gap-6 md:gap-8">
                                @csrf
                                @method('patch')

                                <!-- Custom Input Field - Nama -->
                                <div class="w-full relative">
                                    <label for="name"
                                        class="absolute bg-[#F1FAE4] bottom-9 left-5 text-sm font-bold text-dark-green-2">Nama</label>
                                    <input id="name" name="name" type="text"
                                        class="mt-1 block w-full rounded-xl border-dark-green-2 border-2 bg-[#F1FAE4] text-sm py-3 text-dark-green-2"
                                        value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
                                    @error('name')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="w-full relative opacity-50">
                                    <label for="nim"
                                        class="absolute bg-[#F1FAE4] bottom-9 left-5 text-sm font-bold text-dark-green-2">NIM</label>
                                    <input id="nim" name="nim" type="text"
                                        class="mt-1 block w-full rounded-xl border-dark-green-2 border-2 bg-[#F1FAE4] text-sm py-3 text-dark-green-2"
                                        value="{{ old('nim', $user->nim) }}" required autofocus autocomplete="nim"
                                        disabled />
                                    @error('nim')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="w-full relative opacity-50">
                                    <label for="email"
                                        class="absolute bg-[#F1FAE4] bottom-9 left-5 text-sm font-bold text-dark-green-2">Email</label>
                                    <input id="email" name="email" type="email"
                                        class="mt-1 block w-full rounded-xl border-dark-green-2 border-2 bg-[#F1FAE4] text-sm py-3 text-dark-green-2"
                                        value="{{ old('email', $user->email) }}" required autocomplete="username"
                                        disabled />
                                    @error('email')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror

                                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                                        <div class="mt-2">
                                            <p class="text-sm text-dark-green-2">
                                                {{ __('Your email address is unverified.') }}

                                                <button form="send-verification"
                                                    class="underline text-sm text-dark-green-2 hover:text-dark-green-3 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                                    {{ __('Click here to re-send the verification email.') }}
                                                </button>
                                            </p>

                                            @if (session('status') === 'verification-link-sent')
                                                <p class="mt-2 font-medium text-sm text-green-600">
                                                    {{ __('A new verification link has been sent to your email address.') }}
                                                </p>
                                            @endif
                                        </div>
                                    @endif
                                </div>

                                <!-- Button Save -->
                                <div class="w-full">
                                    <button type="submit"
                                        class="px-4 py-2 bg-dark-green-2 text-white rounded-xl hover:bg-dark-green-3 focus:outline-none focus:ring-2 focus:ring-green-500 w-full">
                                        {{ __('Save') }}
                                    </button>

                                    @if (session('status') === 'profile-updated')
                                        <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                                            class="text-sm text-green-600">{{ __('Saved.') }}</p>
                                    @endif
                                </div>
                            </form>
                        </section>
                    </div>
                </div>

                <!-- Card Kedua (Update Password) -->
                <div class="p-4 sm:p-8 bg-[#F1FAE4] shadow sm:rounded-lg">
                    <div class="max-w-xl mx-auto">
                        <section>
                            <header>
                                <h2 class="text-lg font-medium text-dark-green-2">
                                    {{ __('Update Password') }}
                                </h2>
                            </header>

                            <form method="post" action="{{ route('password.update') }}"
                                class="mt-6 space-y-4 md:space-y-6">
                                @csrf
                                @method('put')

                                <!-- Custom Input Field - Current Password -->
                                <div class="w-full relative">
                                    <label for="update_password_current_password"
                                        class="absolute bg-[#F1FAE4] bottom-9 left-5 text-sm font-bold text-dark-green-2">Current
                                        Password</label>
                                    <input id="update_password_current_password" name="current_password" type="password"
                                        class="mt-1 block w-full rounded-xl border-dark-green-2 border-2 bg-[#F1FAE4] text-sm py-3 text-dark-green-2"
                                        autocomplete="current-password" />
                                    @error('current_password', 'updatePassword')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Custom Input Field - New Password -->
                                <div class="w-full relative">
                                    <label for="update_password_password"
                                        class="absolute bg-[#F1FAE4] bottom-9 left-5 text-sm font-bold text-dark-green-2">New
                                        Password</label>
                                    <input id="update_password_password" name="password" type="password"
                                        class="mt-1 block w-full rounded-xl border-dark-green-2 border-2 bg-[#F1FAE4] text-sm py-3 text-dark-green-2"
                                        autocomplete="new-password" />
                                    @error('password', 'updatePassword')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Custom Input Field - Confirm Password -->
                                <div class="w-full relative">
                                    <label for="update_password_password_confirmation"
                                        class="absolute bg-[#F1FAE4] bottom-9 left-5 text-sm font-bold text-dark-green-2">Confirm
                                        Password</label>
                                    <input id="update_password_password_confirmation" name="password_confirmation"
                                        type="password"
                                        class="mt-1 block w-full rounded-xl border-dark-green-2 border-2 bg-[#F1FAE4] text-sm py-3 text-dark-green-2"
                                        autocomplete="new-password" />
                                    @error('password_confirmation', 'updatePassword')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Button Save -->
                                <div class="w-full">
                                    <button type="submit"
                                        class="px-4 py-2 bg-dark-green-2 text-white rounded-xl hover:bg-dark-green-3 focus:outline-none focus:ring-2 focus:ring-green-500 w-full">
                                        {{ __('Save') }}
                                    </button>

                                    @if (session('status') === 'password-updated')
                                        <p x-data="{ show: true }" x-show="show" x-transition
                                            x-init="setTimeout(() => show = false, 2000)" class="text-sm text-green-600">
                                            {{ __('Saved.') }}</p>
                                    @endif
                                </div>
                            </form>
                        </section>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 md:mt-12 mb-12 w-full md:w-1/2 lg:w-1/3 xl:w-1/4 px-4 md:px-0">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="px-4 py-3 md:py-4 bg-red-600 text-white rounded-xl hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 w-full">
                    {{ __('Log Out') }}
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
