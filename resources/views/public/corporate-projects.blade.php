@php($ar = app()->getLocale() === 'ar')
<div class="nx-product-wall">
@foreach(config('corporate.projects') as $project)
@php($text = $project[$ar ? 'ar' : 'en'])
<article class="nx-product-niche" id="{{ $project['slug'] }}">
    <div class="nx-product-top"><span class="nx-ordinal">0{{ $loop->iteration }}</span><span class="nx-status">{{ $ar ? 'قيد التطوير' : $project['status'] }}</span></div>
    <p class="nx-eyebrow">{{ $text['category'] }}</p><h3><a href="/our-work/{{ $project['slug'] }}{{ $ar ? '?lang=ar' : '' }}">{{ $project['name'] }}</a></h3><p>{{ $text['summary'] }}</p>
    @unless($compact ?? false)<ul>@foreach($text['features'] as $feature)<li>{{ $feature }}</li>@endforeach</ul><p class="nx-boundary">{{ $text['boundary'] }}</p>@endunless
    <div class="nx-product-bottom"><span>{{ $project['platform'] }}</span><a class="nx-text-link" href="/our-work/{{ $project['slug'] }}{{ $ar ? '?lang=ar' : '' }}">{{ $ar ? 'تفاصيل المشروع' : 'Project details' }} <span aria-hidden="true">↗</span></a></div>
    <p class="nx-source-date">{{ $ar ? 'آخر تحديث للمصدر' : 'Source updated' }} <time datetime="{{ $project['date'] }}">{{ $project['date'] }}</time></p>
</article>
@endforeach
</div>
