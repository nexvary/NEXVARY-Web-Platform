@extends('public.layout')
@php
    $ar = app()->getLocale() === 'ar';
    $disciplines = [
        'research' => ['Research & Innovation', 'البحث والابتكار', 'We explore digital systems through methodical experiments, source review and documented findings. Research is a process of testing ideas, not a claim of commercial readiness.', 'ندرس الأنظمة الرقمية من خلال تجارب منهجية ومراجعة المصدر وتوثيق النتائج. البحث عملية لاختبار الأفكار، وليس ادعاءً بجاهزية منتج تجاري.'],
        'cybersecurity' => ['Cybersecurity', 'الأمن السيبراني', 'Our engineering direction emphasizes defensive software, explicit authorization, reduced attack surface and traceable analysis. Software signals require context and validation before decisions are made.', 'يركّز توجهنا الهندسي على البرمجيات الدفاعية والتفويض الواضح وتقليل سطح الهجوم والتحليل القابل للتتبع. تحتاج إشارات البرمجيات إلى السياق والتحقق قبل اتخاذ القرارات.'],
        'artificial-intelligence' => ['Artificial Intelligence', 'الذكاء الاصطناعي', 'We explore AI-assisted workflows with clear boundaries for data handling, model limitations and human review. A generated result is assessed against the requirements of its intended use.', 'نبحث مسارات عمل مدعومة بالذكاء الاصطناعي، مع حدود واضحة لمعالجة البيانات وقيود النماذج والمراجعة البشرية. تُقيَّم النتائج المولّدة وفق متطلبات الاستخدام المقصود.'],
    ];
    [$en,$arabic,$copy,$copyAr] = $disciplines[$discipline];
@endphp
@section('title', ($ar ? $arabic.' | نيكسفاري' : $en.' | Nexvary Inc'))
@section('description', $ar ? $copyAr : 'Nexvary Inc research and engineering: '.$en.'. Explore our methods, capability boundaries and approach to practical validation.')
@section('canonical', 'https://nexvary.com/'.$discipline)
@section('content')
<main id="main-content" class="nc-section">@include('public.icon', ['icon'=>['research'=>'research','cybersecurity'=>'shield','artificial-intelligence'=>'brain'][$discipline]])<p class="nc-eyebrow">{{ $ar ? 'مجالاتنا' : 'OUR DISCIPLINES' }}</p><h1>{{ $ar ? $arabic : $en }}</h1><div class="nx-ssr-copy"><p>{{ $ar ? $copyAr : $copy }}</p><h2>{{ $ar ? 'منهجنا في التحقق' : 'Our approach to validation' }}</h2><p>{{ $ar ? 'نحدد المشكلة ونراجع الافتراضات، ثم نختبر التنفيذ ونسجل ما تم التحقق منه وما بقي غير محسوم. نميّز بوضوح بين التجربة البحثية والقدرة المنفذة والمنتج المتاح. لا ننشر أرقام أداء أو ادعاءات تفوق دون أدلة قابلة للمراجعة.' : 'We define the problem, review assumptions, test the implementation and record what has been verified and what remains unresolved. Research experiments, implemented capabilities and available products are distinguished explicitly. Performance figures and superiority claims require reviewable evidence.' }}</p></div><a class="nc-button" href="/contact{{ $ar ? '?lang=ar' : '' }}">{{ $ar ? 'ناقش معنا مشروعك' : 'Discuss your project' }}</a></main>
@endsection
