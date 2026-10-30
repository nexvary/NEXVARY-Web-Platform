<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Support\Collection;

final class DevelopmentShowcase
{
    public static function projects(): Collection
    {
        $ar = app()->getLocale() === 'ar';

        return collect(config('project-showcase', []))->map(function (array $project, string $slug) use ($ar): object {
            [$category, $summary] = $project[$ar ? 'ar' : 'en'];

            return (object) [
                'slug' => $slug, 'name' => $project['name'], 'platform' => $project['platform'],
                'category' => $category, 'summary' => $summary, 'description' => null,
                'lifecycle_status' => $ar ? 'قيد التطوير' : 'In Development',
                'availability_note' => $ar
                    ? 'هذا المشروع قيد العمل والتطوير. الصفحة للتعريف فقط، ولا تتوفر روابط تحميل أو إصدار تجاري معلن هنا.'
                    : 'Work on this project is ongoing. This is an introduction only; no downloads or commercial release are offered here.',
                'distribution_mode' => 'showcase', 'download_enabled' => false,
                'can_download' => false, 'apk_url' => null, 'request_url' => null,
                'website_url' => null, 'repository_url' => null, 'icon_url' => null,
                'version' => null, 'tagline' => null, 'visibility' => null,
                'technologies' => [], 'features' => [], 'screenshots' => [],
                'updated_at' => null, 'is_development_showcase' => true,
            ];
        });
    }
}
