{{-- Original Nexvary SVG artwork. Consistent 24px geometry, meaningful two-tone details.
     Decorative: the adjacent heading/link supplies the accessible name. --}}
<svg class="nc-icon nc-icon-{{ $icon }}" viewBox="0 0 24 24" fill="none" stroke="#69b7ff" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
@switch($icon)
@case('shield')<path fill="#144636" stroke="#66e4ae" d="m12 2 8 3v6c0 7-8 11-8 11S4 18 4 11V5z"/><path stroke="#69b7ff" stroke-width="2" d="m8 12 3 3 5-6"/>@break
@case('brain')<path fill="#182c50" stroke="#69b7ff" d="M12 18V5a3 3 0 0 0-5.8-1 4 4 0 0 0-2.4 6 4 4 0 0 0 .5 7A4 4 0 1 0 12 18Z"/><path fill="#302046" stroke="#c4a0ff" d="M12 5a3 3 0 0 1 5.8-1 4 4 0 0 1 2.4 6 4 4 0 0 1-.5 7A4 4 0 1 1 12 18M16 8c0 2-2 2-4 2m0 5h4"/><path stroke="#69b7ff" d="M8 8c0 2 2 2 4 2m-4 5h4"/>@break
@case('code')<path stroke="#69b7ff" d="m7 6-5 6 5 6m10-12 5 6-5 6"/><path stroke="#ffb376" stroke-width="2" d="m14 3-4 18"/>@break
@case('research')<path fill="#302044" stroke="#c4a0ff" d="M10 3v6L4 19a1.3 1.3 0 0 0 1 2h14a1.3 1.3 0 0 0 1-2L14 9V3"/><path stroke="#edc77f" d="M8 3h8M7 15h10"/><circle fill="#edc77f" stroke="none" cx="10" cy="18" r="1"/><circle fill="#edc77f" stroke="none" cx="13" cy="12" r="1"/>@break
@case('light')<path fill="#43361c" stroke="#edc77f" d="M8 13a6 6 0 1 1 8 0c-1 1-1 2-1 3H9c0-1 0-2-1-3Z"/><path stroke="#c4a0ff" d="M9 19h6m-5 3h4m-2-6v-5m-2-2 2 2 2-2"/>@break
@case('chart')<path stroke="#c5cedb" d="M3 3v18h18"/><path stroke="#69b7ff" stroke-width="3" d="M7 14v3m5-8v8"/><path stroke="#66e4ae" stroke-width="3" d="M17 5v12"/>@break
@case('globe')<circle fill="#172f49" stroke="#69d9eb" cx="12" cy="12" r="10"/><path stroke="#69b7ff" d="M2 12h20M12 2a17 17 0 0 1 0 20 17 17 0 0 1 0-20Z"/><path stroke="#edc77f" d="M5 5h3m8 14h3"/>@break
@case('mail')<rect fill="#172d46" stroke="#69b7ff" x="2" y="4" width="20" height="16" rx="3"/><path stroke="#edc77f" d="m3 6 9 7 9-7"/>@break
@case('building')<path fill="#322c20" stroke="#edc77f" d="m2 8 10-6 10 6Z"/><path stroke="#c5cedb" d="M5 11v7m5-7v7m4-7v7m5-7v7"/><path stroke="#edc77f" d="M3 21h18"/>@break
@case('forensics')<path fill="#172d39" stroke="#69d9eb" d="M4 2h10l4 4v7M4 2v20h9M14 2v5h4M7 7h3m-3 4h6"/><circle stroke="#c5cedb" cx="16" cy="16" r="4"/><path stroke="#69d9eb" d="m19 19 3 3"/>@break
@case('mobile')<rect fill="#172d46" stroke="#69d9eb" x="6" y="2" width="12" height="20" rx="3"/><path stroke="#69b7ff" d="M10 5h4m-3 14h2"/><path stroke="#69d9eb" d="m10 10 2 2 3-3"/>@break
@case('desktop')<rect fill="#172d46" stroke="#c5cedb" x="2" y="3" width="20" height="14" rx="2"/><path stroke="#69b7ff" d="M8 22h8m-4-5v5M6 7h7m-7 4h4"/>@break
@case('cloud')<path fill="#172d46" stroke="#69b7ff" d="M6 19a5 5 0 0 1-1-10 7 7 0 0 1 13-2 6 6 0 0 1 0 12Z"/><path stroke="#e8edf5" d="m9 13 3-3 3 3m-3-3v8"/>@break
@case('privacy')<path fill="#144636" stroke="#66e4ae" d="M5 10h14v12H5zM8 10V6a4 4 0 0 1 8 0v4"/><circle stroke="#edc77f" cx="12" cy="15" r="1.5"/><path stroke="#edc77f" d="M12 17v2"/>@break
@case('document')<path fill="#262b36" stroke="#c5cedb" d="M5 2h9l5 5v15H5zM14 2v6h5"/><path stroke="#edc77f" d="M9 12h6m-6 4h6"/>@break
@default<path stroke="#edc77f" d="M3 12h17"/><path stroke="#69b7ff" d="m14 6 6 6-6 6"/>
@endswitch
</svg>
