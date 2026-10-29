{{-- Lucide icon paths (ISC license); decorative, colored consistently in corporate.css. --}}
<svg class="nc-icon nc-icon-{{ $icon }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
@switch($icon)
@case('shield')<path d="M12 22s8-4 8-11V5l-8-3-8 3v6c0 7 8 11 8 11z"/><path d="m9 12 2 2 4-4"/>@break
@case('brain')<path d="M12 18V5a3 3 0 0 0-5.8-1 4 4 0 0 0-2.4 6 4 4 0 0 0 .5 7A4 4 0 1 0 12 18ZM12 5a3 3 0 0 1 5.8-1 4 4 0 0 1 2.4 6 4 4 0 0 1-.5 7A4 4 0 1 1 12 18"/><path d="M8 8c0 2 2 2 4 2m4-2c0 2-2 2-4 2M8 15h4m0 0h4"/>@break
@case('code')<path d="m16 18 6-6-6-6M8 6l-6 6 6 6m6-14-4 16"/>@break
@case('research')<path d="M9 3h6m-5 0v6l-6 10a1.3 1.3 0 0 0 1 2h14a1.3 1.3 0 0 0 1-2L14 9V3M8 15h8"/>@break
@case('light')<path d="M9 18h6m-6 3h6m-7-8a6 6 0 1 1 8 0c-1 1-1 2-1 3H9c0-1 0-2-1-3Z"/>@break
@case('chart')<path d="M3 3v18h18M7 14v3m5-8v8m5-13v13"/>@break
@case('globe')<circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a17 17 0 0 1 0 20 17 17 0 0 1 0-20Z"/>@break
@case('mail')<rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 6-10 7L2 6"/>@break
@case('building')<path d="m3 9 9-7 9 7M3 22h18M5 10v9m5-9v9m4-9v9m5-9v9M2 9h20"/>@break
@default<path d="m9 5 7 7-7 7M3 12h13"/>
@endswitch
</svg>
