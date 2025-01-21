<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  @vite('resources/css/app.css')
  <script src="{{ asset('js/app.js') }} defer"></script>
</head>
<body class="h-[2000px] bg-black">
  <x-navbar></x-navbar>

  {{ $slot }}

  <script src="{{ asset('js/app.js') }} defer"></script>
</body>
</html>