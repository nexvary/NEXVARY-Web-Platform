<?php

declare(strict_types=1);

use App\Services\Deployment\DeploymentPublisher;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

Route::post('/deployments/publish', function (Request $request, DeploymentPublisher $publisher): JsonResponse {
    $secret = (string) config('deployments.publish_hmac_secret');
    abort_if($secret === '', Response::HTTP_SERVICE_UNAVAILABLE, 'Deployment publishing is not configured.');

    $timestamp = (string) $request->header('X-NEXVARY-Timestamp', '');
    $signature = (string) $request->header('X-NEXVARY-Signature', '');
    abort_unless(ctype_digit($timestamp), Response::HTTP_UNAUTHORIZED, 'Invalid deployment timestamp.');
    abort_if(abs(time() - (int) $timestamp) > 300, Response::HTTP_UNAUTHORIZED, 'Expired deployment request.');

    $expected = hash_hmac('sha256', $timestamp.'.'.$request->getContent(), $secret);
    abort_unless($signature !== '' && hash_equals($expected, $signature), Response::HTTP_UNAUTHORIZED, 'Invalid deployment signature.');

    $payload = $request->validate([
        'manifest' => ['required', 'array'],
        'deployment' => ['required', 'array'],
        'deployment.provider' => ['nullable', 'string', 'max:32'],
        'deployment.environment' => ['nullable', 'string', 'max:48'],
        'deployment.commit_sha' => ['nullable', 'regex:/^[a-fA-F0-9]{7,64}$/'],
        'deployment.remote_deployment_id' => ['nullable', 'string', 'max:160'],
        'deployment.duration_ms' => ['nullable', 'integer', 'min:0'],
        'deployment.started_at' => ['nullable', 'date'],
    ]);

    return response()->json(
        $publisher->publishAfterHealth($payload['manifest'], $payload['deployment'])
    );
})->middleware('throttle:deployment-hook')->name('api.deployments.publish');
