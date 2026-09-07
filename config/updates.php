<?php

declare(strict_types=1);

return [
    'current_version' => env('NEXVARY_VERSION', '0.1.0-dev'),
    'channel' => env('NEXVARY_UPDATE_CHANNEL', 'stable'),
    'manifest_url' => env(
        'NEXVARY_UPDATE_MANIFEST_URL',
        'https://api.github.com/repos/nexvary/NEXVARY-Web-Platform/contents/update-manifest.json?ref=update-channel'
    ),
    'github_token' => env('NEXVARY_GITHUB_TOKEN'),
    'allowed_hosts' => array_values(array_filter(array_map(
        'trim',
        explode(',', (string) env(
            'NEXVARY_UPDATE_ALLOWED_HOSTS',
            'api.github.com,github.com,release-assets.githubusercontent.com,objects.githubusercontent.com,nexvary.com,www.nexvary.com'
        ))
    ))),
    'timeout' => (int) env('NEXVARY_UPDATE_TIMEOUT', 30),
    'max_archive_mb' => (int) env('NEXVARY_UPDATE_MAX_ARCHIVE_MB', 220),
];
