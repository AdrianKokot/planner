<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>Planner</title>

  <script src="{{ asset('js/app.js') }}" defer></script>

  <link rel="dns-prefetch" href="//fonts.gstatic.com">

  <link href="{{ asset('css/app.css') }}" rel="stylesheet">

  <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon/apple-touch-icon.png') }}">
  <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon/favicon-32x32.png') }}">
  <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon/favicon-16x16.png') }}">
  <link rel="manifest" href="{{ asset('site.webmanifest') }}">

  <meta name="theme-color" content="#00B580">
  <meta name="msapplication-navbutton-color" content="#00B580">
  <meta name="apple-mobile-web-app-status-bar-style" content="#00B580">
</head>

<body>
  <main id="app" class="container-xl">
    @yield('content')
  </main>
</body>

</html>
