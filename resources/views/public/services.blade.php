@extends('public.layout')

@section('title', app()->getLocale() === 'ar' ? 'خدمات NEXVARY الأمنية المتقدمة' : 'NEXVARY Security Services')
@section('description', app()->getLocale() === 'ar' ? 'خدمات NEXVARY في مكافحة التجسس الفني والأمن السيبراني والخصوصية والتحليل الجنائي الرقمي وتقنيات الحماية المادية.' : 'NEXVARY security services cover TSCM, cybersecurity, privacy, digital forensics, physical security technology and security intelligence.')
@section('canonical', 'https://nexvary.com/services')

@section('content')
@php($ar = app()->getLocale() === 'ar')
<main class="nx-section nx-page-body">
    <div class="nx-section-title"><p>SECURITY SERVICES</p><h1>{{ $ar ? 'حماية تقنية متعددة التخصصات' : 'Multi-disciplinary technical protection' }}</h1><p class="nx-lead">{{ $ar ? 'خدمات مصممة لتقليل التعرض الرقمي والمادي، مع فصل واضح بين التوعية، الفحص الفني، والتحقق المهني.' : 'Services designed to reduce digital and physical exposure with a clear separation between awareness, technical inspection and professional validation.' }}</p></div>
    <div class="nx-grid">
        @foreach([
            ['01','TSCM & Counter-Surveillance','Technical inspections for offices, vehicles, rooms, devices, networks and RF environments.'],
            ['02','Cybersecurity','Assessment, hardening, vulnerability reduction, incident response and security architecture.'],
            ['03','Digital Privacy','Privacy-first workflows, exposure reduction and defensive technology for sensitive users and teams.'],
            ['04','Digital Forensics','Structured evidence review, investigation workflows and defensible reporting.'],
            ['05','Physical Security Technology','CCTV, access control, alarms, secure networking and sensor integration.'],
            ['06','Security Intelligence','Curated threat, breach and AI-security intelligence surfaces for decision support.']
        ] as $service)
            <article class="nx-card nx-service-card"><div class="nx-icon">{{ $service[0] }}</div><h2>{{ $service[1] }}</h2><p>{{ $service[2] }}</p><span class="nx-card-status">NEXVARY · CONTROLLED DELIVERY</span></article>
        @endforeach
    </div>
    <div class="nx-ssr-copy" style="margin-top:34px">
        @if($ar)
            <p>تُبنى خدمات NEXVARY حول مبدأ تقليل المخاطر قبل أن تتحول إلى حادث. يبدأ العمل بفهم البيئة والأصول الحساسة، ثم تحديد التعرض المحتمل عبر الشبكات والأجهزة والاتصالات اللاسلكية والمواقع المادية. بعد ذلك يتم اختيار مستوى الفحص أو الحماية المناسب دون خلط بين أدوات التوعية وبين عمليات التحقق التي تتطلب مختصًا وتجهيزات احترافية.</p>
            <p>في الأمن السيبراني نركز على تقليل سطح الهجوم، تقوية الإعدادات، مراجعة نقاط الضعف، والاستجابة المنظمة للحوادث. وفي مكافحة التجسس الفني نركز على الوعي بالمخاطر، فحص البيئات الحساسة، والتحقق الفني المنهجي. أما التحليل الجنائي الرقمي فيعتمد على الحفاظ على الأدلة، توثيق الخطوات، وإنتاج نتائج قابلة للمراجعة بدل القرارات غير الموثقة.</p>
        @else
            <p>NEXVARY services are organized around reducing risk before it becomes an incident. Engagements begin with the environment and the assets that matter, then examine likely exposure across networks, endpoints, wireless communications, physical spaces, and operational processes. The service model deliberately separates awareness tooling from professional validation so that customers can understand what a software signal means and when specialist inspection or evidence handling is required.</p>
            <p>Cybersecurity work focuses on attack-surface reduction, hardening, vulnerability review, incident response, and architecture. Counter-surveillance work focuses on sensitive environments, radio-frequency awareness, and structured technical inspection. Digital-forensics work emphasizes evidence preservation, repeatable analysis, and defensible reporting. Physical-security technology connects cameras, access control, alarms, secure networking, and sensors into a coordinated layer rather than isolated devices.</p>
        @endif
    </div>
</main>
@endsection
