# Development-only project showcases

Published runtime: `57145927923f2dbead88bf3c55adbf3ac505e00c`.

The owner requested public introductions for NEXVARY Recorder Studio, NEXVARY FoodGuard, NEXVARY PromptForge, NEXVARY RealEstate AI OS and NEXVARY Meta Security, all marked **In Development / قيد التطوير**, without downloads. Project names and restrained descriptions were checked against their source READMEs. Private repository URLs, build artifacts and internal implementation details are not exposed.

- Introductions and detail pages appear under `/our-work`; the corporate homepage remains an introduction to the company.
- `config/project-showcase.php` is the curated content source. `DevelopmentShowcase` produces localized, non-downloadable view data. Existing database entries remain intact; curated entries take precedence for the five reserved slugs.
- The download route refuses these five slugs even if a stale database row enables delivery. No APK, release, repository, external product or request-access links are offered on their cards or detail pages. Contact and internal navigation remain available.
- Detail pages omit invented version numbers and include `creativeWorkStatus: In Development` in structured data. All five pages are in the sitemap.
- Before the seven-file source overlay, cPanel created `NEXVARY-before-project-showcase-5714592.zip` (17.25 MB) outside the public directory. Environment, key, database, uploads and vendor packages were preserved; no installer or migrations ran. Source files were deployed with 0644 permissions. Compiled Blade views were moved to recoverable trash so the new templates were regenerated; no user files or uploaded content were removed.

Validation:
- [Corporate Daylight Verification 41001042971](https://github.com/nexvary/NEXVARY-Web-Platform/actions/runs/41001042971): build/type checks/security/manifest, backend suite including stale-download override regression, and desktop/Pixel 9 bilingual browser checks passed.
- [Corporate Production Verification 41001344992](https://github.com/nexvary/NEXVARY-Web-Platform/actions/runs/41001344992): live desktop/Pixel 9 English/Arabic checks passed, including all five detail pages, no download/repository controls, and HTTP 404 for all five download endpoints. Screenshots and reports are in the 14-day `corporate-production-evidence` artifact. After clearing compiled views, direct browser inspection additionally confirmed the new heading, availability notes and localized Learn More URLs.
- Direct inspection of the Arabic FoodGuard detail confirmed its in-development status, educational safety boundary, and contact-only action.

This publishes website introductions, not software releases or a claim of commercial readiness.
