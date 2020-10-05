<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Amprest Technologies. You dream. We build.">
    <meta name="author" content="Amprest Technologies <dev@amprest.co.ke>">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Amprest Technologies Web Services') }}</title>

    <meta property="og:title" content="{{ config('app.name') }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}/">
    <meta property="og:image" content="{{ asset('img/logo.webp') }}" />
    <meta property="og:description" content="Amprest Technologies API. You Dream. We Build." />

    {{-- Canonical Link. --}}
    <link rel="canonical" href="{{ url('/') }}/">

    {{-- Progressive Web App Manifest --}}
    <link rel="manifest" href="/manifest.json">

    {{-- Favicon --}}
    <link rel="apple-touch-icon-precomposed" sizes="57x57"
      href="{{ asset('img/favicon/apple-touch-icon-57x57.webp')}}" />
    <link rel="apple-touch-icon-precomposed" sizes="114x114"
      href="{{ asset('img/favicon/apple-touch-icon-114x114.webp')}}" />
    <link rel="apple-touch-icon-precomposed" sizes="72x72"
      href="{{ asset('img/favicon/apple-touch-icon-72x72.webp')}}" />
    <link rel="apple-touch-icon-precomposed" sizes="144x144"
      href="{{ asset('img/favicon/apple-touch-icon-144x144.webp')}}" />
    <link rel="apple-touch-icon-precomposed" sizes="60x60"
      href="{{ asset('img/favicon/apple-touch-icon-60x60.webp')}}" />
    <link rel="apple-touch-icon-precomposed" sizes="120x120"
      href="{{ asset('img/favicon/apple-touch-icon-120x120.webp')}}" />
    <link rel="apple-touch-icon-precomposed" sizes="76x76"
      href="{{ asset('img/favicon/apple-touch-icon-76x76.webp')}}" />
    <link rel="apple-touch-icon-precomposed" sizes="152x152"
      href="{{ asset('img/favicon/apple-touch-icon-152x152.webp')}}" />
    <link rel="icon" type="image/webp" href="{{ asset('img/favicon/favicon-196x196.webp')}}" sizes="196x196" />
    <link rel="icon" type="image/webp" href="{{ asset('img/favicon/favicon-96x96.webp')}}" sizes="96x96" />
    <link rel="icon" type="image/webp" href="{{ asset('img/favicon/favicon-32x32.webp')}}" sizes="32x32" />
    <link rel="icon" type="image/webp" href="{{ asset('img/favicon/favicon-16x16.webp')}}" sizes="16x16" />
    <link rel="icon" type="image/webp" href="{{ asset('img/favicon/favicon-128.webp')}}" sizes="128x128" />
    <meta name="application-name" content="&nbsp;" />
    <meta name="msapplication-TileColor" content="#FFFFFF" />
    <meta name="msapplication-TileImage" content="{{ asset('img/favicon/mstile-144x144.webp')}}" />
    <meta name="msapplication-square70x70logo" content="{{ asset('img/favicon/mstile-70x70.webp')}}" />
    <meta name="msapplication-square150x150logo" content="{{ asset('img/favicon/mstile-150x150.webp')}}" />
    <meta name="msapplication-wide310x150logo" content="{{ asset('img/favicon/mstile-310x150.webp')}}" />
    <meta name="msapplication-square310x310logo" content="{{ asset('img/favicon/faviconmstile-310x310.webp')}}" />

    <!-- Styles -->
    <link rel="preload" href="{{ asset('css/app.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
      <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    </noscript>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.0/moment.min.js"></script>
    <script src="{{ mix('js/manifest.js') }}" defer></script>
    <script src="{{ mix('js/vendor.js') }}" defer></script>
    <script src="{{ mix('js/app.js') }}" defer></script>

    {{-- Include Ziggy --}}
    @routes
  </head>

  <body class="font-sans antialiased">
    @inertia
  </body>

</html>