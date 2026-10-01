@extends('public.layout')

@section('title', app()->getLocale() === 'ar' ? 'مشروعات ومنتجات NEXVARY' : 'NEXVARY Projects & Products')
@section('description', app()->getLocale() === 'ar' ? 'استعرض منتجات ومشروعات NEXVARY مع حالة التوفر والتقنيات والروابط المنشورة بوضوح.' : 'Explore NEXVARY products and projects with explicit lifecycle status, technologies and verified public links.')
@section('canonical', 'https://nexvary.com/our-work')

@section('content')
@php($ar = app()->getLocale() === 'ar')
<main class="nx-section nx-page-body">
    <div class="nx-section-title">
        <p>PROJECTS / OUR WORK</p>
        <h1>{{ $ar ? 'منتجات حقيقية بحالة واضحة' : 'Real products with explicit status' }}</h1>
        <p class="nx-lead">{{ $ar ? 'نوضح ما هو متاح وما هو تجريبي أو خاص، ونربط المشروع بموقعه أو مستودعه فقط عندما يكون الرابط عامًا ورسميًا.' : 'We distinguish available products from beta, private-preview and internal work, and expose product or repository links only when they are public and official.' }}</p>
    </div>

    @if($apps->isEmpty())
        <div class="nx-product-list">
            @foreach([
                ['Audio Shield','Privacy Technology','Private Preview','Privacy-aware audio protection and analysis tooling.','/apps'],
                ['Tower Guard','Cybersecurity','Private Preview','Cellular anomaly awareness and structured validation workflows.','/apps'],
                ['SafeScan','Cybersecurity','Available','Zero-storage browser-side file inspection.','/safescan']
            ] as $fallback)
                <article class="nx-card">
                    <div class="nx-project-meta"><span>{{ $fallback[1] }}</span><span>{{ strtoupper($fallback[2]) }}</span></div>
                    <div class="nx-icon" aria-hidden="true">N</div>
                    <h2>{{ $fallback[0] }}</h2>
                    <p>{{ $fallback[3] }}</p>
                    <div class="nx-actions"><a class="nx-btn" href="{{ $fallback[4] }}">{{ $ar ? 'اعرف المزيد' : 'Learn more' }}</a></div>
                </article>
            @endforeach
        </div>
    @else
        <div class="nx-product-list">
            @foreach($apps as $app)
                @php
                    $technologies = is_string($app->technologies ?? null) ? (json_decode($app->technologies, true) ?: []) : ((array) ($app->technologies ?? []));
                    $status = $app->lifecycle_status ?? strtoupper(str_replace('_', ' ', (string) $app->distribution_mode));
                @endphp
                <article class="nx-card">
                    @if($app->icon_url)
                        <img src="{{ $app->icon_url }}" alt="{{ $app->name }} visual" loading="lazy" decoding="async" style="width:64px;height:64px;object-fit:cover;border-radius:14px">
                    @else
                        <div class="nx-icon" aria-hidden="true">N</div>
                    @endif
                    <div class="nx-project-meta">
                        <span>{{ $status }}</span>
                        <span>{{ $app->platform }}</span>
                        @if($app->category)<span>{{ $app->category }}</span>@endif
                        @if($app->version)<span>v{{ $app->version }}</span>@endif
                    </div>
                    <h2><a href="{{ route('our-work.show', ['slug'=>$app->slug]) }}">{{ $app->name }}</a></h2>
                    @if($app->tagline)<p><strong>{{ $app->tagline }}</strong></p>@endif
                    <p>{{ $app->summary }}</p>

                    @if(count($technologies))
                        <div class="nx-project-meta">
                            @foreach(array_slice($technologies, 0, 6) as $technology)<span>{{ $technology }}</span>@endforeach
                        </div>
                    @endif

                    <p class="nx-rich-text" style="font-size:.82rem">
                        {{ $ar ? 'آخر تحديث:' : 'Last updated:' }} {{ substr((string) $app->updated_at, 0, 10) }}
                    </p>

                    <div class="nx-actions">
                        <a class="nx-btn" href="{{ route('our-work.show', ['slug'=>$app->slug]) }}">{{ $ar ? 'اعرف المزيد' : 'Learn more' }}</a>
                        @if($app->website_url)<a class="nx-btn" href="{{ $app->website_url }}" target="_blank" rel="noreferrer">{{ $ar ? 'فتح المنتج' : 'Product URL' }}</a>@endif
                        @if($app->repository_url)<a class="nx-btn" href="{{ $app->repository_url }}" target="_blank" rel="noreferrer">GitHub</a>@endif
                    </div>
                </article>
            @endforeach
        </div>
    @endif

    <div class="nx-ssr-copy" style="margin-top:34px">
        @if($ar)
            <p>لا يعني ظهور المشروع هنا أنه متاح للتنزيل. حالة المشروع المنشورة هي المرجع: Available أو Beta أو In Development أو Private Preview أو Internal. ولا يظهر رابط GitHub إلا إذا كان المستودع عامًا ومقصودًا للنشر.</p>
        @else
            <p>Listing a project does not imply public download access. The published lifecycle state is authoritative: Available, Beta, In Development, Private Preview or Internal. A GitHub link appears only when the repository is intentionally public.</p>
        @endif
    </div>
</main>
@endsection
