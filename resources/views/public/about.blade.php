@extends('public.layout')

@section('title', app()->getLocale() === 'ar' ? 'عن NEXVARY — شركة منتجات تقنية أمنية' : 'About NEXVARY — Security Technology Product Company')
@section('description', app()->getLocale() === 'ar' ? 'NEXVARY شركة تقنية تطور منتجات للأمن السيبراني والخصوصية والتحليل الجنائي الرقمي والتطبيقات المتصلة بالسحابة والأمن المدعوم بالذكاء الاصطناعي.' : 'NEXVARY is a technology company developing cybersecurity, privacy, digital forensics, cloud-connected and AI-assisted security software products.')
@section('canonical', 'https://nexvary.com/about')

@section('content')
@php($ar = app()->getLocale() === 'ar')
<main class="nx-section nx-page-body">
    <section class="nx-about-hero">
        <div>
            <p class="nx-kicker">ABOUT NEXVARY</p>
            <h1>{{ $ar ? 'شركة منتجات تقنية تركز على البرمجيات الأمنية.' : 'A technology product company focused on security software.' }}</h1>
            <p class="nx-lead">{{ $ar ? 'تطوّر NEXVARY منتجات برمجية خاصة بها للأمن السيبراني والخصوصية والتحليل الجنائي الرقمي والتطبيقات المتصلة بالسحابة وقدرات أمنية مدعومة بالذكاء الاصطناعي.' : 'NEXVARY is a technology company developing cybersecurity, privacy, digital forensics, and cloud-connected software products. Our focus is building practical security applications and platforms that combine monitoring, analysis, automation, secure cloud services, and AI-assisted capabilities.' }}</p>
        </div>
        <div class="nx-about-emblem" aria-hidden="true"><span>N</span></div>
    </section>

    <div class="nx-grid nx-about-grid">
        @foreach([
            ['Cybersecurity','Defensive software, attack-surface reduction, monitoring and secure operational workflows.'],
            ['Privacy Technology','Products and architecture designed to minimize unnecessary data exposure.'],
            ['Digital Forensics','Repeatable investigation and evidence-oriented analysis workflows.'],
            ['Cloud-Connected Applications','Secure APIs, authentication, storage, databases, monitoring and deployment-ready backends.'],
            ['AI-Assisted Security','AI-supported analysis and automation behind explicit security and data boundaries.'],
            ['Secure Infrastructure','Health checks, auditability, deployment controls and least-privilege integration patterns.']
        ] as $pillar)
            <article class="nx-card"><div class="nx-icon" aria-hidden="true">N</div><h2>{{ $pillar[0] }}</h2><p>{{ $pillar[1] }}</p></article>
        @endforeach
    </div>

    <section class="nx-card" style="margin-top:28px">
        <p class="nx-kicker">CLOUD-READY BY DESIGN</p>
        <h2>{{ $ar ? 'قابلية التوسع دون ادعاءات سحابية غير مثبتة' : 'Built for scalable cloud infrastructure without unsupported cloud claims' }}</h2>
        <p class="nx-rich-text">{{ $ar ? 'تُصمم أنظمة NEXVARY لتعمل مع واجهات API سحابية آمنة، المصادقة، تخزين الكائنات، قواعد البيانات، المراقبة، معالجة الأحداث، الإشعارات، الأتمتة ومسارات النشر. وعندما لا يكون مزود بعينه مستخدمًا فعليًا في الإنتاج، نصف البنية بأنها جاهزة للسحابة بدل الادعاء باستخدام ذلك المزود.' : 'NEXVARY systems are designed around secure cloud APIs, authentication, object storage, databases, monitoring, event processing, notifications, automation and deployment workflows. Where a particular cloud provider is not yet an active production dependency, we describe the architecture as cloud-ready rather than claiming provider usage that has not been verified.' }}</p>
    </section>

    <div class="nx-ssr-copy" style="margin-top:34px">
        @if($ar)
            <p>نحن نفرّق بين المنتج البرمجي، والنسخة التجريبية، والأداة الداخلية، والخدمة المهنية. حالة كل مشروع يجب أن تظهر بوضوح، ولا نعتبر وجود مستودع أو شاشة تجريبية دليلًا على أن المنتج متاح للعامة.</p>
            <p>كما لا نقدم كل إشارة أمنية باعتبارها حكمًا نهائيًا. البرمجيات تساعد على الكشف والتحليل والتوثيق والأتمتة، بينما بعض الحالات تحتاج تحققًا مهنيًا أو معدات ميدانية أو إجراءات حفظ أدلة.</p>
        @else
            <p>We distinguish between a software product, a beta, a private preview, an internal tool and a professional service. Repository existence or a demonstration interface is not treated as proof of public availability; project status is communicated explicitly.</p>
            <p>Security signals are also not presented as absolute conclusions. Software can help detect, analyze, document and automate, while some situations still require professional validation, field equipment or formal evidence-handling procedures.</p>
        @endif
    </div>

    <section class="nx-contact-panel">
        <div><p class="nx-kicker">CONNECT</p><h2>{{ $ar ? 'روابط NEXVARY الرسمية' : 'Official NEXVARY channels' }}</h2></div>
        <div class="nx-social-grid">
            <a href="https://nexvary.com/"><strong>Website</strong><small>nexvary.com</small></a>
            <a href="https://github.com/nexvary" target="_blank" rel="noreferrer"><strong>GitHub</strong><small>github.com/nexvary</small></a>
            <a href="https://www.facebook.com/share/14p9krEn5ij/" target="_blank" rel="noreferrer"><strong>Facebook</strong><small>Official page</small></a>
            <a href="mailto:info@nexvary.com"><strong>Email</strong><small>info@nexvary.com</small></a>
            <a href="https://www.youtube.com/@NexvaryInc" target="_blank" rel="noreferrer"><strong>YouTube</strong><small>@NexvaryInc</small></a>
            <a href="https://x.com/Nexvary" target="_blank" rel="noreferrer"><strong>X</strong><small>@Nexvary</small></a>
        </div>
    </section>
</main>
@endsection
