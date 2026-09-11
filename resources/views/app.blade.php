<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#0C1319">
    <meta name="color-scheme" content="dark">
    <meta name="description" content="NEXVARY global security operations platform for cybersecurity, counter-surveillance, digital forensics, privacy, monitoring, analysis and response.">
    <link rel="canonical" href="https://nexvary.com/">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <title inertia>NEXVARY Global Security Operations Center</title>
    @viteReactRefresh
    @vite(['resources/css/app.css', 'resources/js/app.tsx'])
    @inertiaHead
</head>
<body class="antialiased">
    <div style="position:absolute;width:1px;height:1px;padding:0;margin:-1px;overflow:hidden;clip:rect(0,0,0,0);white-space:nowrap;border:0" aria-hidden="true">
        <h1>{{ app()->getLocale() === 'ar' ? 'مركز العمليات الأمنية العالمي NEXVARY' : 'NEXVARY Global Security Operations Center' }}</h1>
    </div>
    <noscript>
        <nav aria-label="Primary">
            <a href="/">{{ app()->getLocale() === 'ar' ? 'الرئيسية' : 'Home' }}</a>
            <a href="/services">{{ app()->getLocale() === 'ar' ? 'الخدمات' : 'Services' }}</a>
            <a href="/our-work">{{ app()->getLocale() === 'ar' ? 'أعمالنا' : 'Our Work' }}</a>
            <a href="/apps">{{ app()->getLocale() === 'ar' ? 'التطبيقات' : 'Apps' }}</a>
            <a href="/safescan">SafeScan</a>
            <a href="/about">{{ app()->getLocale() === 'ar' ? 'عنّا' : 'About' }}</a>
            <a href="/contact">{{ app()->getLocale() === 'ar' ? 'تواصل معنا' : 'Contact' }}</a>
        </nav>
    </noscript>
    @inertia
</body>
</html>
