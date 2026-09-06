# NEXVARY Web Platform Architecture

## Foundation

Laravel 13 is the application kernel. React 19 + TypeScript is rendered through Inertia 3. Tailwind 4 provides the design system foundation. This follows Laravel's current official React starter-kit direction while keeping backend routing, authorization and security policy inside Laravel.

## Migration strategy

The validated v13.5 PHP/static production site is not discarded. Its visual identity, Arabic RTL behavior, About/social data, SafeScan zero-storage rule, hidden/non-indexed administrative surface, and UI release-gate requirements are migrated feature-by-feature behind tests.

## Security principles

1. A hidden admin path is only an extra obscurity layer, never the primary control.
2. Authentication and authorization are enforced server-side.
3. Admin accounts will require MFA before production release.
4. CSRF protection, encrypted sessions, strict SameSite cookies, HSTS, CSP, rate limiting and audit logs are release requirements.
5. SafeScan must never store uploaded files on NEXVARY infrastructure; browser-side analysis remains the default.
6. Secrets live only in environment variables / GitHub encrypted secrets, never source control.
7. Every production release must pass backend tests, frontend type/build checks, dependency audits and the UI release gate.

## Deployment profiles

- CI: SQLite, PHP 8.4, Node 22.
- Shared hosting compatibility: MySQL/MariaDB, prebuilt frontend assets, no long-running Node process required.
- Enhanced/VPS profile: Redis-compatible cache/queues, workers, scheduled jobs, observability, optional SSR.

## Next milestones

- Complete Laravel Fortify authentication and MFA.
- Port the premium multilingual public UI with Arabic RTL.
- Port About/social settings to database-backed admin configuration.
- Port the zero-storage SafeScan client with hash-only reputation API.
- Add CSP nonce refactor and remove remaining inline-style allowances.
- Add browser-based UI Release Gate at mobile/Android-sized and desktop viewports.
