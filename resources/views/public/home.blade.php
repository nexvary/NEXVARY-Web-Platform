@extends('public.layout')
@section('title', app()->getLocale() === 'ar' ? 'نيكسفاري | التقنية والبحث والابتكار' : 'Nexvary Inc | Technology, Research & Innovation')
@section('description', app()->getLocale() === 'ar' ? 'نيكسفاري تطوّر تقنيات الأمن السيبراني والذكاء الاصطناعي والبرمجيات من خلال البحث والابتكار.' : 'Nexvary Inc develops cybersecurity, artificial intelligence and software through focused engineering, digital research and innovation.')
@section('canonical', 'https://nexvary.com/')
@push('head')
<link rel="preload" as="image" type="image/avif" media="(max-width: 600px)" imagesrcset="/assets/corporate/hero-seal-640.avif 640w, /assets/corporate/hero-seal-960.avif 960w" imagesizes="100vw" fetchpriority="high">
<link rel="preload" as="image" type="image/avif" media="(min-width: 601px)" imagesrcset="/assets/corporate/hero-seal-640.avif 640w, /assets/corporate/hero-seal-960.avif 960w, /assets/corporate/hero-seal-1536.avif 1536w" imagesizes="100vw" fetchpriority="high">
@endpush
@section('content')
@php($ar = app()->getLocale() === 'ar')
<main id="main-content" class="nc-home">
<section class="nc-hero" aria-labelledby="hero-title">
    <picture class="nc-hero-picture">
        <source media="(max-width: 600px)" type="image/avif" srcset="/assets/corporate/hero-seal-640.avif 640w, /assets/corporate/hero-seal-960.avif 960w" sizes="100vw">
        <source media="(max-width: 600px)" type="image/webp" srcset="/assets/corporate/hero-seal-640.webp 640w, /assets/corporate/hero-seal-960.webp 960w" sizes="100vw">
        <source media="(max-width: 600px)" type="image/jpeg" srcset="/assets/corporate/hero-seal-640.jpg 640w, /assets/corporate/hero-seal-960.jpg 960w" sizes="100vw">
        <source type="image/avif" srcset="/assets/corporate/hero-seal-640.avif 640w, /assets/corporate/hero-seal-960.avif 960w, /assets/corporate/hero-seal-1536.avif 1536w" sizes="100vw">
        <source type="image/webp" srcset="/assets/corporate/hero-seal-640.webp 640w, /assets/corporate/hero-seal-960.webp 960w, /assets/corporate/hero-seal-r2-1536.webp 1536w" sizes="100vw">
        <img src="/assets/corporate/hero-seal-960.jpg" srcset="/assets/corporate/hero-seal-640.jpg 640w, /assets/corporate/hero-seal-960.jpg 960w, /assets/corporate/hero-seal-1536.jpg 1536w" sizes="100vw" width="1536" height="1024" fetchpriority="high" alt="{{ $ar ? 'تصور معماري: مدخل نيكسفاري بواجهة حجرية وزجاجية وبوابة أمنية وحدائق ونافورة وعلم الشركة' : 'Architectural concept: Nexvary entrance with stone and glass facade, security gate, gardens, fountain and company flag' }}">
    </picture>
    <div class="nc-hero-copy"><p class="nc-eyebrow">{{ $ar ? 'طموح في الرؤية. ودقة في التنفيذ.' : 'VISION WITH PURPOSE. ENGINEERING WITH CARE.' }}</p><h1 id="hero-title">NEXVARY INC<span>{{ $ar ? 'نحو عالم رقمي' : 'Engineering a safer,' }}<br>{{ $ar ? 'أكثر أمانًا وذكاءً.' : 'smarter digital world.' }}</span></h1><p class="nc-lead">{{ $ar ? 'نطوّر تقنيات الأمن السيبراني والذكاء الاصطناعي والبرمجيات؛ لنحوّل البحث المنهجي إلى حلول عملية ذات هدف واضح.' : 'We develop cybersecurity, artificial intelligence and software, turning disciplined research into practical technology with a clear purpose.' }}</p><div class="nc-actions"><a class="nc-button nc-button-gold" href="/about{{ $ar ? '?lang=ar' : '' }}">{{ $ar ? 'عن نيكسفاري' : 'About Nexvary' }} @include('public.icon',['icon'=>'arrow'])</a><a class="nc-button" href="#our-focus">{{ $ar ? 'منهجنا' : 'Our Approach' }} @include('public.icon',['icon'=>'arrow'])</a></div></div>
    <p class="nc-concept">{{ $ar ? 'تصور معماري للهوية البصرية — لا يمثل مقرًا فعليًا موثقًا أو فريق عمل موثقًا.' : 'Conceptual architectural visualization — not a documented physical office or staff.' }}</p>
