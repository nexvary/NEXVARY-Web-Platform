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
