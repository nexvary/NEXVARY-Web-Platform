@php
    $category = strtolower((string) ($category ?? ''));
    $platform = strtolower((string) ($platform ?? ''));
    $projectIcon = str_contains($category, 'forensic') ? 'forensics'
        : (str_contains($category, 'privacy') ? 'privacy'
        : (str_contains($category, 'artificial') || str_contains($category, 'ai') ? 'brain'
        : (str_contains($category, 'security') ? 'shield'
        : (str_contains($platform, 'android') || str_contains($platform, 'ios') ? 'mobile'
        : (str_contains($platform, 'windows') ? 'desktop' : 'code')))));
@endphp
@include('public.icon', ['icon'=>$projectIcon])
