# NEXVARY Web Platform

Private development repository for the next-generation NEXVARY web platform.

This repository is the controlled migration path from the validated static/PHP v13.5 site to a modern Laravel 13 application. Production changes should be merged only after CI, security checks, RTL checks, responsive UI release gates, and review.

## Target stack

- Laravel 13 / PHP 8.3+
- React 19 + TypeScript + Inertia 3
- Tailwind CSS 4 + accessible component primitives
- Vite
- MySQL/MariaDB production-compatible persistence, SQLite for CI where appropriate
- Redis-compatible cache/queue adapter when the deployment environment supports it
- PHPUnit/Pest-compatible test structure
- GitHub Actions CI/security release gates

## Security posture

Security does not depend on a hidden admin URL. Administrative surfaces must use strong authentication, rate limiting, CSRF protection, secure sessions, authorization policies, audit logging, security headers, and optional MFA. A private/non-indexed admin route may still be retained as an additional low-cost layer.

## Migration rule

The current v13.5 production site remains the fallback reference while the Laravel architecture is built and tested in GitHub. Do not deploy an incomplete migration to `nexvary.com`.


## Product and deployment platform

The public site now distinguishes verified product work from demonstrations and controlled releases. The initial Projects catalog is seeded with Audio Shield, Tower Guard and SafeScan, with lifecycle states rather than unsupported availability claims.

Every NEXVARY repository can describe itself with `nexvary-project.json`. The canonical schema lives at `schema/nexvary-project.schema.json`; validate this repository with:

```bash
npm run manifest:validate
```

Deployment architecture and secret handling are documented in:

- `docs/DEPLOYMENT-ARCHITECTURE.md`
- `docs/DEPLOYMENT-SECRETS.md`

The intended application release path is:

```text
GitHub -> CI -> Coolify -> VPS -> HTTPS Health Check -> signed publish callback -> Our Work
```

The company website retains the existing cPanel-ready build path. `.github/workflows/cpanel-package.yml` creates and smoke-tests the production bundle, but remote cPanel deployment must not be described as automatic until the actual cPanel Git/SSH path is verified and configured.

### Deployment configuration

Application host:

```dotenv
COOLIFY_BASE_URL=
COOLIFY_API_TOKEN=
NEXVARY_DEPLOY_HMAC_SECRET=
NEXVARY_ALLOWED_PROJECT_HOST_SUFFIX=nexvary.com
NEXVARY_DEPLOY_TIMEOUT=15
```

GitHub Actions:

- secret: `COOLIFY_API_TOKEN`
- secret: `NEXVARY_DEPLOY_HMAC_SECRET`
- variable: `COOLIFY_BASE_URL`

Use a GitHub App with narrowly scoped installation permissions for private cross-repository access where possible. Do not commit PATs, SSH keys, cPanel passwords or cloud credentials.

### Verification

Before release, run:

```bash
composer validate --strict
composer install --no-interaction --prefer-dist --no-progress
npm ci
npm run security:gate
npm run manifest:validate
npm run types:check
npm run build
php artisan test
```

Production publication is gated by HTTP 200 health verification. Application rollback targets an earlier healthy commit and does not roll back databases or persistent storage automatically.
