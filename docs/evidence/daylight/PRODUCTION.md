# Corporate daylight production deployment

Runtime revision: `31c4f0b1aa392e020494db4b8e65851498317b9d`.

Verified at https://nexvary.com/ through cPanel and the public website. Hosting is the existing Namecheap Stellar Plus account. The pre-deployment runtime marker was `32f59d0f48e6864e8ac1c9bc6c04f70f809aa769`.

## Deployment and rollback

- Created `NEXVARY-before-daylight-31c4f0b-20261030.zip` (11.7 MB) in the account home, outside `public_html`, containing the current `nexvary_app` and `public_html`. cPanel reported completed compression. A restore drill has not been performed.
- Extracted an explicit allowlist of changed source files, official seal, edited architectural image variants and the CI-built assets from successful cPanel run [40894694642](https://github.com/nexvary/NEXVARY-Web-Platform/actions/runs/40894694642). The full fresh-install bundle was not installed.
- Preserved `.env`, APP_KEY, database, storage/uploads, vendor dependencies and the cPanel front controller. No migrations, seeders or installer ran.
- Retained old configuration and route caches as `config.php.before-daylight` and `routes-v7.php.before-daylight` outside the public directory; Laravel now loads the new configuration and routes.
- Moved the old `favicon.svg` and `nexvary-mark.svg` out of the public directory into the account home. Their former public URLs return 404.
- Applied the repository's `.htaccess` after comparing the previous production contents; only the committed image cache policy differs. AVIF, WebP and favicon responses advertise seven-day browser caching.
- Initial overlay permissions prevented static asset loading. Corrected the ZIP entries to mode 0644, re-extracted, and verified the published bytes against source SHA-256 hashes. Final screenshots below show the corrected state.
- For rollback, restore only changed source/static files from the pre-deployment archive, remove newly added public/config/view files using the overlay manifest, and restore the prior cache filenames. Preserve the current database, environment and uploads. A whole-archive restoration would overwrite live data and must not be used blindly.

## Direct production checks

See [production-http.json](production-http.json) for actual responses and source comparisons. Core pages and internal destinations returned 200; `.env` returned 403. Homepages in both languages reference the official seal and edited hero and contain no old radar. Source hashes match the published favicon, touch icon, manifest, seal and all three edited AVIF sizes. HTML remains private/non-cacheable.

Browser inspection confirmed the English LTR and Arabic RTL layouts, loaded header seal/hero/CSS, and Arabic navigation retaining the locale on the Research page. The only console error observed manually came from the browser extension, not website code. All four read-only automated production checks (English/Arabic, desktop/Pixel 9) passed, including axe WCAG 2 A/AA and WCAG 2.1 AA, image selection, icons, console errors, links and locale navigation. Evidence is tracked in [Corporate Production Verification](https://github.com/nexvary/NEXVARY-Web-Platform/actions/runs/40999552536).

The prior localhost Lighthouse measurement (Performance 99, LCP 2103 ms, CLS 0) is not a production result. The production measurement is recorded in [production-lighthouse.json](production-lighthouse.json): Performance 100, Accessibility 100, SEO 100, LCP 1502.556 ms, CLS 0, TBT 0 ms. Environment: GitHub Ubuntu 24.04, Node 24, Chromium, Lighthouse 12.8.2 default simulated mobile throttling against the public Namecheap site. These are laboratory measurements, not field percentiles. INP requires real interaction/field data and has not been measured.

Company registration verification remains pending; owner-supplied incorporation details are labeled accordingly and the Entity ID is withheld. The hero is visibly labeled as a conceptual architectural visualization.

## Published screenshots

The `corporate-production-evidence` artifact on run 40999552536 contains full-page English and Arabic screenshots for desktop and Pixel 9, the Playwright report, and complete Lighthouse JSON/HTML. The artifact has a 14-day retention period. Manually captured production screenshots are also shown in the delivery conversation.
