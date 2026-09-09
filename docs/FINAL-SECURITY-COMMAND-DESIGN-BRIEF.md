# NEXVARY Web Platform — Final Security Command Design Brief

Status: **MANDATORY visual direction**

This brief supersedes the current weak homepage visual direction. The approved target is a premium **Cyber Security / Intelligence / Command Center** experience inspired by the stronger reference shown by the product owner, while remaining uniquely NEXVARY.

## 1. Global visual direction

- Deep navy / near-black base.
- Electric blue primary accent, restrained silver/white highlights.
- High-end security-operations feel; no generic SaaS landing-page look.
- Dense but controlled information hierarchy.
- Avoid dead space, flat decorative graphics, cartoonish globes, oversized headlines, weak icons, and passive sections.

## 2. Hero section — mandatory

### Headline
- Keep `SECURITY BEYOND THE VISIBLE` or approved equivalent.
- Reduce current headline scale by roughly **20–30%**.
- Maximum 2–3 visual lines.
- Emphasize only one key word using electric blue.
- Hero copy limited to 2–4 short lines.

### CTAs
- Primary: `Request an Assessment`.
- Secondary: `Explore Capabilities` / `Explore Services`.
- Premium illuminated border/hover treatment; no oversized buttons.

## 3. Realistic global threat globe — mandatory

Replace the current globe completely.

Requirements:
- Realistic 3D / pseudo-3D Earth with convincing continent detail and night-light treatment.
- Electric-blue network mesh and orbital arcs.
- Live-style threat/event nodes across Europe, Middle East, Africa, Asia, and the Americas.
- Pulsing nodes, connection paths, concentric event rings.
- Small adjacent `Live Threat Map` panel with High / Medium / Low risk counts and `LIVE` status.
- Globe must feel like a SOC / intelligence visualization, not a decorative illustration.

Important product wording:
- Threat points are **visual intelligence indicators / simulated demo telemetry** unless backed by a real live threat data source.
- Do not imply fabricated real-time breach telemetry.

## 4. RF Environment Radar — mandatory

A clearly visible circular radar module must return to the homepage.

Display:
- Rotating/sweeping radar beam or equivalent CSS/SVG animation.
- Detection dots/blips.
- Circular distance rings and degree markings.
- `RF Environment Radar` title.
- `Detected Signals`, `Active Sources`, `Signal Strength`, `Threat Level`.
- Frequency bands: 433 MHz, 868 MHz, 2.4 GHz, 5.8 GHz, GPS, Other.
- Strong visual presence; must not be a small decorative circle.

## 5. RF Spectrum Analysis — mandatory

Place next to the radar.

Display:
- Spectrum line graph with realistic peaks.
- Frequency scale in GHz/MHz.
- dBm / signal-strength readout.
- Detected-signal marker.
- Spectrogram / waterfall heatmap beneath the graph.
- `LIVE` visual state, but label simulated/demo data accurately unless connected to a real source.

## 6. Core services grid

Five or six premium cards:

1. Cybersecurity
2. Counter-Surveillance / TSCM
3. Executive Technology Protection
4. Digital Forensics
5. Physical Security Technology
6. Privacy & Secure Communications (optional if layout supports six)

Each card:
- Expressive line icon.
- 01 / 02 / 03 sequence number.
- 4–6 concise capability bullets.
- Consistent electric-blue interaction state.
- No generic emoji or low-quality iconography.

## 7. Executive protection section

Section title direction:
`PROTECTING THE PEOPLE WHO MAKE CRITICAL DECISIONS.`

Audience cards:
- Executive Offices
- Boardrooms
- Corporate Headquarters
- Executive Vehicles
- Hotels & Travel
- Private Residences

Use one premium supporting visual plus compact icon cards.

## 8. Security Operations summary

Add command-center KPI cards:
- Threat Status
- RF Environment
- Network Exposure
- Endpoint Status
- Incident Readiness

Use gauges, mini charts, bars, and status indicators. Green / amber / red are status colors only; electric blue remains the main brand accent.

## 9. Header

Premium compact header with:
- NEXVARY logo.
- Solutions dropdown.
- Services dropdown.
- Industries dropdown.
- Intelligence dropdown.
- Company dropdown.
- Language switcher.
- `Request Security Assessment` CTA.

No loose spacing or oversized navigation text.

## 10. Footer

Structured footer with:
- Logo and tagline.
- Solutions / Company / Resources columns.
- Contact details.
- Official social links.
- Privacy / Terms.
- Subtle global map / intelligence motif.

## 11. Apps page

`/apps` must feel like a premium App Center, not a plain list.

Each app card should support:
- App icon.
- Platform badges: Android / Windows / Linux / Web.
- Version.
- Release status.
- Download / Request Access CTA.
- Short changelog.

## 12. Services page

Dedicated premium sections for:
- Cybersecurity
- TSCM / Counter-Surveillance
- Digital Forensics
- Executive Protection Technology
- Physical Security Technology
- Privacy & Secure Communications

Each section needs icon, short description, capabilities, and CTA.

## 13. Arabic RTL — release blocker

Arabic must be treated as a first-class interface:
- Correct `dir=rtl` behavior.
- Right alignment for Arabic content.
- Mirrored layout where appropriate.
- No broken heading wraps.
- Buttons/icons remain correctly aligned.
- No mixed-direction UI defects.

## 14. Mobile — release blocker

Do not simply scale down desktop.

Mobile requirements:
- Headline remains compact.
- Globe remains legible and visually strong.
- Radar and spectrum modules stack cleanly.
- Cards never overlap.
- Navigation works with touch.
- No horizontal overflow.
- Screenshots required at a real mobile viewport.

## 15. Interaction / motion

Prefer CSS/SVG/WebGL-native effects that remain performant:
- Globe rotation / orbit lines.
- Pulsing threat nodes.
- Radar sweep.
- Spectrum pulse.
- Subtle glow and hover transitions.

Avoid gratuitous heavy animation or anything that harms Core Web Vitals.

## 16. Release acceptance criteria

The release is **NOT visually approved** until all are true:

- [ ] Realistic globe replaces current weak globe.
- [ ] Global threat/activity indicators are visible.
- [ ] RF radar is clearly present.
- [ ] Spectrum analysis panel is clearly present.
- [ ] Hero headline is 20–30% smaller than the rejected build.
- [ ] Desktop composition is balanced with no dead hero space.
- [ ] Mobile composition is independently polished.
- [ ] Service icons/cards are high quality and expressive.
- [ ] Arabic RTL passes visual QA.
- [ ] All primary buttons/links are functional.
- [ ] Packaged cPanel ZIP passes runtime smoke tests.
- [ ] Screenshots are captured from the **installed packaged artifact itself**.
- [ ] Product owner visually approves screenshots before hosting upload.

## 17. Screenshot gate

Before any package is called FINAL, capture at minimum:
- Homepage desktop 1440×1100 or similar.
- Homepage mobile (Pixel-class viewport).
- Services desktop.
- Apps desktop.
- About or Contact desktop.

Screenshots must come from the installed cPanel artifact after runtime installation, not from a separate dev build.

## 18. Final rule

**Build success is not release approval.**

Final = runtime-passed package + visual release gate + real screenshots + product-owner approval.