# Approved daylight corporate implementation

## Current verified follow-up — approved security-gate image

The owner-approved image with the foreground woman removed and the perimeter security gate added is now the responsive home hero (`hero-security-*`). Application commit `268b5f227b401bc3690bb79be9e229af4ec86c0b` passed Corporate Daylight Verification, Fast UI Gate and the cPanel Ready Bundle on GitHub after the repository became public. The missing axe Playwright dependency was declared in package.json/lock; the homepage no longer downloads the unused 75KB legacy stylesheet.

Latest Lighthouse mobile lab result: performance **99**, accessibility **100**, SEO **100**, LCP **2103.2397ms**, CLS **0**, TBT **0ms**. Environment and exact raw summary are in `lighthouse-mobile.json`. INP and production field results remain unmeasured. Evidence run: https://github.com/nexvary/NEXVARY-Web-Platform/actions/runs/40893659959 ; cPanel bundle: https://github.com/nexvary/NEXVARY-Web-Platform/actions/runs/40893659818 ; Fast UI: https://github.com/nexvary/NEXVARY-Web-Platform/actions/runs/40893659775 .

The previous CI billing/pre-start obstacle below is historical; the latest public-repository runs completed successfully. No financial settings were changed. Production deployment is still pending verified hosting access and a reviewed production backup; changing GitHub visibility does not supply those. The separate label-gated Release Gate was skipped, not claimed as passed.

The remaining sections retain the earlier implementation and diagnostic record.

Base: upstream `main` 32f59d0f48e6864e8ac1c9bc6c04f70f809aa769. The local baseline was verified by Git blob hashes against the GitHub recursive tree (only the empty database/.gitkeep differed locally).

## Scope

Laravel public Blade renderer retained; React/Inertia administration and application workflows retained. Home is a bilingual corporate introduction, without product listings or simulated operational widgets. Header, footer, login branding, favicon, Apple Touch and browser manifest use the owner's supplied seal. A simplified lettermark was not substituted. No News link is shown because no publishable company news was supplied.

`seal-source.png` is the exact supplied seal. A supersampled elliptical alpha mask follows the outside rope border; the source image pixels inside it are preserved and downsampled. Icon sizes: 16, 32, 48, 180, 192, 512; multi-resolution ICO: 16, 32, 48. Nav: 256px WebP displayed at 72px or less. Existing favicon SVG removed. Lucide-derived decorative icon paths use the installed library's ISC license.

Hero source: the owner's supplied `approved-reference.png`, edited with the built-in image-generation tool to remove baked-in website text/controls. Prompt: remove only the top navigation, left headline/copy/buttons/capability cards and bottom focus panel; reconstruct scenery; preserve the approved building, physical NEXVARY reception lettering, daytime sky, green trees, gardens, visitors, fountain, pigeons and marble company flag; no giant facade seal, no year on building/flag, no website UI. The edited image was visually inspected before integration. Generated project source was `/workspace/scratch/3a23eaee2a8b/generated_images/exec-f2446387-e061-4404-999c-4b3368768979.png`; production copies are `public/assets/corporate/hero-*`.

Desktop/mobile responsive widths: 640/960/1536, AVIF/WebP/progressive JPEG. AVIF sizes ~76/150/296 KiB. Browser chooses by `srcset` and `sizes`; preload has matching candidates, hero is eager/high priority. No below-fold full-size imagery. Explicit image dimensions and fixed hero layout prevent image-induced shifts. Apache image caching is one week; Vite CSS/JS remains content-hashed. Responsive sources retain the full architectural composition rather than create a different mobile scene; CSS crops it with the reception centered. Text is native HTML and remains outside the mobile photograph for readability.

## Truthfulness and deployment controls

Official Wyoming search returned a human-verification challenge; entity ID 2026-002118182 was not verified. `config/corporate-identity.php` therefore defaults `registry_verified=false`. The ID and foundingDate are omitted from machine-readable assertions. Owner-supplied date/jurisdiction are labeled as such in visible corporate information. Do not flip the flag without reviewing official evidence.

