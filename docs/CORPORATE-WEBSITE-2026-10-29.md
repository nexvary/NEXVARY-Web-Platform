# Nexvary Inc corporate wall redesign

Base: `32f59d0f48e6864e8ac1c9bc6c04f70f809aa769` (main, 2026-10-03).

## Audit

The public production HTML matches the Laravel Blade public surface: Laravel + Vite, with React/Inertia retained for administrative surfaces. Responses include nonce-based CSP, HSTS, frame restrictions and secure session cookies. The server identifies LiteSpeed. Source provides a split `nexvary_app` / `public_html` cPanel packaging workflow; no authenticated production hosting session or verified remote deployment credential is available in this run. HTTP server headers alone do not establish the hosting account or document root.

The old home prioritizes security services, simulated RF/spectrum data and an operational globe. The new public introduction focuses on software, intelligent applications, digital security and research. No metrics, clients or accreditations are invented. SafeScan's old public page had no scanner control or implementation; its retained public route is now honestly marked Research Preview.

## Design

The final user directive supersedes the initial dark palette. Ivory limestone and beige marble form one continuous wall. Typography uses dark bronze incised relief, navigation sits in a sculpted band, products use inward-shadowed niches and the original seal occupies a recessed circular architectural reveal. No full-seal repeated background, gold gradients, dark public background or animation-heavy effects.

Generated material asset: `public/assets/corporate/ivory-limestone.webp`. Built-in image generation; prompt: orthographic natural ivory limestone and warm beige marble, low-contrast mineral detail, soft upper-left illumination, surface only, no text, objects, border or joints. Original uploaded seal is optimized as WebP without image content edits. Building is identified as artwork, never a physical office claim. Amiri font files are self-hosted under the included SIL Open Font License.

## Product evidence (source-reviewed, not device re-tested)

| Product | Reviewed source | Latest source update | Public scope |
| --- | --- | --- | --- |
| NEXVARY FoodGuard | private main `0d4012edd943e33f56b686a8a7bfcf9121d908ce` | 2026-09-25 UTC | Food guidance, local image-quality heuristics, catalog/checklists/history/sharing. No trained spoilage model or food safety certification claim. |
| NEXVARY Guardian Studio | `fix/guardian-recipient-picker` at `e5e0c150bb2818e9c7215c6d70614ea4f86cb023` | 2026-10-25 UTC | Recorder Studio evolution; camera/screen/audio, local media, optional user-configured SMTP. Cloud setup and physical validation remain separate. |
| NEXVARY-MR | `feature/government-operations` at `eb2322140956a55a50ee7494570f35573a1ce0c0` | 2026-10-27 UTC | Authorized device observation, local case/evidence/audit workflow and bounded offline inspection. Unsigned development; no government certification. |

The MR screenshot is `evidence/ui-rebuild-2026-10-23/dashboard-en.png` at the reviewed MR commit. Inspected: no personal records or device identifiers, a development dashboard with test counts. No suitable source screenshots were found for the other two projects. No generated application screenshots were substituted. Public cards contain no private repository links or unapproved development-source or download links.

Company name, jurisdiction and incorporation date are supplied by the owner in this task; independent registry verification is not claimed.

## Production deployment / rollback

Before applying any production files, authenticate the actual cPanel account and verify the document root and PHP runtime against this lockfile (PHP >=8.4.1). Take a private backup of application code and `public_html`, plus a database backup and persistent files. Never include `.env`, private uploads or databases in a public download.

The source baseline is retained as a restore point. A code snapshot does not replace the required production-data backup. Build and test the new release separately. Preserve the current `.env`, `APP_KEY`, database, storage, permissions and admin state. This change adds no database migration. Keep both old and new hashed Vite build files during the transition.

After successful tests and screenshot review, place the new views, corporate config, public-seo routes, locale middleware, built Vite files and corporate assets in the corresponding existing paths. Clear/rebuild view, route and config caches using the hosting PHP binary. Verify every public route in EN/AR, contact submission with a clearly disposable test record, assets, CSP and /health. If any gate fails, restore code and manifest from the backup, clear/rebuild caches again, and verify /health and representative pages. Do not restore the database over newly received contact requests for this presentation-only release.

Do not run the initial setup installer on the existing site. Do not change DNS, mail, payment, infrastructure, or access permissions.
