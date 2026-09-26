@extends('public.layout')

@section('title', app()->getLocale() === 'ar' ? 'NEXVARY — مركز العمليات الأمنية العالمي' : 'NEXVARY — Global Security Operations Center')
@section('description', app()->getLocale() === 'ar' ? 'منصة NEXVARY لمراقبة وتحليل الأمن السيبراني ومكافحة التجسس الفني والتحليل الجنائي الرقمي والخصوصية والاستجابة للحوادث.' : 'NEXVARY global security operations platform for cybersecurity, counter-surveillance, digital forensics, privacy, monitoring, analysis and response.')
@section('canonical', 'https://nexvary.com/')

@push('head')
    @vite(['resources/css/site-command-v9.css', 'resources/css/site-density-v10.css', 'resources/css/site-command-v11.css', 'resources/css/site-command-v14.css'])
@endpush

@section('content')
@php($ar = app()->getLocale() === 'ar')
<main class="nx-exec" dir="{{ $ar ? 'rtl' : 'ltr' }}" data-testid="final-command-home" data-release-ui="intelligence-v14">
    <div class="nx-exec-shell">
        <aside class="nx-exec-side">
            <div class="nx-side-heading"><small>NEXVARY / SOC</small><strong>{{ $ar ? 'منصة العمليات' : 'OPERATIONS' }}</strong></div>
            <nav role="group" aria-label="{{ $ar ? 'تنقل مركز العمليات' : 'Command navigation' }}">
                <a href="/" class="active"><span>◉</span><span>{{ $ar ? 'الرئيسية' : 'Home' }}</span></a>
                <a href="/services"><span>◎</span><span>{{ $ar ? 'الخدمات' : 'Services' }}</span></a>
                <a href="/safescan"><span>◇</span><span>{{ $ar ? 'التهديدات' : 'Threats' }}</span></a>
                <a href="/apps"><span>⌁</span><span>{{ $ar ? 'البرمجيات' : 'Software' }}</span></a>
                <a href="/our-work"><span>▦</span><span>{{ $ar ? 'أعمالنا' : 'Our work' }}</span></a>
                <a href="/contact"><span>▤</span><span>{{ $ar ? 'طلب تقرير' : 'Request a report' }}</span></a>
                <a href="/about"><span>⚙</span><span>{{ $ar ? 'عن الشركة' : 'About NEXVARY' }}</span></a>
            </nav>
            <div class="nx-side-globe"><span class="nx-side-emblem" aria-hidden="true">N<span>×</span></span><b>NEXVARY</b><span>SECURITY · SOFTWARE · INTELLIGENCE</span><small>{{ $ar ? 'رؤية أوضح. قرار أقوى.' : 'Clarity for critical decisions.' }}</small></div>
        </aside>

        <section class="nx-exec-main">
            <section class="nx-kpi-head nx-executive-hero">
                <div class="nx-title-block"><small>NEXVARY / {{ $ar ? 'الأمن الرقمي وهندسة البرمجيات' : 'DIGITAL SECURITY & SOFTWARE ENGINEERING' }}</small><h1>{{ $ar ? 'نرى المخاطر بوضوح. ونبني الحماية بثقة.' : 'Clarity in risk. Confidence in response.' }}</h1><p>{{ $ar ? 'نساعد المؤسسات على حماية أنظمتها وبيئاتها الحساسة من خلال التقييم الأمني، والفحص الفني، والتحقيق الرقمي، وتطوير البرمجيات.' : 'Security assessment, technical counter-surveillance, digital forensics and software engineering for organizations with critical decisions to make.' }}</p><div class="nx-hero-actions"><a class="nx-hero-link" href="/contact">{{ $ar ? 'ناقش احتياجات مؤسستك' : 'Discuss your security needs' }} <span aria-hidden="true">{{ $ar ? '←' : '→' }}</span></a><a class="nx-hero-secondary" href="/services">{{ $ar ? 'استكشف الخدمات' : 'Explore services' }}</a></div></div>
                <div class="nx-hero-index"><span>01 / ASSESS</span><strong>{{ $ar ? 'تقييم واقعي' : 'Understand exposure' }}</strong><p>{{ $ar ? 'افهم الأصول ونقاط الضعف قبل اتخاذ القرار.' : 'Find the exposure that matters.' }}</p></div>
                <div class="nx-hero-index"><span>02 / INVESTIGATE</span><strong>{{ $ar ? 'أدلة واضحة' : 'Trace the evidence' }}</strong><p>{{ $ar ? 'استجابة وتحليل يمكن مراجعتهما.' : 'Documented response and forensics.' }}</p></div>
                <div class="nx-hero-index"><span>03 / ENGINEER</span><strong>{{ $ar ? 'أنظمة موثوقة' : 'Build with purpose' }}</strong><p>{{ $ar ? 'برمجيات تناسب المهمة والخصوصية.' : 'Software designed for critical work.' }}</p></div>
            </section>
            <div class="nx-section-divider"><span>{{ $ar ? 'مرصد الاستخبارات' : 'INTELLIGENCE OBSERVATORY' }}</span><span>{{ $ar ? 'بيانات عامة موثقة · ليست مراقبة لشبكة NEXVARY' : 'VERIFIED PUBLIC DATA · NOT NEXVARY NETWORK TELEMETRY' }}</span></div>
            <section class="nx-visual-grid">
                <div class="nx-map-card">
                    <div class="nx-map-tabs" role="group" aria-label="{{ $ar ? 'مرشحات الأحداث الجغرافية' : 'Geolocated observation filters' }}"><button type="button" class="active" data-map-filter="all" aria-pressed="true">{{ $ar ? 'كل الإشارات' : 'All signals' }}</button><button type="button" data-map-filter="scan" aria-pressed="false">{{ $ar ? 'الاستطلاع' : 'Recon' }}</button><button type="button" data-map-filter="attack" aria-pressed="false">{{ $ar ? 'الهجمات' : 'Attacks' }}</button><button type="button" data-map-filter="infrastructure" aria-pressed="false">{{ $ar ? 'البنية' : 'Infrastructure' }}</button></div>
                    <div class="nx-map-host"><div class="nx-command nx-command-globe"><div class="nx-world-stage"></div></div></div>
                </div>
                <aside class="nx-right-rail nx-source-rail">
                    <section class="nx-health-card"><small>{{ $ar ? 'مصدر البيانات المؤكد' : 'VERIFIED SOURCE' }}</small><h3>CISA KEV</h3><p>{{ $ar ? 'ثغرات معروفة جرى استغلالها فعليًا، من الكتالوج الرسمي لوكالة CISA.' : 'Known exploited vulnerabilities from the official CISA catalog.' }}</p><strong data-kev-count>—</strong><span>{{ $ar ? 'إدخال في الكتالوج' : 'catalog entries' }}</span><div class="nx-source-state" data-intel-status>{{ $ar ? 'جار جلب البيانات' : 'FETCHING SOURCE' }}</div><a href="https://www.cisa.gov/known-exploited-vulnerabilities-catalog" target="_blank" rel="noopener noreferrer">{{ $ar ? 'افتح المصدر الرسمي' : 'View official source' }} <span aria-hidden="true">↗</span></a></section>
                    <section class="nx-health-card nx-source-note"><small>{{ $ar ? 'دقة العرض' : 'DATA INTEGRITY' }}</small><h3>{{ $ar ? 'الخريطة الجغرافية' : 'Geographic context' }}</h3><p>{{ $ar ? 'حدود الدول مرجعية من Natural Earth. لا يحدد كتالوج CISA مواقع الهجمات، لذا لا نحول الثغرات إلى مسارات أو نقاط وهمية.' : 'Country boundaries come from Natural Earth. CISA KEV has no attack coordinates, so we do not invent event paths or locations.' }}</p></section>
                </aside>
            </section>

            <section class="nx-intel-section">
                <div class="nx-intel-heading"><div><small>CISA / KNOWN EXPLOITED VULNERABILITIES</small><h2>{{ $ar ? 'ثغرات مؤكدة الاستغلال' : 'Exploitation confirmed. Priorities made visible.' }}</h2><p>{{ $ar ? 'آخر الإدخالات المنشورة في الكتالوج. البيانات لا تعني تعرض أنظمتك لهذه الثغرات.' : 'Recent catalog additions. Their presence does not imply your systems are affected.' }}</p></div><a href="https://www.cisa.gov/known-exploited-vulnerabilities-catalog" target="_blank" rel="noopener noreferrer">{{ $ar ? 'كتالوج CISA' : 'Explore CISA KEV' }} ↗</a></div>
                <div class="nx-kev-list" data-kev-list><p>{{ $ar ? 'جار تحميل الإدخالات الموثقة…' : 'Loading verified catalog entries…' }}</p></div>
            </section>
            <section class="nx-quick-row">
                <a href="/safescan"><span>◇</span><span><b>{{ $ar ? 'فحص شامل' : 'Comprehensive Scan' }}</b><small>{{ $ar ? 'بدء فحص أمني الآن' : 'Start security scan' }}</small></span></a>
                <a href="/apps"><span>▤</span><span><b>{{ $ar ? 'تحليل ملف' : 'Analyze File' }}</b><small>{{ $ar ? 'فحص ملف مشتبه به' : 'Inspect suspicious file' }}</small></span></a>
                <a href="/services"><span>⌁</span><span><b>{{ $ar ? 'الخدمات الأمنية' : 'Security Services' }}</b><small>{{ $ar ? 'استكشف الخدمات المتخصصة' : 'Explore specialist services' }}</small></span></a>
                <a href="/our-work"><span>▦</span><span><b>{{ $ar ? 'إدارة الأصول' : 'Asset Management' }}</b><small>{{ $ar ? 'عرض الأصول الرقمية' : 'View digital assets' }}</small></span></a>
                <a href="/contact"><span>@</span><span><b>{{ $ar ? 'إنشاء تقرير' : 'Create Report' }}</b><small>{{ $ar ? 'طلب تقرير مخصص' : 'Request tailored report' }}</small></span></a>
            </section>
            <section class="nx-capabilities" aria-labelledby="nx-capabilities-title">
                <div class="nx-capabilities-intro">
                    <small>NEXVARY / {{ $ar ? 'مجالات العمل' : 'DISCIPLINES' }}</small>
                    <h2 id="nx-capabilities-title">{{ $ar ? 'خبرة عملية للأنظمة التي لا تحتمل التخمين' : 'Built for decisions that cannot rely on guesswork.' }}</h2>
                    <p>{{ $ar ? 'نجمع بين التقييم الأمني، والفحص الفني، والتحليل الجنائي الرقمي، وتطوير البرمجيات المتخصصة. يبدأ كل عمل بتحديد النطاق والأدلة والمخاطر، وينتهي بمخرجات واضحة قابلة للمراجعة.' : 'We bring together security assessment, technical inspection, digital forensics and specialist software engineering. Every engagement starts with a clear scope, evidence and risk model, and ends with findings that can be reviewed and acted upon.' }}</p>
                </div>
                <div class="nx-capabilities-grid">
                    <article><span>01 / ASSESS</span><h3>{{ $ar ? 'فهم سطح التعرض' : 'Understand the exposure' }}</h3><p>{{ $ar ? 'نراجع البنية الرقمية والأجهزة وبيئات العمل، ونرتب نقاط الضعف بحسب أثرها الحقيقي قبل وضع خطة معالجة مناسبة.' : 'We examine digital infrastructure, devices and working environments, then prioritize weaknesses by practical impact before defining a remediation plan.' }}</p><a href="/services">{{ $ar ? 'الخدمات الأمنية' : 'Security services' }} <span aria-hidden="true">{{ $ar ? '←' : '→' }}</span></a></article>
                    <article><span>02 / INVESTIGATE</span><h3>{{ $ar ? 'تحليل يمكن تتبعه' : 'Evidence you can trace' }}</h3><p>{{ $ar ? 'عند وقوع حادث أو الاشتباه في تجسس فني، نعتمد خطوات موثقة لحفظ الأدلة وتحليل المؤشرات وتقديم نتائج مفهومة لصاحب القرار.' : 'When an incident or technical surveillance concern arises, we use documented steps to preserve evidence, analyze indicators and present findings clearly to decision makers.' }}</p><a href="/about">{{ $ar ? 'تعرف على NEXVARY' : 'About NEXVARY' }} <span aria-hidden="true">{{ $ar ? '←' : '→' }}</span></a></article>
                    <article><span>03 / ENGINEER</span><h3>{{ $ar ? 'برمجيات تخدم المهمة' : 'Software shaped around the mission' }}</h3><p>{{ $ar ? 'نطور أدوات ومنصات تربط بين الأمان وسهولة الاستخدام، مع اعتبار الخصوصية وإدارة الصلاحيات وقابلية الصيانة من بداية التصميم.' : 'We build tools and platforms that balance security with usability, considering privacy, access control and maintainability from the first design decision.' }}</p><a href="/our-work">{{ $ar ? 'استعرض أعمالنا' : 'Explore our work' }} <span aria-hidden="true">{{ $ar ? '←' : '→' }}</span></a></article>
                </div>
            </section>
        </section>
    </div>
</main>
@endsection