Architectural scene is explicitly labeled as conceptual and does not establish an office, address, staff, customer, partnership or certification. No registered-agent address is used as the building address. No Amazon partnership/logo is added. The small emblem already present on the approved flag is retained; website identity uses the separately supplied seal exactly.

Public contact processing was reviewed against `routes/public-seo.php`: request fields and keyed IP/user-agent hashes are stored. Privacy text now describes session cookies, form protection, contact records, hosting logs and access/correction/deletion requests. No unimplemented fixed retention guarantee is made.

Repository deployment paths: cPanel ready-bundle workflow and production Coolify workflow; release updater publishes only from verified main. No known production resource UUID, cPanel authenticated deployment session or reviewed current production backup is available in this execution. Production must remain unchanged until the required tests, access and backup are verified. This branch does not trigger production deployment.

## Verification

Local Node 24.22.1: Vite build, TypeScript, manifest validation and static security gate passed. The existing ~963KB Inertia application bundle warns about size; public Blade home does not load it (public interaction JS is ~0.5KB).

Managed preview runs the actual Laravel application using the existing FrankenPHP runtime. An initial missing vendor-path error was fixed by providing the dependencies inside the preview checkout. The desktop English home was visually checked against the approved reference. Local PHP CLI is 8.3.6; locked dependencies require >=8.4.1. The actual managed preview runs PHP 8.5.12. PHPUnit was invoked programmatically in that isolated runtime with the phpunit.xml environment and APP_RUNNING_IN_CONSOLE=true to match the standard CLI test harness: **31 tests, 356 assertions, zero failures/errors/skips**, 2.756318s. The temporary test entry point was removed afterward. No dependency platform check or production security setting was disabled. Reports are included as phpunit-preview.xml/txt. A sitemap test exposed relative-link discovery missing from the internal product catalog after removing homepage products; catalog detail links now use same-origin relative URLs.

`Corporate Daylight Verification` runs PHP tests, frontend build/typecheck/security/manifest checks, desktop/Pixel 9 UI in both languages, same-origin links, responsive image selection, official icons, WCAG axe checks, console errors and mobile Lighthouse lab measurement. Screenshots and JSON/HTML Lighthouse reports are uploaded as `corporate-daylight-evidence`. INP requires field interaction data and is not inferred from Lighthouse TBT. Workflow results are evidence only once completed; source changes alone are not a pass.

## Observed browser results

- Actual desktop Laravel pages inspected in English and Arabic; RTL copy placement was corrected and visually verified.
- Mobile layout checked with actual server-rendered HTML in a 412px layout viewport (same-origin srcdoc QA harness, desktop Chrome; not a real Pixel 9/device test). No horizontal overflow (scrollWidth=clientWidth); mobile source selected hero-640.avif. Native details menu expanded successfully.
- axe-core WCAG 2 A/AA + 2.1 AA on the actual English and Arabic homepage: zero automated violations, 21 passes, one incomplete category requiring manual review (image-backed text contrast). This is not full accessibility certification.
- A temporary nonce-authorized PerformanceObserver in the actual desktop preview measured LCP=172ms, CLS=0, zero long tasks in a warm-cache, unthrottled observation. This is not a mobile Lighthouse score or a production field result. INP not measured. QA instrumentation is removed from the delivered source.
- GitHub Actions runs 40886143244 (Corporate Daylight Verification), 40886143208 (Fast UI Gate) and 40886143214 (cPanel Ready Bundle) failed before starting any step (empty steps, runner_id=0). The API exposes no diagnostic annotation through the available endpoint. The private repository's browser view is not authenticated, so the exact pre-start failure reason remains unresolved. No billing or payment settings were changed.
- Consequently mobile Lighthouse 90+, production LCP/CLS/INP, full Playwright UI matrix, cPanel package runtime and production deployment remain unverified. The PR stays draft.

Arabic contact validation errors and request categories are localized, including a passing invalid-submission regression test. The release updater archive now includes lang/; workflow path filters include translation changes.
