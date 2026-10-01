<?php

declare(strict_types=1);

use App\Services\Deployment\CoolifyClient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use RuntimeException;

Route::prefix(config('nexvary.admin_prefix'))
    ->middleware(['auth', 'verified', 'admin', 'throttle:admin', 'audit.admin'])
    ->group(function (): void {
        Route::get('/deployments', function () {
            $projects = Schema::hasTable('portfolio_apps')
                ? DB::table('portfolio_apps')->orderBy('name')->get([
                    'id', 'slug', 'name', 'repository_url', 'repository_branch', 'website_url', 'health_url',
                    'coolify_resource_uuid', 'deploy_provider', 'publish_to_website', 'is_published',
                    'lifecycle_status', 'last_deployed_at', 'last_commit_sha',
                ])
                : collect();

            $runs = Schema::hasTable('deployment_runs')
                ? DB::table('deployment_runs')
                    ->join('portfolio_apps', 'portfolio_apps.id', '=', 'deployment_runs.portfolio_app_id')
                    ->latest('deployment_runs.id')
                    ->limit(100)
                    ->get([
                        'deployment_runs.id', 'deployment_runs.portfolio_app_id', 'portfolio_apps.name as project_name',
                        'deployment_runs.provider', 'deployment_runs.deployment_mode', 'deployment_runs.environment',
                        'deployment_runs.repository_url', 'deployment_runs.branch', 'deployment_runs.commit_sha',
                        'deployment_runs.domain', 'deployment_runs.remote_deployment_id', 'deployment_runs.status',
                        'deployment_runs.health_status', 'deployment_runs.log_summary', 'deployment_runs.duration_ms',
                        'deployment_runs.started_at', 'deployment_runs.completed_at', 'deployment_runs.created_at',
                    ])
                : collect();

            return Inertia::render('admin/deployments', ['projects' => $projects, 'runs' => $runs]);
        })->name('admin.deployments');

        Route::post('/deployments/projects/{project}/deploy', function (Request $request, int $project, CoolifyClient $coolify): RedirectResponse {
            abort_unless(in_array($request->user()?->role, ['admin', 'owner'], true), 403);
            $validated = $request->validate([
                'deployment_mode' => ['required', Rule::in(['deploy_publish', 'deploy_only'])],
                'force' => ['nullable', 'boolean'],
            ]);

            $app = DB::table('portfolio_apps')->where('id', $project)->first();
            abort_if($app === null, 404);
            abort_if(blank($app->coolify_resource_uuid), 422, 'Set the Coolify resource UUID before deploying.');

            $now = now();
            $runId = DB::table('deployment_runs')->insertGetId([
                'portfolio_app_id' => $app->id,
                'provider' => 'coolify',
                'deployment_mode' => $validated['deployment_mode'],
                'environment' => 'production',
                'repository_url' => $app->repository_url,
                'branch' => $app->repository_branch,
                'commit_sha' => $app->last_commit_sha,
                'domain' => $app->website_url,
                'status' => 'Queued',
                'started_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            try {
                $result = $coolify->deploy((string) $app->coolify_resource_uuid, (bool) ($validated['force'] ?? false));
                $remoteId = data_get($result, 'deployment_uuid')
                    ?? data_get($result, 'deployments.0.deployment_uuid')
                    ?? data_get($result, 'uuid');

                DB::table('deployment_runs')->where('id', $runId)->update([
                    'remote_deployment_id' => is_scalar($remoteId) ? (string) $remoteId : null,
                    'status' => 'Deploying',
                    'log_summary' => 'Deployment accepted by Coolify. Refresh status before publishing.',
                    'updated_at' => now(),
                ]);
            } catch (Throwable $exception) {
                report($exception);
                DB::table('deployment_runs')->where('id', $runId)->update([
                    'status' => 'Failed',
                    'log_summary' => str($exception->getMessage())->limit(800),
                    'completed_at' => now(),
                    'updated_at' => now(),
                ]);

                return back()->withErrors(['deployment' => 'Coolify deployment failed to start. Review server logs and configuration.']);
            }

            return back()->with('success', 'Deployment queued in Coolify.');
        })->name('admin.deployments.deploy');

        Route::post('/deployments/{run}/refresh', function (Request $request, int $run, CoolifyClient $coolify): RedirectResponse {
            abort_unless(in_array($request->user()?->role, ['admin', 'owner'], true), 403);
            $row = DB::table('deployment_runs')
                ->join('portfolio_apps', 'portfolio_apps.id', '=', 'deployment_runs.portfolio_app_id')
                ->where('deployment_runs.id', $run)
                ->first([
                    'deployment_runs.*', 'portfolio_apps.health_url', 'portfolio_apps.website_url',
                    'portfolio_apps.publish_to_website', 'portfolio_apps.coolify_resource_uuid',
                ]);
            abort_if($row === null, 404);
            abort_if(blank($row->remote_deployment_id), 422, 'This deployment has no remote deployment ID.');

            try {
                $remote = $coolify->deployment((string) $row->remote_deployment_id);
                $rawStatus = strtolower((string) (data_get($remote, 'status') ?? data_get($remote, 'deployment.status') ?? 'unknown'));
                $failed = str_contains($rawStatus, 'fail') || str_contains($rawStatus, 'error') || str_contains($rawStatus, 'cancel');
                $finished = $failed || str_contains($rawStatus, 'finish') || str_contains($rawStatus, 'success') || str_contains($rawStatus, 'complete');

                $status = $failed ? 'Failed' : 'Deploying';
                $healthStatus = null;
                $completedAt = null;

                if ($finished && ! $failed) {
                    $healthUrl = (string) ($row->health_url ?: $row->website_url ?: '');
                    if ($healthUrl === '') {
                        throw new RuntimeException('No health URL is configured for this project.');
                    }

                    $parts = parse_url($healthUrl);
                    $host = strtolower((string) ($parts['host'] ?? ''));
                    $suffix = strtolower((string) config('deployments.allowed_host_suffix', 'nexvary.com'));
                    $allowed = $host === $suffix || str_ends_with($host, '.'.$suffix);
                    if (($parts['scheme'] ?? null) !== 'https' || ! $allowed) {
                        throw new RuntimeException('Health URL must be HTTPS on the configured NEXVARY domain suffix.');
                    }

                    $health = Http::timeout(10)->get($healthUrl);
                    if ($health->status() !== 200) {
                        $status = 'Failed';
                        $healthStatus = 'HTTP '.$health->status();
                    } else {
                        $status = 'Healthy';
                        $healthStatus = 'HTTP 200';
                        if ($row->deployment_mode === 'deploy_publish' && (bool) $row->publish_to_website) {
                            DB::table('portfolio_apps')->where('id', $row->portfolio_app_id)->update([
                                'is_published' => true,
                                'review_status' => 'approved',
                                'last_deployed_at' => now(),
                                'last_commit_sha' => $row->commit_sha,
                                'published_at' => DB::raw('COALESCE(published_at, CURRENT_TIMESTAMP)'),
                                'updated_at' => now(),
                            ]);
                        }
                    }
                    $completedAt = now();
                }

                DB::table('deployment_runs')->where('id', $run)->update([
                    'status' => $status,
                    'health_status' => $healthStatus,
                    'completed_at' => $completedAt,
                    'log_summary' => 'Coolify status: '.$rawStatus.($healthStatus ? '; health: '.$healthStatus : ''),
                    'updated_at' => now(),
                ]);
            } catch (Throwable $exception) {
                report($exception);

                return back()->withErrors(['deployment' => 'Could not refresh deployment status safely: '.$exception->getMessage()]);
            }

            return back()->with('success', 'Deployment status refreshed.');
        })->name('admin.deployments.refresh');

        Route::post('/deployments/{run}/retry', function (Request $request, int $run, CoolifyClient $coolify): RedirectResponse {
            abort_unless(in_array($request->user()?->role, ['admin', 'owner'], true), 403);
            $row = DB::table('deployment_runs')
                ->join('portfolio_apps', 'portfolio_apps.id', '=', 'deployment_runs.portfolio_app_id')
                ->where('deployment_runs.id', $run)
                ->first(['deployment_runs.*', 'portfolio_apps.coolify_resource_uuid']);
            abort_if($row === null, 404);
            abort_if(blank($row->coolify_resource_uuid), 422, 'Coolify resource UUID is missing.');

            try {
                $result = $coolify->deploy((string) $row->coolify_resource_uuid, true);
                $remoteId = data_get($result, 'deployment_uuid') ?? data_get($result, 'deployments.0.deployment_uuid') ?? data_get($result, 'uuid');
                DB::table('deployment_runs')->where('id', $run)->update([
                    'remote_deployment_id' => is_scalar($remoteId) ? (string) $remoteId : null,
                    'status' => 'Deploying',
                    'health_status' => null,
                    'completed_at' => null,
                    'started_at' => now(),
                    'log_summary' => 'Retry accepted by Coolify.',
                    'updated_at' => now(),
                ]);
            } catch (Throwable $exception) {
                report($exception);

                return back()->withErrors(['deployment' => 'Retry could not be queued safely.']);
            }

            return back()->with('success', 'Deployment retry queued.');
        })->name('admin.deployments.retry');

        Route::post('/deployments/{run}/rollback', function (Request $request, int $run, CoolifyClient $coolify): RedirectResponse {
            abort_unless($request->user()?->role === 'owner', 403);
            $row = DB::table('deployment_runs')
                ->join('portfolio_apps', 'portfolio_apps.id', '=', 'deployment_runs.portfolio_app_id')
                ->where('deployment_runs.id', $run)
                ->first(['deployment_runs.*', 'portfolio_apps.coolify_resource_uuid']);
            abort_if($row === null, 404);
            abort_if(blank($row->coolify_resource_uuid), 422, 'Coolify resource UUID is missing.');

            $previous = DB::table('deployment_runs')
                ->where('portfolio_app_id', $row->portfolio_app_id)
                ->where('status', 'Healthy')
                ->whereNotNull('commit_sha')
                ->where('id', '<', $row->id)
                ->latest('id')
                ->first(['commit_sha']);
            abort_if($previous === null, 422, 'No earlier healthy commit is available for rollback.');

            try {
                $result = $coolify->rollback((string) $row->coolify_resource_uuid, (string) $previous->commit_sha);
                $remoteId = data_get($result, 'deployment_uuid') ?? data_get($result, 'uuid');

                DB::table('deployment_runs')->insert([
                    'portfolio_app_id' => $row->portfolio_app_id,
                    'provider' => 'coolify',
                    'deployment_mode' => 'deploy_only',
                    'environment' => $row->environment,
                    'repository_url' => $row->repository_url,
                    'branch' => $row->branch,
                    'commit_sha' => $previous->commit_sha,
                    'domain' => $row->domain,
                    'remote_deployment_id' => is_scalar($remoteId) ? (string) $remoteId : null,
                    'status' => 'Rolled Back',
                    'log_summary' => 'Rollback requested to the previous healthy application commit. Database and persistent storage are not rolled back by this action.',
                    'started_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } catch (Throwable $exception) {
                report($exception);

                return back()->withErrors(['deployment' => 'Rollback could not be queued safely.']);
            }

            return back()->with('success', 'Rollback requested. Refresh and verify health before treating it as complete.');
        })->name('admin.deployments.rollback');

        Route::post('/deployments/projects/{project}/publication', function (Request $request, int $project): RedirectResponse {
            abort_unless(in_array($request->user()?->role, ['admin', 'owner'], true), 403);
            $validated = $request->validate(['published' => ['required', 'boolean']]);

            DB::table('portfolio_apps')->where('id', $project)->update([
                'is_published' => $validated['published'],
                'published_at' => $validated['published'] ? now() : null,
                'updated_at' => now(),
            ]);

            return back()->with('success', $validated['published'] ? 'Project published.' : 'Project unpublished.');
        })->name('admin.deployments.publication');
    });
