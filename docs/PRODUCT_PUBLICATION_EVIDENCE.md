# Product Publication Evidence

Review date: 2026-10-26. This register is a preliminary source audit, **not a production-readiness certification**. Confirm current branch HEAD, code paths, CI, releases, licenses and screenshots before publication.

## NEXVARY Net Scan
- Repository: `nexvary/NEXVARY-NetScan` (README calls it NetScan; commercial label: NEXVARY Net Scan).
- README: Windows desktop local-network device discovery. Goals include one-click discovery, IP/hostname/MAC/vendor hint/reachability, refresh/search/copy/export. **Goals are not verified implemented features.**
- README identifies development branch `dev/foundation`; latest SHA not yet independently verified.
- Publication recommendation: Active Development; do not claim working exports, installer, release or supported features without source and build evidence.

## NEXVARY Guardian Studio (formerly Recorder Studio)
- Repository: `nexvary/NEXVARY-Recorder-Studio`.
- README documents Android 10+ (minSdk 29), target Android 15 (35), MediaProjection screen recording with microphone, PCM/WAV voice studio, video audio replacement and prerecorded-to-live RTMP/RTMPS via RootEncoder.
- These are **README-documented implementations**, pending code/CI/device verification. Android internal playback audio mixing is explicitly identified as incomplete in README.
- Guardian brand transition, SOS, trusted contacts, child/family safety, country profiles, multi-recipient automatic delivery and background camera behavior: **not verified in this audit**; do not advertise as available.
- Publication recommendation: Active Development; no debug APK download, no subscriptions or guarantee of emergency response.

## Web Platform publication guardrails
- Repository: `nexvary/NEXVARY-Web-Platform`.
- README describes Laravel 13, React 19, TypeScript, Inertia 3 migration; existing Projects catalog seeded with Audio Shield, Tower Guard and SafeScan; `nexvary-project.json` schema.
- Extend existing catalog architecture rather than introducing a parallel catalog. Preserve current static/PHP v13.5 production until CI/security/RTL/responsive checks pass.
- Required verification: current SHA for each reviewed branch; source feature paths; release and license review; screenshots; build, tests, responsive RTL/LTR, links, SEO and deployment safety.

## Claims deliberately withheld
- Available downloads, production-ready status, completed Guardian rename, verified emergency functions, security guarantees, customer counts, partnerships, incorporation and release dates.

## Publication gate
- No public product feature may be marked Available without implementation evidence and applicable validation. No deployment before successful production build and review.

## NEXVARY FoodGuard — additional product candidate (2026-10-26)
- Repository: `nexvary/NEXVARY-FoodGuard` (commercial display name: NEXVARY FoodGuard).
- README states Stage 550 / `0.5.0-stage550`; Kotlin/Jetpack Compose Android application, 220-item food catalog, camera/gallery selection, local photo-quality metrics, manual spoilage checklist, local assessment history, multilingual resources and text sharing.
- README states local-first privacy posture, with no INTERNET manifest permission. Verify manifest/source before publishing this claim as confirmed.
- Crucial limitation: **no validated trained food-spoilage classifier**. Photo metrics and educational spoilage guidance do not prove food safety; hazards can be invisible.
- Build and release readiness: README describes unit tests, lint and debug assembly; **latest Actions run and distributable signed release not checked**.
- Recommendation: include in Featured Products only as **Active Development** after source and CI audit. Do not advertise automatic food safety detection, laboratory accuracy or a public APK download.

## Approved public messaging for FoodGuard
- Publish as **Active Development**, without waiting for completion of planned features.
- Arabic public copy: «تعمل NEXVARY على تطوير تطبيق FoodGuard لأجهزة Android لمساعدة المستخدمين على التعرف على مؤشرات فساد الأطعمة، والاطلاع على إرشادات التخزين والفحص الأولي. يتضمن المشروع دليلًا للأطعمة وأدوات تقييم مساعدة، ولا يحل محل الفحص المخبري أو يضمن سلامة الغذاء.»
- Required closing sentence, verbatim: **«والمشروع قيد إضافة مميزات أخرى.»**
- English equivalent: **“Additional features are currently in development.”**
- Do not show an unverified download link or suggest automated spoilage classification is validated.

## Owner publication requirements (2026-10-26)
- Do not publish any outbound links in public product cards or pages: no GitHub, APK, store, beta, release or external URLs without explicit future approval. Internal site navigation may be used to open product detail pages.
- Show authentic, current product screenshots: one primary image plus a gallery of 3–6 relevant screenshots when assets exist and are verified against the current UI.
- For Guardian Studio, Net Scan and FoodGuard, inspect repository assets and avoid fictional or outdated screenshots. If insufficient authentic screenshots exist, omit the gallery until real captures are available; illustrations must be clearly labeled.
- Product descriptions should end with: «والمشروع قيد إضافة مميزات أخرى.»