</section>
<section class="nc-section nc-capabilities" aria-labelledby="capabilities-title"><div class="nc-heading"><p class="nc-eyebrow">{{ $ar ? 'مجالاتنا الأساسية' : 'CORE CAPABILITIES' }}</p><h2 id="capabilities-title">{{ $ar ? 'تقنيات مترابطة. وطموح واضح.' : 'Connected disciplines. Clear ambition.' }}</h2></div><div class="nc-grid">
@foreach([
['shield','Cybersecurity','الأمن السيبراني','Security-aware architecture and defensive software built around defined risks.','بنية تراعي الأمن وبرمجيات دفاعية تستند إلى فهم المخاطر.'],
['brain','Artificial Intelligence','الذكاء الاصطناعي','Intelligent workflows with explicit model, data and validation boundaries.','مسارات عمل ذكية بحدود واضحة للنماذج والبيانات والتحقق.'],
['code','Software Engineering','هندسة البرمجيات','Purpose-built applications with maintainable foundations and considered interfaces.','تطبيقات هادفة بأسس قابلة للصيانة وواجهات مدروسة.'],
['research','Digital Research & Innovation','البحث الرقمي والابتكار','Methodical exploration, documented findings and practical experimentation.','بحث منهجي ونتائج موثقة وتجارب عملية.']
] as [$icon,$en,$arabic,$copy,$copyAr])<article class="nc-capability">@include('public.icon',['icon'=>$icon])<h3>{{ $ar ? $arabic : $en }}</h3><p>{{ $ar ? $copyAr : $copy }}</p></article>@endforeach
</div></section>
<section id="our-focus" class="nc-section nc-focus" aria-labelledby="focus-title"><div class="nc-heading"><p class="nc-eyebrow">{{ $ar ? 'منهجنا' : 'OUR FOCUS' }}</p><h2 id="focus-title">{{ $ar ? 'الثقة تبدأ بطريقة البناء.' : 'Trust begins with how we build.' }}</h2><p>{{ $ar ? 'مبادئ توجّه قراراتنا من الفكرة الأولى إلى التحقق والإصدار.' : 'Principles that guide our decisions, from the first idea to validation and release.' }}</p></div><div class="nc-grid">
@foreach([
['shield','Security by Design','الأمن منذ التصميم','Minimize exposure. Make permissions and data boundaries deliberate.','تقليل التعرض للمخاطر وتحديد الصلاحيات وحدود البيانات بعناية.'],
['light','Innovation Driven','الابتكار محرّكنا','Explore ideas against real needs and measurable outcomes.','اختبار الأفكار في ضوء احتياجات حقيقية ونتائج قابلة للقياس.'],
['chart','Trusted Technology','تقنية جديرة بالثقة','Document limitations and verify results before making claims.','توثيق الحدود والتحقق من النتائج قبل إعلان القدرات.'],
['globe','Global Vision','رؤية عالمية','Design for different languages, devices and working environments.','التصميم للغات وأجهزة وبيئات عمل مختلفة.']
] as [$icon,$en,$arabic,$copy,$copyAr])<article>@include('public.icon',['icon'=>$icon])<h3>{{ $ar ? $arabic : $en }}</h3><p>{{ $ar ? $copyAr : $copy }}</p></article>@endforeach
</div></section>
@include('public.identity')
<section class="nc-section nc-contact-strip"><div><p class="nc-eyebrow">{{ $ar ? 'لنبدأ حوارًا' : 'BEGIN A CONVERSATION' }}</p><h2>{{ $ar ? 'لديك هدف تقني واضح؟' : 'Have a technology challenge in mind?' }}</h2><p>{{ $ar ? 'تواصل لمناقشة تطوير البرمجيات أو البحث أو فرص التعاون.' : 'Contact us about software engineering, research or business collaboration.' }}</p></div><a class="nc-button nc-button-gold" href="/contact{{ $ar ? '?lang=ar' : '' }}">{{ $ar ? 'تواصل معنا' : 'Contact Nexvary' }} @include('public.icon',['icon'=>'mail'])</a></section>
</main>
@endsection
