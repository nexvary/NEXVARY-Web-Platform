@extends('public.layout')

@section('title', ($app->name ?? 'NEXVARY Project').' | NEXVARY')
@section('description', str($app->summary ?? 'Published NEXVARY security project.')->limit(160))
@section('canonical', 'https://nexvary.com/our-work/'.$app->slug)

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
        <section style="margin-top:24px"><div class="nx-section-title"><p>PREVIEW</p><h2>{{ $ar ? 'صور المشروع' : 'Project screenshots' }}</h2></div><div class="nx-screen-grid">@foreach($app->screenshots as $index=>$image)<img src="{{ $image }}" alt="{{ $app->name }} screenshot {{ $index + 1 }}" loading="lazy">@endforeach</div></section>
    @endif

    <div class="nx-actions" style="margin-top:26px">
        @if($app->can_download)<a class="nx-btn nx-btn-primary" href="{{ route('our-work.download', ['slug'=>$app->slug]) }}">{{ $ar ? 'تنزيل الإصدار' : 'Download release' }}</a>
        @elseif($app->distribution_mode === 'request' && filled($app->request_url ?? null))<a class="nx-btn nx-btn-primary" href="{{ $app->request_url }}">{{ $ar ? 'طلب الوصول' : 'Request access' }}</a>
        @else<a class="nx-btn" href="/contact">{{ $ar ? 'استفسر عن المشروع' : 'Ask about this project' }}</a>@endif
        <a class="nx-btn" href="/our-work">{{ $ar ? 'كل المشاريع' : 'All projects' }}</a>
    </div>

    <div class="nx-seo-note">{{ $app->availability_note ?: ($ar ? 'حالة التوفر المعروضة هنا هي الحالة الرسمية الحالية لهذا المشروع.' : 'The availability shown here is the current official distribution status for this project.') }}</div>
</main>
@endsection
