@php
    $publicLocale = app()->getLocale() === 'ar' ? 'ar' : 'en';
    $ar = $isRtl = $publicLocale === 'ar';
    $pageTitle = html_entity_decode(trim($__env->yieldContent('title')), ENT_QUOTES, 'UTF-8');
    $pageDescription = html_entity_decode(trim($__env->yieldContent('description')), ENT_QUOTES, 'UTF-8');
    $baseCanonical = trim($__env->yieldContent('canonical'));
    $pageCanonical = $baseCanonical.($ar ? '?lang=ar' : '');
    $nav = [
        ['/', 'Home', 'الرئيسية'], ['/about', 'About', 'عنّا'], ['/our-work', 'Our Work', 'أعمالنا'],
        ['/artificial-intelligence', 'AI', 'الذكاء الاصطناعي'], ['/cybersecurity', 'Cybersecurity', 'الأمن الرقمي'],
        ['/research', 'Research', 'الأبحاث'], ['/contact', 'Contact', 'تواصل معنا'],
    ];
    $organizationSchema = ['@context'=>'https://schema.org', '@graph'=>[
        ['@type'=>'Organization','@id'=>'https://nexvary.com/#organization','name'=>'Nexvary Inc','legalName'=>'Nexvary Inc','url'=>'https://nexvary.com/','email'=>'info@nexvary.com','foundingDate'=>'2026-10-28','sameAs'=>['https://github.com/nexvary','https://www.youtube.com/@NexvaryInc','https://x.com/Nexvary']],
        ['@type'=>'WebSite','@id'=>'https://nexvary.com/#website','name'=>'Nexvary Inc','url'=>'https://nexvary.com/','publisher'=>['@id'=>'https://nexvary.com/#organization'],'inLanguage'=>['en','ar']],
    ]];
@endphp
<!doctype html>
<html lang="{{ $publicLocale }}" dir="{{ $ar ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#e9ddc8">
    <meta name="color-scheme" content="light">
    <meta name="robots" content="index,follow,max-image-preview:large">
    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $pageDescription }}">
    <link rel="canonical" href="{{ $pageCanonical }}">
    <link rel="alternate" hreflang="en" href="{{ $baseCanonical }}">
    <link rel="alternate" hreflang="ar" href="{{ $baseCanonical }}?lang=ar">
    <link rel="alternate" hreflang="x-default" href="{{ $baseCanonical }}">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Nexvary Inc">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $pageDescription }}">
    <meta property="og:url" content="{{ $pageCanonical }}">
    <meta property="og:locale" content="{{ $ar ? 'ar_AR' : 'en_US' }}">
    <meta property="og:image" content="https://nexvary.com/assets/corporate/nexvary-seal.webp">
    <meta property="og:image:alt" content="{{ $ar ? 'الختم الفني لشركة نيكسفاري' : 'Nexvary corporate seal artwork' }}">
    <meta name="twitter:card" content="summary_large_image">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    @vite(['resources/css/public.css', 'resources/js/public.ts'])
    <script type="application/ld+json" nonce="{{ Vite::cspNonce() }}">{!! json_encode($organizationSchema, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT) !!}</script>
    @stack('head')
</head>
<body>
<a class="nx-skip" href="#main-content">{{ $ar ? 'انتقل إلى المحتوى' : 'Skip to content' }}</a>
<div class="nx-wall">
    <header class="nx-topbar" data-testid="site-header">
        <a class="nx-brand" href="/" aria-label="Nexvary Inc home"><svg viewBox="0 0 48 48" aria-hidden="true"><path d="M7 10h9l16 23V10h9v28h-9L16 15v23H7z"/><path d="M3 3h42v42H3z"/></svg><span>NEXVARY <small>INCORPORATED · WYOMING, USA</small></span></a>
        <button class="nx-menu-toggle" type="button" data-menu-toggle aria-expanded="false" aria-controls="primary-nav">{{ $ar ? 'القائمة' : 'Menu' }} <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16"/></svg></button>
        <nav id="primary-nav" class="nx-nav" aria-label="{{ $ar ? 'التنقل الرئيسي' : 'Primary navigation' }}">
            @foreach($nav as [$url, $en, $arabic])<a href="{{ $url }}" @if(request()->path() === ltrim($url, '/') || ($url === '/' && request()->path() === '/')) aria-current="page" @endif>{{ $ar ? $arabic : $en }}</a>@endforeach
        </nav>
        <label class="nx-language"><span class="sr-only">{{ $ar ? 'اللغة' : 'Language' }}</span><select data-language-switch class="nx-locale-select" aria-label="Language"><option value="en" @selected(!$ar)>EN</option><option value="ar" @selected($ar)>AR</option></select></label>
    </header>
    @unless(request()->routeIs('home'))<div class="nx-back-row"><button class="nx-back-button" type="button" data-testid="back-button" data-back-button><span aria-hidden="true">{{ $ar ? '→' : '←' }}</span> {{ $ar ? 'رجوع' : 'Back' }}</button><span>NEXVARY INC / {{ $ar ? 'البرمجيات والأبحاث' : 'SOFTWARE & RESEARCH' }}</span></div>@endunless
    @yield('content')
    <footer class="nx-footer">
        <div class="nx-footer-identity"><strong>NEXVARY INC</strong><p>{{ $ar ? 'ذكاء نصنعه. وثقة نبنيها.' : 'Intelligence, engineered. Trust, built in.' }}</p><small>{{ $ar ? 'شركة مسجلة في وايومنغ، الولايات المتحدة · ٢٨ أكتوبر ٢٠٢٦' : 'Incorporated in Wyoming, USA · October 28, 2026' }}</small></div>
        <div class="nx-footer-links" aria-label="Official NEXVARY links"><a href="https://nexvary.com/">Website</a><a href="https://github.com/nexvary" target="_blank" rel="noopener noreferrer">GitHub</a><a href="https://www.facebook.com/share/14p9krEn5ij/" target="_blank" rel="noopener noreferrer">Facebook</a><a href="mailto:info@nexvary.com">Email</a><a href="https://www.youtube.com/@NexvaryInc" target="_blank" rel="noopener noreferrer">YouTube</a><a href="https://x.com/Nexvary" target="_blank" rel="noopener noreferrer">X</a><a href="/privacy">{{ $ar ? 'الخصوصية' : 'Privacy' }}</a><a href="/terms">{{ $ar ? 'الشروط' : 'Terms' }}</a><a href="/apps">{{ $ar ? 'التطبيقات' : 'Apps' }}</a><a href="/safescan">SafeScan</a><a href="/services">{{ $ar ? 'مجالات العمل' : 'Expertise' }}</a></div>
        <div class="nx-copyright">© {{ date('Y') }} Nexvary Inc. <span>{{ $ar ? 'البرمجيات · الذكاء الاصطناعي · الأمن الرقمي' : 'SOFTWARE · INTELLIGENCE · DIGITAL SECURITY' }}</span></div>
    </footer>
</div>
</body>
</html>
