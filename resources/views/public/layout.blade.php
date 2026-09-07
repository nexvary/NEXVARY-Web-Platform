<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#020817">
    <meta name="color-scheme" content="dark">
    <meta name="robots" content="index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1">
    <title>@yield('title')</title>
    <meta name="description" content="@yield('description')">
    <link rel="canonical" href="@yield('canonical')">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="NEXVARY">
    <meta property="og:title" content="@yield('title')">
    <meta property="og:description" content="@yield('description')">
    <meta property="og:url" content="@yield('canonical')">
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="@yield('title')">
    <meta name="twitter:description" content="@yield('description')">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    @vite(['resources/css/public.css', 'resources/js/public.ts'])
    <script type="application/ld+json" nonce="{{ Vite::cspNonce() }}">{!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => 'NEXVARY',
        'url' => 'https://nexvary.com/',
        'email' => 'info@nexvary.com',
        'sameAs' => [
            'https://www.facebook.com/share/14p9krEn5ij/',
            'https://www.youtube.com/@NexvaryInc',
            'https://x.com/Nexvary',
        ],
    ], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) !!}</script>
    @stack('head')
</head>
<body class="antialiased">
<div class="nx-page">
    <header class="nx-topbar" data-testid="site-header">
        <a class="nx-brand" href="/" aria-label="NEXVARY home"><span class="nx-brand-mark" aria-hidden="true">N</span><span>NEXVARY</span></a>
        <nav class="nx-nav" aria-label="{{ app()->getLocale() === 'ar' ? 'التنقل الرئيسي' : 'Primary navigation' }}">
            <a href="/">{{ app()->getLocale() === 'ar' ? 'الرئيسية' : 'Home' }}</a>
            <a href="/services">{{ app()->getLocale() === 'ar' ? 'الخدمات' : 'Services' }}</a>
            <a href="/our-work">{{ app()->getLocale() === 'ar' ? 'أعمالنا' : 'Our Work' }}</a>
            <a href="/apps">{{ app()->getLocale() === 'ar' ? 'التطبيقات' : 'Apps' }}</a>
            <a href="/safescan">SafeScan</a>
            <a href="/about">{{ app()->getLocale() === 'ar' ? 'عنّا' : 'About' }}</a>
            <a href="/contact">{{ app()->getLocale() === 'ar' ? 'تواصل معنا' : 'Contact' }}</a>
        </nav>
        <label class="nx-language"><span class="sr-only">Language</span><select class="nx-locale-select" data-language-switch aria-label="Language">
            @foreach(['en','ar','tr','ru','de','it','es'] as $language)
                <option value="{{ $language }}" @selected(app()->getLocale() === $language)>{{ strtoupper($language) }}</option>
            @endforeach
        </select></label>
    </header>

    @unless(request()->routeIs('home'))
        <div class="nx-back-row"><button type="button" class="nx-back-button" data-testid="back-button" data-back-button aria-label="{{ app()->getLocale() === 'ar' ? 'رجوع' : 'Back' }}"><span aria-hidden="true">{{ app()->getLocale() === 'ar' ? '→' : '←' }}</span><span>{{ app()->getLocale() === 'ar' ? 'رجوع' : 'Back' }}</span></button></div>
    @endunless

    @yield('content')

    <footer class="nx-footer">
        <div><strong>NEXVARY</strong><span>{{ app()->getLocale() === 'ar' ? 'الأمن أبعد مما تراه.' : 'Security beyond the visible.' }}</span></div>
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
