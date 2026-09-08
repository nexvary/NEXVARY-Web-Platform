<?php

namespace App\Services\Security\Sources;

use App\Services\Security\Contracts\SecuritySignalSource;
use Illuminate\Support\Facades\Http;
use Throwable;

abstract class JsonHttpSecuritySource implements SecuritySignalSource
{
    public function __construct(
        protected readonly string $sourceKey,
        protected readonly bool $sourceEnabled,
        protected readonly ?string $endpoint,
        protected readonly ?string $token = null,
        protected readonly int $timeout = 4,
    ) {}

    public function key(): string
    {
        return $this->sourceKey;
    }

    public function enabled(): bool
    {
        return $this->sourceEnabled && filled($this->endpoint);
    }

    public function signals(): array
    {
        if (! $this->enabled()) {
            return [];
        }

        try {
            $request = Http::acceptJson()->timeout($this->timeout)->retry(1, 150, throw: false);
            if (filled($this->token)) {
                $request = $request->withToken($this->token);
            }

            $response = $request->get((string) $this->endpoint);
            if (! $response->successful()) {
                return [];
            }

            return $this->mapPayload($response->json());
        } catch (Throwable $exception) {
            report($exception);

            return [];
        }
    }

    public function health(): array
    {
        return [
            'key' => $this->key(),
            'enabled' => $this->enabled(),
            'configured' => filled($this->endpoint),
        ];
    }

    /** @param mixed $payload
     *  @return array<int, array<string, mixed>>
     */
    abstract protected function mapPayload(mixed $payload): array;
}
