<?php

return [
    'risk' => [
        'thresholds' => [
            'guarded' => 20,
            'elevated' => 45,
            'high' => 70,
            'critical' => 85,
        ],
    ],

    'sources' => [
        'application' => [
            'enabled' => true,
            'driver' => 'internal',
        ],
        'waf' => [
            'enabled' => (bool) env('NEXVARY_WAF_ENABLED', false),
            'driver' => env('NEXVARY_WAF_DRIVER', 'coraza'),
            'endpoint' => env('NEXVARY_WAF_ENDPOINT'),
        ],
        'crowd' => [
            'enabled' => (bool) env('NEXVARY_CROWD_ENABLED', false),
            'driver' => env('NEXVARY_CROWD_DRIVER', 'crowdsec'),
            'endpoint' => env('NEXVARY_CROWD_ENDPOINT'),
        ],
        'rf' => [
            'enabled' => (bool) env('NEXVARY_RF_FEED_ENABLED', false),
            'driver' => 'nexvary-rf',
            'endpoint' => env('NEXVARY_RF_FEED_ENDPOINT'),
        ],
    ],

    'retention_days' => (int) env('NEXVARY_SECURITY_EVENT_RETENTION_DAYS', 90),
];
