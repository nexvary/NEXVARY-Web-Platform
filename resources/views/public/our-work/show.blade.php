@php
    $safeProjectTitle = str((string) ($app->name ?? 'NEXVARY Project'))->squish()->limit(49, '');
    $safeProjectDescription = str((string) ($app->summary ?? 'Published NEXVARY security project.'))->squish()->limit(155, '');
    $projectSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'SoftwareApplication',
        'name' => (string) $app->name,
        'description' => (string) $app->summary,
        'url' => 'https://nexvary.com/our-work/'.$app->slug,
        'applicationCategory' => (string) ($app->category ?: 'SecurityApplication'),
        'operatingSystem' => (string) $app->platform,
        'softwareVersion' => (string) ($app->version ?: 'Current'),
        'publisher' => ['@id' => 'https://nexvary.com/#organization'],
    ];
@endphp
@extends('public.layout')

@section('title', $safeProjectTitle.' | NEXVARY')
@section('description', $safeProjectDescription)
@section('canonical', 'https://nexvary.com/our-work/'.$app->slug)

@push('head')
<script type="application/ld+json" nonce="{{ Vite::cspNonce() }}">{!! json_encode($projectSchema, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT) !!}</script>
@endpush

@section('content')
@php($ar = app()->getLocale() === 'ar')
<main class="nx-section nx-page-body">
    <div class="nx-section-title"><p>PROJECT</p><h1>{{ $app->name }}</h1>@if($app->tagline)<p class="nx-lead">{{ $app->tagline }}</p>@endif</div>
    <div class="nx-project-meta"><span>{{ $app->platform }}</span>@if($app->category)<span>{{ $app->category }}</span>@endif @if($app->version)<span>v{{ $app->version }}</span>@endif <span>{{ strtoupper(str_replace('_',' ', $app->distribution_mode)) }}</span></div>
    <article class="nx-card"><p>{{ $app->summary }}</p>@if($app->description)<div class="nx-rich-text">{{ $app->description }}</div>@endif</article>

    @if(!empty($app->features))
        <section style="margin-top:24px"><div class="nx-section-title"><p>FEATURES</p><h2>{{ $ar ? 'القدرات الرئيسية' : 'Key capabilities' }}</h2></div><div class="nx-grid">@foreach($app->features as $feature)<article class="nx-card"><div class="nx-icon" aria-hidden="true">N</div><h3>{{ $feature }}</h3></article>@endforeach</div></section>
    @endif

    @if(!empty($app->screenshots))
        <section style="margin-top:24px"><div class="nx-section-title"><p>PREVIEW</p><h2>{{ $ar ? 'صور المشروع' : 'Project screenshots' }}</h2></div><div class="nx-screen-grid">@foreach($app->screenshots as $index=>$image)<img src="{{ $image }}" alt="{{ $app->name }} screenshot {{ $index + 1 }}" loading="lazy" decoding="async">@endforeach</div></section>
    @endif

    <div class="nx-ssr-copy" style="margin-top:30px">
        @if($ar)
            <p>تعرض هذه الصفحة المعلومات المنشورة رسميًا عن المشروع وحالة توفره الحالية. قد يكون المشروع متاحًا للتنزيل، مخصصًا للعرض، متاحًا حسب الطلب، للاستخدام الداخلي، أو في مرحلة قادمة. لا يظهر زر تنزيل عام إلا عندما تكون سياسة التوزيع مضبوطة على التنزيل ويكون ملف الإصدار منشورًا بصورة صريحة.</p>
            <p>معلومات الإصدار والمنصة والخصائص المعروضة هنا تساعد على التحقق من هوية المشروع قبل استخدامه. عند توفر ملف عام يُفضّل التحقق من معلومات الإصدار والبصمة المنشورة إن وجدت. أما المشروعات الداخلية أو المقيدة فلا ينبغي الاعتماد على روابط غير رسمية أو نسخ معاد توزيعها خارج قنوات NEXVARY.</p>
        @else
            <p>This page presents the officially published information for the project and its current availability. A project may be publicly downloadable, published as a showcase, available by request, restricted to internal use, or listed as coming soon. A public download action is exposed only when the distribution policy explicitly permits it and a release file has been configured for public delivery.</p>
            <p>The platform, version, capabilities, and availability shown here help visitors verify the identity and intended distribution of the project before use. When a public release includes integrity information, users should verify the published version and checksum where applicable. Internal or restricted projects should not be obtained from unofficial mirrors or redistributed copies that fall outside the controlled NEXVARY release path.</p>
        @endif
    </div>

    <div class="nx-actions" style="margin-top:26px">
        @if($app->can_download)<a class="nx-btn nx-btn-primary" href="{{ route('our-work.download', ['slug'=>$app->slug]) }}">{{ $ar ? 'تنزيل الإصدار' : 'Download release' }}</a>
        @elseif($app->distribution_mode === 'request' && filled($app->request_url ?? null))<a class="nx-btn nx-btn-primary" href="{{ $app->request_url }}" rel="noreferrer">{{ $ar ? 'طلب الوصول' : 'Request access' }}</a>
        @else<a class="nx-btn" href="/contact">{{ $ar ? 'استفسر عن المشروع' : 'Ask about this project' }}</a>@endif
        <a class="nx-btn" href="/our-work">{{ $ar ? 'كل المشاريع' : 'All projects' }}</a>
    </div>

    <div class="nx-seo-note">{{ $app->availability_note ?: ($ar ? 'حالة التوفر المعروضة هنا هي الحالة الرسمية الحالية لهذا المشروع.' : 'The availability shown here is the current official distribution status for this project.') }}</div>
</main>
@endsection
