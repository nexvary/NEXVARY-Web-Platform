@php
    $requestedLocale = app()->getLocale();
    $publicLocale = in_array($requestedLocale, ['ar', 'en'], true) ? $requestedLocale : 'en';
    $isRtl = $publicLocale === 'ar';
    $pageTitle = trim($__env->yieldContent('title'));
    $pageDescription = trim($__env->yieldContent('description'));
    $baseCanonical = trim($__env->yieldContent('canonical'));
    $pageCanonical = $publicLocale === 'ar'
        ? $baseCanonical.(str_contains($baseCanonical, '?') ? '&' : '?').'lang=ar'
        : $baseCanonical;
    $organizationSchema = [
        '@context' => 'https://schema.org',
        '@graph' => [
            [
                '@type' => 'Organization',
                '@id' => 'https://nexvary.com/#organization',
                'name' => 'NEXVARY',
                'url' => 'https://nexvary.com/',
                'email' => 'info@nexvary.com',
                'sameAs' => [
                    'https://www.facebook.com/share/14p9krEn5ij/',
                    'https://www.youtube.com/@NexvaryInc',
                    'https://x.com/Nexvary',
                ],
            ],
            [
                '@type' => 'WebSite',
                '@id' => 'https://nexvary.com/#website',
                'url' => 'https://nexvary.com/',
                'name' => 'NEXVARY',
                'publisher' => ['@id' => 'https://nexvary.com/#organization'],
                'inLanguage' => ['en', 'ar'],
            ],
        ],
    ];
@endphp
<!doctype html>
<html lang="{{ $publicLocale }}" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#020817">
    <meta name="color-scheme" content="dark">
    <meta name="robots" content="index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1">
    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $pageDescription }}">
    <link rel="canonical" href="{{ $pageCanonical }}">
    <link rel="alternate" hreflang="en" href="{{ $baseCanonical }}">
    <link rel="alternate" hreflang="ar" href="{{ $baseCanonical }}{{ str_contains($baseCanonical, '?') ? '&' : '?' }}lang=ar">
    <link rel="alternate" hreflang="x-default" href="{{ $baseCanonical }}">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="NEXVARY">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $pageDescription }}">
    <meta property="og:url" content="{{ $pageCanonical }}">
    <meta property="og:locale" content="{{ $isRtl ? 'ar_AR' : 'en_US' }}">
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $pageDescription }}">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    @vite(['resources/css/public.css', 'resources/css/command-center-v4.css', 'resources/css/command-center-v6.css', 'resources/js/public.ts', 'resources/js/home-command-v4.ts', 'resources/js/home-command-v6.ts'])
    <script type="application/ld+json" nonce="{{ Vite::cspNonce() }}">{!! json_encode($organizationSchema, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT) !!}</script>
    @stack('head')
</head>
<body class="antialiased">
<div class="nx-page">
    <header class="nx-topbar" data-testid="site-header">
        <a class="nx-brand" href="/" aria-label="NEXVARY home"><span class="nx-brand-mark" aria-hidden="true">N</span><span>NEXVARY</span></a>
        <nav class="nx-nav" aria-label="{{ $isRtl ? 'التنقل الرئيسي' : 'Primary navigation' }}">
            <a href="/">{{ $isRtl ? 'الرئيسية' : 'Home' }}</a>
            <a href="/services">{{ $isRtl ? 'الخدمات' : 'Services' }}</a>
            <a href="/our-work">{{ $isRtl ? 'أعمالنا' : 'Our Work' }}</a>
            <a href="/apps">{{ $isRtl ? 'التطبيقات' : 'Apps' }}</a>
            <a href="/safescan">SafeScan</a>
            <a href="/about">{{ $isRtl ? 'عنّا' : 'About' }}</a>
            <a href="/contact">{{ $isRtl ? 'تواصل معنا' : 'Contact' }}</a>
        </nav>
        <label class="nx-language"><span class="sr-only">Language</span><select class="nx-locale-select" data-language-switch aria-label="Language">
            <option value="en" @selected($publicLocale === 'en')>EN</option>
            <option value="ar" @selected($publicLocale === 'ar')>AR</option>
        </select></label>
    </header>

    @unless(request()->routeIs('home'))
        <div class="nx-back-row"><button type="button" class="nx-back-button" data-testid="back-button" data-back-button aria-label="{{ $isRtl ? 'رجوع' : 'Back' }}"><span aria-hidden="true">{{ $isRtl ? '→' : '←' }}</span><span>{{ $isRtl ? 'رجوع' : 'Back' }}</span></button></div>
    @endunless

    @yield('content')

    <footer class="nx-footer">
        <div><strong>NEXVARY</strong><span>{{ $isRtl ? 'الأمن أبعد مما تراه.' : 'Security beyond the visible.' }}</span></div>
        <div class="nx-footer-links" aria-label="Official NEXVARY links">
            <a href="https://nexvary.com/" aria-label="Website"><span>Website</span></a>
            <a href="https://www.facebook.com/share/14p9krEn5ij/" rel="noreferrer" target="_blank" aria-label="Facebook"><span>Facebook</span></a>
            <a href="mailto:info@nexvary.com" aria-label="Email"><span>Email</span></a>
            <a href="https://www.youtube.com/@NexvaryInc" rel="noreferrer" target="_blank" aria-label="YouTube"><span>YouTube</span></a>
            <a href="https://x.com/Nexvary" rel="noreferrer" target="_blank" aria-label="X"><span>X</span></a>
        </div>
    </footer>
</div>
</body>
</html>
