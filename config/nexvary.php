<?php

return [
    'admin_prefix' => env('NEXVARY_ADMIN_PREFIX', 'secure-control'),
    'csp_report_only' => (bool) env('NEXVARY_CSP_REPORT_ONLY', false),
    'languages' => ['en', 'ar', 'tr', 'ru', 'de', 'it', 'es'],
    'social' => [
        'website' => 'https://nexvary.com/',
        'facebook' => 'https://www.facebook.com/share/14p9krEn5ij/',
        'email' => 'info@nexvary.com',
        'youtube' => 'https://www.youtube.com/@NexvaryInc',
        'x' => 'https://x.com/Nexvary',
    ],
];
