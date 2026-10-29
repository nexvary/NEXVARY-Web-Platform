<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#020817">
    <meta name="color-scheme" content="dark">
    <link rel="icon" href="/favicon.ico" sizes="16x16 32x32 48x48">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">
    <title inertia>{{ config('app.name', 'NEXVARY') }}</title>
    @viteReactRefresh
    @vite(['resources/css/app.css', 'resources/css/corporate.css', 'resources/js/app.tsx'])
    @inertiaHead
</head>
<body class="antialiased">
    @inertia
</body>
</html>
