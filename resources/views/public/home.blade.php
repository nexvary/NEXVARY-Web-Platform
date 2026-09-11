@extends('public.layout')

@section('title', app()->getLocale() === 'ar' ? 'NEXVARY — مركز العمليات الأمنية العالمي' : 'NEXVARY — Global Security Operations Center')
@section('description', app()->getLocale() === 'ar' ? 'منصة NEXVARY لمراقبة وتحليل الأمن السيبراني ومكافحة التجسس الفني والتحليل الجنائي الرقمي والخصوصية والاستجابة للحوادث.' : 'NEXVARY global security operations platform for cybersecurity, counter-surveillance, digital forensics, privacy, monitoring, analysis and response.')
@section('canonical', 'https://nexvary.com/')

@push('head')
    @vite('resources/css/site-command-v9.css')
@endpush

@section('content')
@php($ar = app()->getLocale() === 'ar')
<main class="nx-exec" dir="{{ $ar ? 'rtl' : 'ltr' }}" data-testid="final-command-home" data-release-ui="command-center-v10">
    <header class="nx-exec-top" data-testid="site-header">
        <a href="/" class="nx-exec-brand"><span class="nx-user-dot">N</span><span><b>NEXVARY</b><small>SECURITY COMMAND INTERFACE</small></span></a>
        <label class="nx-exec-search"><span aria-hidden="true">⌕</span><input aria-label="{{ $ar ? 'البحث' : 'Search' }}" placeholder="{{ $ar ? 'البحث في المنصة ...' : 'Search the platform ...' }}"></label>
        <div class="nx-exec-tools"><a href="/contact" class="nx-user-dot" aria-label="{{ $ar ? 'التواصل' : 'Contact' }}">@</a><span class="nx-user-label"><b>{{ $ar ? 'مدير النظام' : 'Administrator' }}</b><small>NEXVARY</small></span></div>
    </header>

    <div class="nx-exec-shell">
        <aside class="nx-exec-side">
            <nav aria-label="{{ $ar ? 'التنقل الرئيسي' : 'Primary navigation' }}">
                <a href="/" class="active"><span>◉</span><span>{{ $ar ? 'الرئيسية' : 'Home' }}</span></a>
                <a href="/services"><span>◎</span><span>{{ $ar ? 'الخدمات' : 'Services' }}</span></a>
                <a href="/safescan"><span>◇</span><span>{{ $ar ? 'التهديدات' : 'Threats' }}</span></a>
                <a href="/apps"><span>⌁</span><span>{{ $ar ? 'الرصد والتحليل' : 'Analytics' }}</span></a>
                <a href="/our-work"><span>▦</span><span>{{ $ar ? 'الأصول الرقمية' : 'Assets' }}</span></a>
                <a href="/contact"><span>▤</span><span>{{ $ar ? 'التقارير' : 'Reports' }}</span></a>
                <a href="/about"><span>⚙</span><span>{{ $ar ? 'الإعدادات' : 'Settings' }}</span></a>
            </nav>
            <div class="nx-side-globe"><span style="font-size:3rem">◎</span><b>NEXVARY</b><span>GLOBAL INTELLIGENCE</span><small>SAFER TOGETHER</small></div>
        </aside>

        <section class="nx-exec-main">
            <section class="nx-kpi-head">
                <div class="nx-title-block"><small>{{ $ar ? 'مرحبًا بك في' : 'WELCOME TO' }}</small><h1>{{ $ar ? 'مركز العمليات الأمنية العالمي' : 'Global Security Operations Center' }}</h1><p>{{ $ar ? 'مراقبة · تحليل · استجابة · حماية' : 'Monitor · Analyze · Respond · Protect' }}</p></div>
                <div class="nx-kpi"><span>◎</span><strong>183</strong><span>{{ $ar ? 'دولة مغطاة' : 'Countries' }}</span></div>
                <div class="nx-kpi"><span>▦</span><strong>2.4M</strong><span>{{ $ar ? 'مصدر بيانات' : 'Data sources' }}</span></div>
                <div class="nx-kpi"><span>◇</span><strong>17,382</strong><span>{{ $ar ? 'إشارة نشطة' : 'Signals' }}</span></div>
                <div class="nx-kpi"><span>●</span><strong>99.98%</strong><span>{{ $ar ? 'جاهزية' : 'Availability' }}</span></div>
            </section>

            <section class="nx-visual-grid">
                <div class="nx-map-card">
                    <div class="nx-map-tabs"><button class="active">{{ $ar ? 'خريطة التهديدات العالمية' : 'Global Threat Map' }}</button><button>{{ $ar ? 'التدفق المباشر' : 'Live Feed' }}</button><button>{{ $ar ? 'الهجمات السيبرانية' : 'Cyber Attacks' }}</button><button>{{ $ar ? 'مستوى المخاطر' : 'Risk Level' }}</button></div>
                    <div class="nx-map-host"><div class="nx-command nx-command-globe"><div class="nx-world-stage"></div></div></div>
                </div>
                <aside class="nx-right-rail">
                    <section class="nx-time-card"><small>{{ $ar ? 'توقيت مركز العمليات' : 'OPERATIONS CLOCK' }}</small><strong>{{ now()->timezone('Africa/Cairo')->format('H:i:s') }}</strong><span>Cairo · UTC+3</span><em>UTC {{ now()->timezone('UTC')->format('H:i:s') }}</em></section>
                    <section class="nx-compass-card"><div class="nx-compass"><i></i><b>NX</b><span class="n">N</span><span class="e">E</span><span class="s">S</span><span class="w">W</span></div><strong>{{ $ar ? 'أنت في الاتجاه الصحيح' : "YOU'RE ON THE RIGHT PATH" }}</strong></section>
                    <section class="nx-health-card"><h3>{{ $ar ? 'الوضع العام للنظام' : 'System Status' }}</h3><div class="nx-good">✓ {{ $ar ? 'آمن ومستقر' : 'Secure & Stable' }}</div><dl><div><dt>{{ $ar ? 'المعالجة' : 'Processing' }}</dt><dd>2.4M/s</dd></div><div><dt>{{ $ar ? 'زمن الاستجابة' : 'Response' }}</dt><dd>12 ms</dd></div><div><dt>{{ $ar ? 'سلامة البنية' : 'Infrastructure' }}</dt><dd>100%</dd></div></dl></section>
                </aside>
            </section>

            <section class="nx-data-row">
                <article class="nx-data-panel nx-feed"><header><b>{{ $ar ? 'أحدث التهديدات' : 'Latest Threats' }}</b><span>{{ $ar ? 'عرض الكل' : 'View all' }}</span></header>
                    @foreach([['17:24:12',$ar?'محاولة وصول غير مصرح':'Unauthorized access attempt','RU','critical'],['17:23:54',$ar?'نشاط فحص للمنافذ':'Port scanning activity','CN','warn'],['17:23:28',$ar?'بصمة خبيثة محتملة':'Possible malicious fingerprint','IR','warn'],['17:22:41',$ar?'نشاط عابر على API':'Transient API activity','US','ok'],['17:22:10',$ar?'محاولة تصعيد صلاحيات':'Privilege escalation attempt','DE','critical']] as $row)
                        <div class="nx-feed-row"><time>{{ $row[0] }}</time><span><i class="{{ $row[3] }}"></i>{{ $row[1] }}</span><b>{{ $row[2] }}</b></div>
                    @endforeach
                </article>
                <article class="nx-data-panel"><header><b>{{ $ar ? 'توزيع التهديدات حسب المنطقة' : 'Threats by Region' }}</b></header><div class="nx-donut"><i></i><div><span>38% {{ $ar ? 'آسيا' : 'Asia' }}</span><span>28% {{ $ar ? 'أوروبا' : 'Europe' }}</span><span>18% {{ $ar ? 'أمريكا الشمالية' : 'N. America' }}</span><span>10% {{ $ar ? 'أفريقيا' : 'Africa' }}</span></div></div></article>
                <article class="nx-data-panel"><header><b>{{ $ar ? 'اتجاهات التهديدات · 24 ساعة' : 'Threat Trends · 24h' }}</b></header><div class="nx-line-chart"><svg viewBox="0 0 320 110" preserveAspectRatio="none"><polyline points="0,82 25,72 45,78 68,48 92,63 115,42 138,70 164,55 190,62 215,32 240,44 266,29 292,48 320,39"></polyline><polyline class="secondary" points="0,92 30,88 60,84 90,90 120,73 150,79 180,70 210,76 240,64 270,72 300,60 320,66"></polyline></svg></div></article>
                <article class="nx-data-panel nx-services"><header><b>{{ $ar ? 'الخدمات الأمنية' : 'Security Services' }}</b></header>@foreach(['Firewall','Intrusion Detection','Malware Defense','Behavior Analytics','Data Center','Backup'] as $service)<span><i></i>{{ $service }}<b>{{ $ar ? 'يعمل' : 'Online' }}</b></span>@endforeach</article>
            </section>

            <section class="nx-quick-row">
                <a href="/safescan"><span>◇</span><span><b>{{ $ar ? 'فحص شامل' : 'Comprehensive Scan' }}</b><small>{{ $ar ? 'بدء فحص أمني الآن' : 'Start security scan' }}</small></span></a>
                <a href="/apps"><span>▤</span><span><b>{{ $ar ? 'تحليل ملف' : 'Analyze File' }}</b><small>{{ $ar ? 'فحص ملف مشتبه به' : 'Inspect suspicious file' }}</small></span></a>
                <a href="/services"><span>⌁</span><span><b>{{ $ar ? 'مراقبة مباشرة' : 'Live Monitoring' }}</b><small>{{ $ar ? 'عرض النشاط الحالي' : 'View current activity' }}</small></span></a>
                <a href="/our-work"><span>▦</span><span><b>{{ $ar ? 'إدارة الأصول' : 'Asset Management' }}</b><small>{{ $ar ? 'عرض الأصول الرقمية' : 'View digital assets' }}</small></span></a>
                <a href="/contact"><span>@</span><span><b>{{ $ar ? 'إنشاء تقرير' : 'Create Report' }}</b><small>{{ $ar ? 'طلب تقرير مخصص' : 'Request tailored report' }}</small></span></a>
            </section>
        </section>
    </div>
</main>
@endsection
