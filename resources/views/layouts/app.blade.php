<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="{{ asset('images/LogoDigikom.png') }}">
  <title>{{ $title ?? 'Digikom Lab' }}</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body>
  <x-navbar></x-navbar>

  {{ $slot }}

  <x-footer></x-footer>
  <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>