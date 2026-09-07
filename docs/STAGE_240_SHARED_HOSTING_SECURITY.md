# Stage 240 — Shared Hosting Security Hardening

This wave intentionally assumes **Namecheap/cPanel-style shared hosting**. It does not require Cloudflare, a VPS, Redis, long-running workers, Docker, or root access.

## Implemented in this wave

- Per-request CSP nonces wired through Laravel Vite.
- Removed `unsafe-inline` from CSP.
- Restricted browser network connections to same-origin by default.
- Expanded browser hardening headers and private-surface noindex/no-store behavior.
- Explicit least-privilege admin authorization (`is_admin`) in addition to authentication and email verification.
- Layered named throttles for admin, locale changes, health endpoint, Fortify login, and two-factor challenges.
- Fortify two-factor authentication foundation remains enabled with confirmation and password confirmation.
- Privacy-preserving, tamper-evident admin audit trail using HMAC hash chaining; raw IP addresses and user-agent strings are not stored.
- `/.well-known/security.txt` disclosure contact.
- Apache `.htaccess` hardening for shared hosting: directory listing disabled, server signature disabled, sensitive filename access denied, and Laravel front-controller routing retained.
- Existing dependency audits, secret scan, backend tests, TypeScript/build checks, and Playwright visual gates remain mandatory.

## Shared-hosting deployment constraints

Production should use PHP 8.3+ with required Laravel extensions, HTTPS only, a database user limited to the application database, and the web document root pointed at `public/` whenever cPanel permits it. The Laravel application root, `.env`, `vendor/`, `storage/`, and configuration files must not be directly web-accessible.

No persistent queue worker is assumed. Queue work must use `sync`, database + cron processing, or a host-supported scheduled command. Front-end assets are built in GitHub Actions before deployment, so Node.js is not required on production hosting.

## Admin security operating rule

A hidden admin path is only an additional obscurity layer. Access still requires a verified authenticated account with the explicit admin flag, Fortify controls, throttling, secure sessions, and audit logging. MFA enrollment is required before the production administrator is considered release-ready.

## Next hardening gates

1. Add admin MFA enrollment/recovery UI and then enforce confirmed MFA on all private administration routes.
2. Add passkey/WebAuthn enrollment as an optional stronger second factor when browser/platform compatibility is verified.
3. Add audit-chain verification command and security dashboard summaries.
4. Commit reproducible Composer/npm lockfiles and switch CI to deterministic installs.
5. Add static-analysis checks that are available for the private repository without requiring paid GitHub Advanced Security.
6. Add encrypted backup/restore runbook suitable for cPanel databases and application configuration.

Security is layered risk reduction; this project must never claim to be impossible to compromise.
