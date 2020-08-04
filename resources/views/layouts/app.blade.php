<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>Planner</title>

  <script src="{{ asset('js/app.js') }}" defer></script>

  <link rel="dns-prefetch" href="//fonts.gstatic.com">

  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body>
  <main id="app" class="container-md">
    @yield('content')
  </main>
</body>

</html>
