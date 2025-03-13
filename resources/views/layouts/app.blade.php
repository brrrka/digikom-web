<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('images/LogoDigikom.png') }}">
    <title>{{ $title ?? 'Digikom Lab' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>
    <x-navbar></x-navbar>

    {{ $slot }}

    <x-footer></x-footer>

    <!-- Toast Container -->
    <div id="toast-container" class="fixed top-4 right-4 z-50 flex flex-col gap-2"></div>

    <!-- Toast Script -->
    <script>
        function showToast(message, type = 'success', duration = 3000) {
            const container = document.getElementById('toast-container');

            // Define colors based on your tailwind config
            const toastClasses = {
                success: 'bg-primary text-dark-digikom',
                error: 'bg-red-digikom text-white',
                warning: 'bg-light-green-3 text-dark-digikom',
                info: 'bg-light-blue text-dark-digikom'
            };

            const toast = document.createElement('div');
            toast.className =
                `${toastClasses[type]} px-4 py-3 rounded-lg shadow-lg transform transition-all duration-300 translate-x-full max-w-xs`;
            toast.innerHTML = `<div class="flex items-center">
                           <span>${message}</span>
                           <button class="ml-auto text-sm" onclick="this.parentElement.parentElement.remove()">×</button>
                         </div>`;

            container.appendChild(toast);

            // Animate in
            setTimeout(() => {
                toast.classList.remove('translate-x-full');
            }, 10);

            // Remove after duration
            setTimeout(() => {
                toast.classList.add('opacity-0');
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }, duration);
        }
    </script>

    <!-- Flash Message Handling -->
    @if (session('success'))
        <script>
            showToast("{{ session('success') }}", 'success');
        </script>
    @endif

    @if (session('error'))
        <script>
            showToast("{{ session('error') }}", 'error');
        </script>
    @endif

    @if (session('warning'))
        <script>
            showToast("{{ session('warning') }}", 'warning');
        </script>
    @endif

    @if (session('info'))
        <script>
            showToast("{{ session('info') }}", 'info');
        </script>
    @endif

    <script src="{{ asset('js/app.js') }}"></script>
</body>

</html>
