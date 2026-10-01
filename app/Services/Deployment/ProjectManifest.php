<?php

declare(strict_types=1);

namespace App\Services\Deployment;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

final class ProjectManifest
{
    /**
     * @param  array<string, mixed>  $manifest
     * @return array<string, mixed>
     */
    public function validate(array $manifest): array
    {
        return Validator::make($manifest, [
            'name' => ['required', 'string', 'min:2', 'max:180'],
            'slug' => ['required', 'string', 'max:120', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/'],
            'description' => ['required', 'string', 'min:20', 'max:2000'],
            'category' => ['required', 'string', 'min:2', 'max:100'],
            'status' => ['required', Rule::in(['Available', 'Beta', 'In Development', 'Private Preview', 'Internal'])],
            'visibility' => ['required', Rule::in(['public', 'private', 'internal'])],
            'website' => ['nullable', 'url:https', 'max:500'],
            'repository' => ['nullable', 'url:https', 'regex:/^https:\/\/github\.com\//i', 'max:500'],
            'branch' => ['nullable', 'string', 'max:120'],
            'technologies' => ['required', 'array', 'min:1', 'max:30'],
            'technologies.*' => ['string', 'min:1', 'max:80', 'distinct'],
            'image' => ['nullable', 'string', 'max:500'],
            'healthCheck' => ['nullable', 'url:https', 'max:500'],
            'deploymentMode' => ['nullable', Rule::in(['deploy_publish', 'deploy_only'])],
            'publishToWebsite' => ['required', 'boolean'],
        ])->validate();
    }
}
