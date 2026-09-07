@extends('public.layout')

@section('title', app()->getLocale() === 'ar' ? 'أعمال ومشاريع NEXVARY' : 'NEXVARY Projects and Our Work')
@section('description', app()->getLocale() === 'ar' ? 'استعرض المشاريع والتطبيقات المنشورة من NEXVARY وحالة توفر كل مشروع للتنزيل أو العرض أو الطلب أو الاستخدام الداخلي.' : 'Browse published NEXVARY projects and applications with clear availability status for download, showcase, request-only or internal use.')
@section('canonical', 'https://nexvary.com/our-work')

@section('content')
@php($ar = app()->getLocale() === 'ar')
<main class="nx-section nx-page-body">
    <div class="nx-section-title"><p>OUR WORK</p><h1>{{ $ar ? 'مشاريع منشورة بحالة توفر واضحة' : 'Published projects with clear availability' }}</h1><p class="nx-lead">{{ $ar ? 'كل مشروع منشور يوضح حالته بدل إظهار زر تنزيل غير متاح أو رابط ميت.' : 'Every published project states its availability instead of exposing unavailable downloads or dead actions.' }}</p></div>

    @if($apps->isEmpty())
        <div class="nx-card"><h2>{{ $ar ? 'لا توجد مشاريع منشورة حاليًا' : 'No published projects yet' }}</h2><p>{{ $ar ? 'سيظهر هنا كل مشروع بعد اجتياز بوابة الإصدار وتحديد سياسة التوزيع.' : 'Projects will appear here after they pass the release gate and receive an explicit distribution policy.' }}</p></div>
    @else
        <div class="nx-product-list">
            @foreach($apps as $app)
                <article class="nx-card">
                    <div class="nx-project-meta"><span>{{ $app->platform }}</span>@if($app->category)<span>{{ $app->category }}</span>@endif @if($app->version)<span>v{{ $app->version }}</span>@endif</div>
                    <h2><a href="{{ route('our-work.show', ['slug'=>$app->slug]) }}">{{ $app->name }}</a></h2>
                    @if($app->tagline)<p><strong>{{ $app->tagline }}</strong></p>@endif
                    <p>{{ $app->summary }}</p>
                    <div class="nx-actions"><a class="nx-btn" href="{{ route('our-work.show', ['slug'=>$app->slug]) }}">{{ $ar ? 'عرض المشروع' : 'View project' }}</a></div>
                    <span class="nx-card-status">{{ strtoupper(str_replace('_',' ', $app->distribution_mode)) }}</span>
                </article>
            @endforeach
        </div>
    @endif

    <div class="nx-ssr-copy" style="margin-top:34px">@if($ar)<p>صفحة أعمالنا ليست متجر تنزيلات تلقائيًا. بعض المشاريع مخصصة للعرض، وبعضها متاح حسب الطلب، وبعضها للاستخدام الداخلي أو ما زال في مرحلة قادمة. عندما يكون التنزيل عامًا فعلًا يظهر ذلك بوضوح داخل صفحة المشروع، مع معلومات الإصدار وحالة التوفر والملاحظات المرتبطة بالتوزيع.</p><p>هذا الأسلوب يمنع الروابط الميتة ويجعل حالة كل مشروع مفهومة للمستخدم ومحركات البحث في الوقت نفسه. كما يسمح بعرض المشاريع التي تمثل خبرة NEXVARY أو اتجاهها التقني دون الادعاء بأن كل مشروع متاح للجمهور. يمكن استخدام صفحة التواصل للاستفسار عن مشروع محدد أو طلب معلومات إضافية حول التوفر.</p>@else<p>Our Work is not treated as an automatic download store. Some projects are published as showcases, some are request-only, some remain internal, and others may be listed as coming soon. A public download appears only when the project is explicitly configured for distribution. This keeps availability accurate and avoids buttons that lead to missing files, inaccessible pages, or ambiguous release status.</p><p>The same structure helps search crawlers understand the relationship between the portfolio index and each published project page. Internal links connect the catalog to individual project records, while the sitemap includes only surfaces intended for public discovery. Visitors who need access to a request-only project can use the contact page instead of encountering a dead download path.</p>@endif</div>
</main>
@endsection
