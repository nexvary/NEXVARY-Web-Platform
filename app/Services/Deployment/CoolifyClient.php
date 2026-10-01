<?php

declare(strict_types=1);

namespace App\Services\Deployment;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use RuntimeException;

final class CoolifyClient
{
    public function deploy(string $resourceUuid, bool $force = false): array
    {
        $response = $this->client()->post('/api/v1/deploy', [
            'uuid' => $resourceUuid,
            'force' => $force,
        ])->throw();

        return (array) $response->json();
    }

    public function deployment(string $deploymentUuid): array
    {
        return (array) $this->client()
            ->get('/api/v1/deployments/'.rawurlencode($deploymentUuid))
            ->throw()
            ->json();
    }

    public function rollback(string $resourceUuid, string $commit): array
    {
        return (array) $this->client()
            ->post('/api/v1/applications/'.rawurlencode($resourceUuid).'/rollback', ['commit' => $commit])
            ->throw()
            ->json();
    }

    private function client(): PendingRequest
    {
        $baseUrl = (string) config('deployments.coolify.base_url');
        $token = (string) config('deployments.coolify.token');

        if ($baseUrl === '' || $token === '') {
            throw new RuntimeException('Coolify is not configured. Set COOLIFY_BASE_URL and COOLIFY_API_TOKEN in the deployment environment.');
        }

        return Http::baseUrl($baseUrl)
            ->acceptJson()
            ->asJson()
            ->withToken($token)
            ->timeout(max(3, (int) config('deployments.coolify.timeout', 15)))
            ->retry(2, 250, throw: false);
    }
}
