<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $title ?? 'Digikom Lab' }}</title>
  @vite('resources/css/app.css')
</head>
<body class="bg-white">
  <x-navbar></x-navbar>

  {{ $slot }}


  <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>