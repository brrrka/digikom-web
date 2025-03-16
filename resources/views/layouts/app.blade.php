<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('images/LogoDigikom.png') }}">
    <title>{{ $title ?? 'Digikom Lab' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
</head>

<body>
    <x-navbar></x-navbar>

    {{ $slot }}

    <x-footer></x-footer>

    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        // Konfigurasi default Toastr
        toastr.options = {
            closeButton: true,
            progressBar: true,
            positionClass: "toast-bottom-right",
            timeOut: 5000,
            extendedTimeOut: 1000,
            preventDuplicates: true
        };

        // Jalankan toast jika ada flash message
        @if (session()->has('success'))
            toastr.success("{{ session('success') }}");
        @endif

        @if (session()->has('error'))
            toastr.error("{{ session('error') }}");
        @endif

        @if (session()->has('info'))
            toastr.info("{{ session('info') }}");
        @endif

        @if (session()->has('warning'))
            toastr.warning("{{ session('warning') }}");
        @endif
    </script>

    <script>
        // Konfigurasi default Toastr
        toastr.options = {
            closeButton: true,
            progressBar: true,
            positionClass: "toast-bottom-right",
            timeOut: 5000,
            extendedTimeOut: 1000,
            preventDuplicates: true
        };

        // Jalankan toast jika ada flash message
        @if (session()->has('success'))
            toastr.success("{{ session('success') }}");
        @endif

        @if (session()->has('error'))
            toastr.error("{{ session('error') }}");
        @endif

        @if (session()->has('info'))
            toastr.info("{{ session('info') }}");
        @endif

        @if (session()->has('warning'))
            toastr.warning("{{ session('warning') }}");
        @endif

        // Menangani error validasi
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                toastr.error("{{ $error }}");
            @endforeach
        @endif
    </script>

    <script src="{{ asset('js/app.js') }}"></script>
</body>

</html>
