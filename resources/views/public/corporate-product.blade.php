@extends('public.layout')
@section('title', $project['name'].' | Nexvary Inc')
@section('description', app()->getLocale() === 'ar' ? $project['ar']['category'].' — قدرات منفذة وحالة التطوير وحدود التحقق ضمن منتجات نيكسفاري البرمجية.' : $project['en']['category'].' — implemented capabilities, development status and validation boundaries from Nexvary Inc.')
@section('canonical', 'https://nexvary.com/our-work/'.$project['slug'])
@section('content')
@php
    $ar = app()->getLocale() === 'ar';
    $text = $project[$ar ? 'ar' : 'en'];
@endphp
<main id="main-content" class="nx-page-body"><section class="nx-section nx-page-intro"><p class="nx-kicker">{{ $text['category'] }}</p><h1>{{ $project['name'] }}</h1><div class="nx-project-meta"><span class="nx-status">{{ $ar ? 'قيد التطوير' : 'In Development' }}</span><span>{{ $project['platform'] }}</span></div><p class="nx-lead">{{ $text['summary'] }}</p></section><section class="nx-section nx-section-first"><div class="nx-editorial"><div><h2>{{ $ar ? 'ما تم تنفيذه' : 'Implemented in source' }}</h2><ul class="nx-feature-list">@foreach($text['features'] as $feature)<li>{{ $feature }}</li>@endforeach</ul></div><div class="nx-inset"><h2>{{ $ar ? 'حدود التحقق' : 'Validation boundaries' }}</h2><p>{{ $text['boundary'] }}</p><p>{{ $ar ? 'آخر تحديث للمصدر:' : 'Source updated:' }} <time datetime="{{ $project['date'] }}">{{ $project['date'] }}</time></p><p>{{ $ar ? 'مراجعة المحتوى:' : 'Content reviewed:' }} <time datetime="2026-10-29">2026-10-29</time></p></div></div>
@if($project['image'])<figure class="nx-real-screen"><img src="{{ $project['image'] }}" width="1366" height="768" loading="lazy" decoding="async" alt="{{ $ar ? 'لقطة حقيقية من واجهة تطوير NEXVARY-MR تعرض لوحة العمل' : 'Real NEXVARY-MR development screenshot showing the workstation dashboard' }}"><figcaption>{{ $ar ? 'واجهة تطوير حقيقية من المستودع، بتاريخ ٢٣ أكتوبر ٢٠٢٦؛ البيانات الظاهرة للاختبار.' : 'Actual development interface from the repository, October 23, 2026; displayed records are test data.' }}</figcaption></figure>@else<p class="nx-editorial-note">{{ $ar ? 'لا نعرض صورة مولّدة على أنها لقطة من التطبيق. ستضاف صور حقيقية عند توفر لقطة مناسبة للنشر.' : 'Generated artwork is not presented as an application screenshot. Suitable verified application images will be added when available.' }}</p>@endif
<a href="/our-work{{ $ar ? '?lang=ar' : '' }}" class="nx-text-link">{{ $ar ? 'استعرض كل المنتجات' : 'Explore all products' }} <span aria-hidden="true">↗</span></a></section></main>
@endsection
