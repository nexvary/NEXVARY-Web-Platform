@extends('public.layout')

@section('title', app()->getLocale() === 'ar' ? 'NEXVARY — مركز العمليات الأمنية العالمي' : 'NEXVARY — Global Security Operations Center')
@section('description', app()->getLocale() === 'ar' ? 'منصة NEXVARY لمراقبة وتحليل الأمن السيبراني ومكافحة التجسس الفني والتحليل الجنائي الرقمي والخصوصية والاستجابة للحوادث.' : 'NEXVARY global security operations platform for cybersecurity, counter-surveillance, digital forensics, privacy, monitoring, analysis and response.')
@section('canonical', 'https://nexvary.com/')

@push('head')
    @vite(['resources/css/site-command-v9.css', 'resources/css/site-density-v10.css', 'resources/css/site-command-v11.css'])
@endpush

@section('content')
@php($ar = app()->getLocale() === 'ar')
<main class="nx-exec" dir="{{ $ar ? 'rtl' : 'ltr' }}" data-testid="final-command-home" data-release-ui="command-center-v10">
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
            <section class="nx-kpi-head">
                <div class="nx-title-block"><small>NEXVARY / {{ $ar ? 'حماية رقمية وهندسة برمجيات' : 'DIGITAL SECURITY & SOFTWARE' }}</small><h1>{{ $ar ? 'رؤية أمنية تتجاوز الظاهر' : 'Security beyond the visible.' }}</h1><p>{{ $ar ? 'الأمن السيبراني · مكافحة التجسس الفني · الأدلة الرقمية · برمجيات متخصصة' : 'Cybersecurity · Counter-surveillance · Digital forensics · Purpose-built software' }}</p><a class="nx-hero-link" href="/contact">{{ $ar ? 'تحدث إلى فريقنا' : 'Talk to our team' }} <span aria-hidden="true">{{ $ar ? '←' : '→' }}</span></a></div>
                <div class="nx-kpi"><span>01</span><strong>{{ $ar ? 'حماية' : 'PROTECT' }}</strong><span>{{ $ar ? 'تقييم المخاطر والتحصين' : 'Assess & harden' }}</span></div>
                <div class="nx-kpi"><span>02</span><strong>{{ $ar ? 'تحقيق' : 'INVESTIGATE' }}</strong><span>{{ $ar ? 'أدلة واستجابة للحوادث' : 'Forensics & response' }}</span></div>
                <div class="nx-kpi"><span>03</span><strong>{{ $ar ? 'ابتكار' : 'BUILD' }}</strong><span>{{ $ar ? 'أنظمة صممت للمهام الحساسة' : 'Software for critical work' }}</span></div>
            </section>

            <section class="nx-visual-grid">
                <div class="nx-map-card">
                    <div class="nx-map-tabs" role="group" aria-label="{{ $ar ? 'مرشحات الخريطة التجريبية' : 'Simulated map filters' }}"><button type="button" class="active" data-map-filter="all" aria-pressed="true">{{ $ar ? 'كل الإشارات' : 'All signals' }}</button><button type="button" data-map-filter="scan" aria-pressed="false">{{ $ar ? 'الاستطلاع' : 'Recon' }}</button><button type="button" data-map-filter="attack" aria-pressed="false">{{ $ar ? 'الهجمات' : 'Attacks' }}</button><button type="button" data-map-filter="infrastructure" aria-pressed="false">{{ $ar ? 'البنية' : 'Infrastructure' }}</button></div>
                    <div class="nx-map-host"><div class="nx-command nx-command-globe"><div class="nx-world-stage"></div></div></div>
                </div>
                <aside class="nx-right-rail">
                    <section class="nx-time-card"><small>{{ $ar ? 'توقيت مركز العمليات' : 'OPERATIONS CLOCK' }}</small><strong>{{ now()->timezone('Africa/Cairo')->format('H:i:s') }}</strong><span>Cairo · UTC+3</span><em>UTC {{ now()->timezone('UTC')->format('H:i:s') }}</em></section>
                    <section class="nx-compass-card"><div class="nx-compass"><i></i><b>NX</b><span class="n">N</span><span class="e">E</span><span class="s">S</span><span class="w">W</span></div><strong>{{ $ar ? 'أنت في الاتجاه الصحيح' : "YOU'RE ON THE RIGHT PATH" }}</strong></section>
                    <section class="nx-health-card"><h3>{{ $ar ? 'ملخص المشهد' : 'OPERATIONAL CONTEXT' }}</h3><div class="nx-good">◌ {{ $ar ? 'عرض توضيحي للمنصة' : 'PLATFORM DEMONSTRATION' }}</div><dl><div><dt>{{ $ar ? 'مصدر الخريطة' : 'Geography' }}</dt><dd>Natural Earth</dd></div><div><dt>{{ $ar ? 'إشارات التهديد' : 'Threat signals' }}</dt><dd>{{ $ar ? 'محاكاة' : 'Simulated' }}</dd></div><div><dt>{{ $ar ? 'تحديث العرض' : 'Feed interval' }}</dt><dd>15 s</dd></div></dl><a href="/services">{{ $ar ? 'استكشف خدماتنا' : 'Explore capabilities' }} <span aria-hidden="true">{{ $ar ? '←' : '→' }}</span></a></section>
                </aside>
            </section>

            <section class="nx-data-row">
                <article class="nx-data-panel nx-feed"><header><b>{{ $ar ? 'أمثلة إشارات التهديد' : 'Threat signal examples' }}</b><span>SIMULATED</span></header>
                    @foreach([['17:24:12',$ar?'محاولة وصول غير مصرح':'Unauthorized access attempt','RU','critical'],['17:23:54',$ar?'نشاط فحص للمنافذ':'Port scanning activity','CN','warn'],['17:23:28',$ar?'بصمة خبيثة محتملة':'Possible malicious fingerprint','IR','warn'],['17:22:41',$ar?'نشاط عابر على API':'Transient API activity','US','ok'],['17:22:10',$ar?'محاولة تصعيد صلاحيات':'Privilege escalation attempt','DE','critical']] as $row)
                        <div class="nx-feed-row"><time>{{ $row[0] }}</time><span><i class="{{ $row[3] }}"></i>{{ $row[1] }}</span><b>{{ $row[2] }}</b></div>
                    @endforeach
                </article>
                <article class="nx-data-panel"><header><b>{{ $ar ? 'توزيع افتراضي حسب المنطقة' : 'Illustrative regional mix' }}</b><span>DEMO</span></header><div class="nx-donut"><i></i><div><span>38% {{ $ar ? 'آسيا' : 'Asia' }}</span><span>28% {{ $ar ? 'أوروبا' : 'Europe' }}</span><span>18% {{ $ar ? 'أمريكا الشمالية' : 'N. America' }}</span><span>10% {{ $ar ? 'أفريقيا' : 'Africa' }}</span></div></div></article>
                <article class="nx-data-panel"><header><b>{{ $ar ? 'اتجاه افتراضي · 24 ساعة' : 'Illustrative trend · 24h' }}</b><span>DEMO</span></header><div class="nx-line-chart"><svg viewBox="0 0 320 110" preserveAspectRatio="none"><polyline points="0,82 25,72 45,78 68,48 92,63 115,42 138,70 164,55 190,62 215,32 240,44 266,29 292,48 320,39"></polyline><polyline class="secondary" points="0,92 30,88 60,84 90,90 120,73 150,79 180,70 210,76 240,64 270,72 300,60 320,66"></polyline></svg></div></article>
                <article class="nx-data-panel nx-services"><header><b>{{ $ar ? 'مجالات الخبرة' : 'Core capabilities' }}</b></header>@foreach([[$ar?'الأمن السيبراني':'Cybersecurity','01'],[$ar?'الاستجابة للحوادث':'Incident response','02'],[$ar?'الأدلة الرقمية':'Digital forensics','03'],[$ar?'مكافحة التجسس الفني':'Counter-surveillance','04'],[$ar?'هندسة البرمجيات':'Software engineering','05']] as $service)<span><i></i>{{ $service[0] }}<b>{{ $service[1] }}</b></span>@endforeach</article>
            </section>

            <section class="nx-quick-row">
                <a href="/safescan"><span>◇</span><span><b>{{ $ar ? 'فحص شامل' : 'Comprehensive Scan' }}</b><small>{{ $ar ? 'بدء فحص أمني الآن' : 'Start security scan' }}</small></span></a>
                <a href="/apps"><span>▤</span><span><b>{{ $ar ? 'تحليل ملف' : 'Analyze File' }}</b><small>{{ $ar ? 'فحص ملف مشتبه به' : 'Inspect suspicious file' }}</small></span></a>
                <a href="/services"><span>⌁</span><span><b>{{ $ar ? 'مراقبة مباشرة' : 'Live Monitoring' }}</b><small>{{ $ar ? 'عرض النشاط الحالي' : 'View current activity' }}</small></span></a>
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
