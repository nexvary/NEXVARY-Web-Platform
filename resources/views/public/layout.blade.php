@php
    $ar = $isRtl = app()->getLocale() === 'ar';
    $publicLocale = $ar ? 'ar' : 'en';
    $pageTitle = html_entity_decode(trim($__env->yieldContent('title')), ENT_QUOTES, 'UTF-8');
    $pageDescription = html_entity_decode(trim($__env->yieldContent('description')), ENT_QUOTES, 'UTF-8');
    $baseCanonical = trim($__env->yieldContent('canonical'));
    $pageCanonical = $baseCanonical.($ar ? '?lang=ar' : '');
    $nav = [['/', 'Home', 'الرئيسية'], ['/about', 'Company', 'الشركة'], ['/services', 'Technology', 'التقنية'], ['/research', 'Research', 'الأبحاث'], ['/contact', 'Contact', 'تواصل معنا']];
    $organization = ['@context'=>'https://schema.org','@type'=>'Organization','@id'=>'https://nexvary.com/#organization','name'=>'Nexvary Inc','legalName'=>'Nexvary Inc','url'=>'https://nexvary.com/','email'=>'info@nexvary.com','logo'=>'https://nexvary.com/assets/corporate/seal-512.png','sameAs'=>['https://github.com/nexvary']];
    if (config('corporate-identity.registry_verified')) {
        $organization['foundingDate'] = config('corporate-identity.incorporated');
        $organization['identifier'] = config('corporate-identity.entity_id');
    }
@endphp
<!doctype html>
<html lang="{{ $publicLocale }}" dir="{{ $ar ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#0a0a0a">
    <meta name="color-scheme" content="dark">
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
    <meta property="og:image" content="https://nexvary.com/assets/corporate/seal-512.png">
    <meta property="og:image:width" content="512"><meta property="og:image:height" content="512">
    <meta property="og:image:alt" content="{{ $ar ? 'ختم نيكسفاري الرسمي' : 'Official Nexvary corporate seal' }}">
    <meta name="twitter:card" content="summary">
    <link rel="icon" href="/favicon.ico" sizes="16x16 32x32 48x48">
    @foreach([16,32,48] as $size)<link rel="icon" type="image/png" sizes="{{ $size }}x{{ $size }}" href="/assets/corporate/seal-{{ $size }}.png">@endforeach
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="manifest" href="/site.webmanifest">
    @vite(['resources/css/public.css', 'resources/css/corporate.css', 'resources/js/public.ts'])
    <script type="application/ld+json" nonce="{{ Vite::cspNonce() }}">{!! json_encode($organization, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT) !!}</script>
    @stack('head')
</head>
<body class="nc-site">
<a href="#main-content" class="nc-skip">{{ $ar ? 'انتقل إلى المحتوى' : 'Skip to content' }}</a>
<div class="nc-shell">
<header class="nc-header" data-testid="site-header">
    <a class="nc-brand" href="/{{ $ar ? '?lang=ar' : '' }}" aria-label="{{ $ar ? 'نيكسفاري — الرئيسية' : 'Nexvary Inc home' }}"><img src="/assets/corporate/seal-nav.webp" width="72" height="72" alt=""><span>NEXVARY INC<small>{{ $ar ? 'التقنية والبحث والابتكار' : 'TECHNOLOGY · RESEARCH · INNOVATION' }}</small></span></a>
    <nav class="nc-nav" aria-label="{{ $ar ? 'التنقل الرئيسي' : 'Primary navigation' }}">@foreach($nav as [$url,$en,$arabic])<a href="{{ $url }}{{ $ar ? '?lang=ar' : '' }}" @if(request()->getPathInfo() === $url) aria-current="page" @endif>{{ $ar ? $arabic : $en }}</a>@endforeach</nav>
    <a class="nc-language" href="{{ request()->getPathInfo() }}?lang={{ $ar ? 'en' : 'ar' }}" lang="{{ $ar ? 'en' : 'ar' }}" aria-label="{{ $ar ? 'Switch to English' : 'التبديل إلى العربية' }}">{{ $ar ? 'English' : 'العربية' }}</a>
    <details class="nc-menu"><summary>{{ $ar ? 'القائمة' : 'Menu' }}</summary><nav aria-label="{{ $ar ? 'قائمة الهاتف' : 'Mobile navigation' }}">@foreach($nav as [$url,$en,$arabic])<a href="{{ $url }}{{ $ar ? '?lang=ar' : '' }}">{{ $ar ? $arabic : $en }}</a>@endforeach</nav></details>
</header>
@unless(request()->routeIs('home'))<div class="nx-back-row"><a class="nx-back-button" data-testid="back-button" href="/{{ $ar ? '?lang=ar' : '' }}">{{ $ar ? '→ الرئيسية' : '← Home' }}</a></div>@endunless
@yield('content')
<footer class="nc-footer">
    <div class="nc-footer-top"><div><img src="/assets/corporate/seal-nav.webp" alt="" width="64" height="64" loading="lazy"><strong>NEXVARY INC</strong><p>{{ $ar ? 'هندسة مستقبل رقمي أكثر أمانًا وذكاءً.' : 'Engineering a safer, smarter digital world.' }}</p></div><nav aria-label="{{ $ar ? 'روابط التذييل' : 'Footer navigation' }}">@foreach($nav as [$url,$en,$arabic])<a href="{{ $url }}{{ $ar ? '?lang=ar' : '' }}">{{ $ar ? $arabic : $en }}</a>@endforeach<a href="/privacy{{ $ar ? '?lang=ar' : '' }}">{{ $ar ? 'سياسة الخصوصية' : 'Privacy Policy' }}</a><a href="/terms{{ $ar ? '?lang=ar' : '' }}">{{ $ar ? 'شروط الاستخدام' : 'Terms of Use' }}</a><a href="/our-work{{ $ar ? '?lang=ar' : '' }}">{{ $ar ? 'أعمالنا' : 'Our Work' }}</a><a href="/apps{{ $ar ? '?lang=ar' : '' }}">{{ $ar ? 'التطبيقات' : 'Apps' }}</a><a href="/safescan{{ $ar ? '?lang=ar' : '' }}">SafeScan</a></nav><div><a href="mailto:info@nexvary.com">info@nexvary.com</a><a href="https://github.com/nexvary" target="_blank" rel="noopener noreferrer">GitHub</a><a href="https://nexvary.com/">{{ $ar ? 'الموقع الرسمي' : 'Website' }}</a><a href="https://www.facebook.com/share/14p9krEn5ij/" target="_blank" rel="noopener noreferrer">Facebook</a><a href="https://www.youtube.com/@NexvaryInc" target="_blank" rel="noopener noreferrer">YouTube</a><a href="https://x.com/Nexvary" target="_blank" rel="noopener noreferrer">X</a><p>{{ $ar ? 'بيانات الشركة كما قدّمها المالك: وايومنغ، الولايات المتحدة.' : 'Owner-supplied company jurisdiction: Wyoming, United States.' }}</p></div></div>
    <div class="nc-copyright">© {{ date('Y') }} Nexvary Inc. {{ $ar ? 'جميع الحقوق محفوظة.' : 'All rights reserved.' }}</div>
</footer>
</div>
</body></html>
