@extends('public.layout')

@section('title', app()->getLocale() === 'ar' ? 'NEXVARY — الأمن أبعد مما تراه' : 'NEXVARY — Security Beyond the Visible')
@section('description', app()->getLocale() === 'ar' ? 'منصة NEXVARY للأمن السيبراني ومكافحة التجسس الفني والتحليل الجنائي الرقمي وتقنيات الخصوصية والحماية متعددة الطبقات.' : 'NEXVARY unifies cybersecurity, counter-surveillance, digital forensics and privacy technology into one multi-layer security platform.')
@section('canonical', 'https://nexvary.com/')

@section('content')
@php($ar = app()->getLocale() === 'ar')
<main>
    <section class="nx-hero nx-hero-premium">
        <div class="nx-hero-copy">
            <p class="nx-kicker">NEXVARY SECURITY INTELLIGENCE</p>
            <h1>{{ $ar ? 'نرى ما لا يراه الآخرون.' : 'Security Beyond the Visible.' }}</h1>
            <p class="nx-lead">{{ $ar ? 'منصة أمنية متقدمة تجمع مكافحة التجسس، الأمن السيبراني، التحليل الجنائي الرقمي وتقنيات الخصوصية في تجربة واحدة فاخرة ومحمية.' : 'A premium security platform unifying counter-surveillance, cybersecurity, digital forensics and privacy technology in one protected experience.' }}</p>
            <div class="nx-actions"><a class="nx-btn nx-btn-primary" href="/services">{{ $ar ? 'استكشف الخدمات' : 'Explore services' }}</a><a class="nx-btn" href="/apps">{{ $ar ? 'التطبيقات' : 'Applications' }}</a></div>
            <div class="nx-status-strip"><span><i></i>TSCM</span><span><i></i>Cyber Defense</span><span><i></i>Digital Forensics</span><span><i></i>Privacy</span></div>
        </div>
        <div class="nx-command nx-command-globe" aria-label="Global security visualization">
            <div class="nx-command-topline" aria-hidden="true"><strong>NEXVARY COMMAND VISUAL</strong><span>RF AWARENESS LAYER</span></div>
            <div class="nx-rf-field" aria-hidden="true"><div class="nx-rf-sweep"></div><i class="nx-rf-node node-1"></i><i class="nx-rf-node node-2"></i><i class="nx-rf-node node-3"></i><i class="nx-rf-node node-4"></i></div>
            <div class="nx-globe-wrap" aria-hidden="true"><div class="nx-globe"><span class="nx-globe-lat lat-1"></span><span class="nx-globe-lat lat-2"></span><span class="nx-globe-lat lat-3"></span><span class="nx-globe-long long-1"></span><span class="nx-globe-long long-2"></span><span class="nx-globe-long long-3"></span><span class="nx-continent nx-continent-a"></span><span class="nx-continent nx-continent-b"></span><span class="nx-continent nx-continent-c"></span><span class="nx-globe-pulse pulse-1"></span><span class="nx-globe-pulse pulse-2"></span><span class="nx-globe-pulse pulse-3"></span><div class="nx-globe-core">N</div></div></div>
            <div class="nx-command-meta"><span><b>GLOBAL</b>{{ $ar ? 'وعي أمني متعدد الطبقات' : 'multi-layer security awareness' }}</span><span><b>VISUAL</b>{{ $ar ? 'تصور أمني ديناميكي' : 'dynamic intelligence visualization' }}</span><span><b>SECURE</b>{{ $ar ? 'هندسة محكومة بالاختبارات' : 'release-gated engineering' }}</span></div>
        </div>
    </section>

    <section class="nx-section">
        <div class="nx-section-title"><p>CAPABILITIES</p><h2>{{ $ar ? 'منظومة أمنية متعددة الطبقات' : 'A multi-layer security capability' }}</h2></div>
        <div class="nx-grid">
            @foreach([
                ['TSCM','RF and counter-surveillance operations'],['Cybersecurity','Hardening, assessment and incident response'],['SafeScan','Zero-storage browser-side file inspection'],['Audio Shield','Privacy-aware audio protection tools'],['Tower Guard','Cellular anomaly awareness and validation'],['Digital Forensics','Structured evidence and investigation workflows']
            ] as $item)
                <article class="nx-card"><div class="nx-icon" aria-hidden="true">N</div><h3>{{ $item[0] }}</h3><p>{{ $item[1] }}</p></article>
            @endforeach
        </div>
        <div class="nx-ssr-copy" style="margin-top:34px">
            @if($ar)
                <p>تعمل NEXVARY على ربط طبقات الحماية التي غالبًا ما تُدار منفصلة: أمن الشبكات والأجهزة، كشف التعرض التقني، فحص البيئات الحساسة، إدارة الخصوصية، والتحليل الجنائي الرقمي عند الحاجة. الفكرة ليست إضافة أدوات أكثر فحسب، بل بناء مسار واضح يبدأ من تقليل سطح الهجوم وينتهي بالتحقق والتوثيق واتخاذ قرار قابل للدفاع عنه.</p>
                <p>يعتمد الموقع على عرض الخدمات والأدوات المتاحة بصورة شفافة، مع فصل واضح بين الخصائص التوعوية والوظائف التي تحتاج مختصين أو تحققًا مهنيًا. يمكنك الانتقال إلى صفحة الخدمات لمعرفة مجالات الحماية، أو إلى التطبيقات لمراجعة الأدوات المتاحة، أو إلى أعمالنا للاطلاع على المشاريع المنشورة وحالة توفر كل مشروع.</p>
            @else
                <p>NEXVARY connects security layers that are often managed separately: endpoint and network defense, technical exposure reduction, counter-surveillance awareness, privacy engineering, and digital-forensics workflows when evidence must be preserved and reviewed. The objective is not simply to add more tools. It is to create a clear path from reducing attack surface, through validation and investigation, to reporting that can support an operational decision.</p>
                <p>The platform presents services and applications with a deliberate distinction between awareness features, defensive software, and work that requires professional validation. Visitors can review the security service portfolio, explore privacy-first applications, inspect published projects in Our Work, or contact NEXVARY for a specific technical requirement. This server-rendered public layer also ensures that essential content, navigation, metadata, and structured information remain accessible to search crawlers before JavaScript executes.</p>
            @endif
        </div>
    </section>
</main>
@endsection
