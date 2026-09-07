# NEXVARY Web Platform — Stage 160

Stage 081–160 advances the Laravel 13 migration from a foundation into a release-gated security platform. Production remains on the validated legacy release until this branch passes all gates.

## 081–100 — Foundation correction and platform shell

- Fixed the Inertia/Vite TypeScript resolver contract.
- Corrected PHP formatting failures detected by Pint.
- Hardened response headers and HSTS policy.
- Added a unified responsive SiteShell with navigation, footer and seven-language switching.
- Added public Services route and localized session switching.
- Added public robots.txt and sitemap.xml generation with private routes excluded.

## 101–130 — Public experience

- Upgraded the home experience to Stage 160 branding.
- Added the full services experience.
- Rebuilt About on the shared shell.
- Preserved official NEXVARY website, Facebook, email, YouTube and X links.
- Added canonical and description metadata to core public pages.

## 131–150 — Applications, icons and privacy UX

- Added expressive SVG security icons instead of generic placeholder blocks.
- Rebuilt Apps hub with distinct security categories.
- Rebuilt SafeScan explanation around the zero-storage model.
- Added responsive CSS for Android-class phone widths and safe-area behavior.
- Added social/contact cards, mobile nav wrapping and reduced overflow risk.

## 151–160 — Security and release gates

- Hardened locale middleware and RTL persistence.
- Expanded Playwright UI Release Gate to Home, Services, About, Apps and SafeScan.
- Added horizontal-overflow, clipped-control, Arabic RTL and Android tap-target checks.
- Added explicit Laravel auth configuration.
- Added Fortify configuration with email verification, password recovery and two-factor authentication.
- Added MFA-capable User model and database schema for users, sessions and password reset tokens.
- Added private Secure Access login and two-factor challenge views.
- Added login and two-factor rate limiters.
- Added X-Robots-Tag noindex/nofollow/noarchive/nosnippet and no-store caching on admin/auth surfaces.
- Added feature tests for public routes, Arabic RTL, sitemap/robots isolation and private login headers.
- Updated visual CI artifact target to `stage-160-ui-screenshots`.

## Release rule

Stage 160 is not considered passed until GitHub Actions completes Backend, Frontend, Secret Scan and Visual Release Gate successfully. Do not deploy this branch to nexvary.com before that point.
