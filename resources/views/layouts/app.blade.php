<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Border Buyers')</title>

    <!-- Primary Meta Tags -->
    <meta name="title" content="@yield('meta_title', 'Border Buyers — Secure Cross‑Border Escrow & Monitoring')">
    <meta name="description" content="@yield('meta_description', 'Secure cross-border transactions with escrow, monitoring, and verified agents. Border Buyers helps you buy safely across borders.')">
    <meta name="keywords" content="@yield('meta_keywords', 'escrow, transaction monitoring, cross-border, buyers, agents, marketplace')">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('meta_title', 'Border Buyers — Secure Cross‑Border Escrow & Monitoring')">
    <meta property="og:description" content="@yield('meta_description', 'Secure cross-border transactions with escrow, monitoring, and verified agents. Border Buyers helps you buy safely across borders.')">
    <meta property="og:image" content="{{ asset('favicon.ico') }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="@yield('meta_title', 'Border Buyers — Secure Cross‑Border Escrow & Monitoring')">
    <meta property="twitter:description" content="@yield('meta_description', 'Secure cross-border transactions with escrow, monitoring, and verified agents. Border Buyers helps you buy safely across borders.')">
    <meta property="twitter:image" content="{{ asset('favicon.ico') }}">

    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @stack('head')
</head>
<body class="min-h-screen bg-gradient-to-br from-green-50 to-cyan-50">
    <div id="app" class="min-h-screen flex flex-col">
        @yield('content')
    </div>

    @stack('scripts')
</body>
</html>