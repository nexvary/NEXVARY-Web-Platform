<?php

return [
    'token' => env('GITHUB_TOKEN'),
    'api_url' => rtrim((string) env('GITHUB_API_URL', 'https://api.github.com'), '/'),
    'timeout' => (int) env('GITHUB_API_TIMEOUT', 12),
];
