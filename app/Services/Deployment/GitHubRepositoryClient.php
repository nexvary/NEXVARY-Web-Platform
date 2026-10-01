<?php

declare(strict_types=1);

namespace App\Services\Deployment;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use RuntimeException;

final class GitHubRepositoryClient
{
    /** @return array{owner:string,repo:string} */
    public function identify(string $repositoryUrl): array
    {
        $parts = parse_url($repositoryUrl);
        $host = strtolower((string) ($parts['host'] ?? ''));
        $path = trim((string) ($parts['path'] ?? ''), '/');

        if ($host !== 'github.com') {
            throw new RuntimeException('Only github.com repository URLs are supported.');
        }

        $segments = array_values(array_filter(explode('/', $path)));
        if (count($segments) !== 2) {
            throw new RuntimeException('Repository URL must use https://github.com/OWNER/REPOSITORY.');
        }

        return ['owner' => $segments[0], 'repo' => preg_replace('/\.git$/i', '', $segments[1]) ?: $segments[1]];
    }

    /** @return array{commit_sha:string,manifest:array<string,mixed>|null} */
    public function inspect(string $repositoryUrl, string $branch): array
    {
        $repo = $this->identify($repositoryUrl);
        $base = '/repos/'.rawurlencode($repo['owner']).'/'.rawurlencode($repo['repo']);

        $branchPayload = (array) $this->client()
            ->get($base.'/branches/'.rawurlencode($branch))
            ->throw()
            ->json();

        $sha = (string) data_get($branchPayload, 'commit.sha');
        if ($sha === '') {
            throw new RuntimeException('GitHub did not return a commit SHA for the selected branch.');
        }

        $manifest = null;
        $response = $this->client()->get($base.'/contents/nexvary-project.json', ['ref' => $branch]);
        if ($response->successful()) {
            $encoded = str_replace("\n", '', (string) data_get($response->json(), 'content', ''));
            $decoded = base64_decode($encoded, true);
            if ($decoded === false) {
                throw new RuntimeException('GitHub returned an invalid project manifest encoding.');
            }
            $manifest = json_decode($decoded, true, flags: JSON_THROW_ON_ERROR);
            if (! is_array($manifest)) {
                throw new RuntimeException('nexvary-project.json must contain a JSON object.');
            }
        } elseif ($response->status() !== 404) {
            $response->throw();
        }

        return ['commit_sha' => $sha, 'manifest' => $manifest];
    }

    private function client(): PendingRequest
    {
        $request = Http::baseUrl((string) config('github_projects.api_url'))
            ->acceptJson()
            ->withHeaders([
                'X-GitHub-Api-Version' => '2022-11-28',
                'User-Agent' => 'NEXVARY-Web-Platform',
            ])
            ->timeout(max(3, (int) config('github_projects.timeout', 12)));

        $token = (string) config('github_projects.token');
        return $token !== '' ? $request->withToken($token) : $request;
    }
}
