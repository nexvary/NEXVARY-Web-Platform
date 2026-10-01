# NEXVARY Web Platform

NEXVARY Web Platform is the company and product platform for NEXVARY security software.

## Stack

- Laravel 13 / PHP 8.3+
- React 19 + TypeScript + Inertia 3
- Tailwind CSS 4
- Vite
- MySQL/MariaDB production-compatible persistence, SQLite for CI
- GitHub Actions release gates

## Public product positioning

NEXVARY is presented as a technology product company developing cybersecurity, privacy, digital-forensics, cloud-connected and AI-assisted security software.

Demonstration telemetry is explicitly labeled as demonstration/simulated data. Product lifecycle states distinguish Available, Beta, In Development, Private Preview and Internal work.

## Projects

Public portfolio metadata is managed through **Projects / Our Work**.

A project may be linked to:

- GitHub repository and branch,
- product URL,
- technologies,
- health check,
- Coolify resource UUID,
- deployment provider,
- public/private lifecycle state.

Repositories may include `nexvary-project.json`, validated against `schema/nexvary-project.schema.json`.

## Deployment architecture

Application projects:

```
GitHub -> CI -> tests -> Coolify -> VPS -> HTTPS -> health check -> Projects metadata -> Our Work
```

Main website while using shared cPanel:

```
GitHub -> CI -> build -> cPanel-ready artifact -> controlled cPanel deployment -> nexvary.com
```

See:

- [Deployment Platform](docs/DEPLOYMENT-PLATFORM.md)
- [AWS Activate readiness](docs/AWS-ACTIVATE-READINESS.md)

## Secrets

Never commit deployment credentials.

Expected placeholders include:

- `GITHUB_TOKEN` — preferably a short-lived GitHub App installation token, otherwise a fine-grained read-only token.
- `COOLIFY_BASE_URL`
- `COOLIFY_API_TOKEN`
- `NEXVARY_DEPLOY_HMAC_SECRET`
- `CPANEL_DEPLOY_KEY` / `DEPLOY_SSH_KEY` when automatic cPanel deployment is enabled.

## Local verification

```bash
composer install
npm ci
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate --force
composer run lint:check
php artisan test
npm run lint:check
npm run types:check
npm run security:gate
npm run build
npm run visual:test
```

Production changes should be merged only after CI, security checks, RTL checks, responsive UI release gates and review.
