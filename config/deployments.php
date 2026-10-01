<?php

return [
    'coolify' => [
        'base_url' => rtrim((string) env('COOLIFY_BASE_URL', ''), '/'),
        'token' => env('COOLIFY_API_TOKEN'),
        'timeout' => (int) env('NEXVARY_DEPLOY_TIMEOUT', 15),
    ],
    'publish_hmac_secret' => env('NEXVARY_DEPLOY_HMAC_SECRET'),
    'allowed_host_suffix' => ltrim((string) env('NEXVARY_ALLOWED_PROJECT_HOST_SUFFIX', 'nexvary.com'), '.'),
];
