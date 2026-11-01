@extends('public.layout')

@section('title', app()->getLocale() === 'ar' ? 'تطبيقات NEXVARY الأمنية' : 'NEXVARY Security Applications')
@section('description', app()->getLocale() === 'ar' ? 'تطبيقات NEXVARY للحماية الرقمية والخصوصية والفحص المحلي والاستخبارات الأمنية والتحليل الجنائي الرقمي.' : 'Explore NEXVARY security applications for privacy, zero-storage inspection, threat intelligence, cellular awareness and digital forensics.')
@section('canonical', 'https://nexvary.com/apps')

@section('content')
@php($ar = app()->getLocale() === 'ar')
<main id="main-content" class="nx-section nx-page-body">
    <div class="nx-section-title">@include('public.icon', ['icon'=>'mobile'])<p>{{ $ar ? 'منظومة التطبيقات' : 'APPLICATION ECOSYSTEM' }}</p><h1>{{ $ar ? 'تطبيقات أمنية بواجهة موحدة' : 'Security applications, one platform' }}</h1><p class="nx-lead">{{ $ar ? 'مجموعة تطبيقات دفاعية وخصوصية مصممة لتقديم معلومات أو إجراءات واضحة دون ادعاءات غير واقعية.' : 'A set of defensive and privacy-first applications designed to provide useful signals, clear workflows and controlled release paths.' }}</p></div>
    <div class="nx-grid">
        @foreach([
            ['Audio Shield','PRIVACY','Privacy-aware audio protection and awareness tooling.','تقنيات لحماية الخصوصية الصوتية والتوعية بمخاطرها.','privacy'],
            ['Tower Guard','CELLULAR','Cellular anomaly awareness with professional validation guidance.','التوعية بشذوذ الشبكات الخلوية مع إرشادات التحقق المتخصص.','mobile'],
            ['SafeScan','ZERO-STORAGE','Browser-side zero-storage static inspection with optional hash reputation.','فحص محلي داخل المتصفح دون تخزين الملفات، مع تحقق اختياري من سمعة البصمة.','shield'],
            ['Threat Intelligence','INTEL','Curated security intelligence and advisory surfaces.','معلومات أمنية وتنبيهات مختارة ومنظمة.','cloud'],
            ['AI Intelligence','AI','Focused AI security and technology intelligence.','معلومات متخصصة في أمن الذكاء الاصطناعي وتقنياته.','brain'],
            ['DFIR Lab','FORENSICS','Structured digital-forensics workflows and investigation support.','مسارات منظمة للتحليل الجنائي الرقمي ودعم التحقيق.','forensics']
        ] as $product)
            <article class="nx-card nx-app-card">@include('public.icon', ['icon'=>$product[4]])<div class="nx-card-tag">{{ $product[1] }}</div><h2>{{ $product[0] }}</h2><p>{{ $ar ? $product[3] : $product[2] }}</p>@if($product[0] === 'SafeScan')<a class="nx-card-link" href="/safescan{{ $ar ? '?lang=ar' : '' }}">{{ $ar ? 'فتح SafeScan' : 'Open SafeScan' }} @include('public.icon', ['icon'=>'arrow'])</a>@else<span class="nx-card-status">{{ $ar ? 'إصدار مقيّد' : 'CONTROLLED RELEASE' }}</span>@endif</article>
        @endforeach
    </div>
    <div class="nx-space-34 nx-ssr-copy">
        @if($ar)
            <p>تم تصميم منظومة تطبيقات NEXVARY بحيث لا تختلط وظائف الخصوصية مع أدوات الفحص أو الاستخبارات. بعض الأدوات تعمل محليًا في المتصفح لتقليل مشاركة البيانات، وبعضها يقدم طبقة تحليل أو توعية تساعد المستخدم على فهم المخاطر قبل طلب فحص متخصص. كل تطبيق يوضح ما يفعله وما لا يفعله، حتى لا تتحول الواجهة الأمنية إلى وعود غير قابلة للتحقق.</p>
            <p>يمكنك تجربة SafeScan كنموذج لنهج التخزين الصفري، ثم الانتقال إلى صفحة أعمالنا لمراجعة المشاريع المنشورة وحالة التوزيع لكل مشروع. بعض التطبيقات قد تكون للعرض فقط، وبعضها متاح حسب الطلب أو للاستخدام الداخلي، بينما تتوفر التطبيقات القابلة للتنزيل فقط عندما يجتاز الإصدار اختبارات الأمان والجودة والتوافق.</p>
        @else
            <p>The NEXVARY application ecosystem is structured so that privacy tools, inspection utilities, intelligence surfaces, and forensic workflows do not blur into one another. Some tools run locally in the browser to reduce unnecessary data transfer, while others provide analysis or awareness that helps an operator understand a signal before requesting professional validation. Each application is expected to state what it does, what data it handles, and where its technical limits are.</p>
            <p>SafeScan demonstrates the zero-storage approach by keeping primary inspection in the browser. Other products focus on cellular awareness, audio privacy, security intelligence, or repeatable DFIR workflows. Published projects and their availability status can be reviewed in Our Work. Downloads are exposed only when a project is explicitly configured for distribution and has passed the release process appropriate to that product.</p>
        @endif
    </div>
</main>
@endsection
