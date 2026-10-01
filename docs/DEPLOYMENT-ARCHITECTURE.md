# NEXVARY Deployment Architecture

## Verified repository state

The main website is a Laravel/Inertia/React application with an existing cPanel-ready packaging workflow. The current cPanel workflow builds production dependencies, compiles frontend assets, assembles a split `nexvary_app/` + `public_html/` bundle, runs a runtime smoke test, captures screenshots, and uploads a ZIP artifact. It does **not** currently prove that the live cPanel account is automatically deploying that artifact.

Do not describe the live hosting mechanism as Git deployment, SFTP deployment, or Coolify deployment until the hosting account is verified.

## Target architecture

For deployable NEXVARY applications:

```text
GitHub repository
  -> repository CI / tests / build
  -> Coolify API
  -> VPS application
  -> HTTPS domain
  -> HTTP 200 health check
  -> signed NEXVARY publish callback
  -> Projects / Our Work metadata
```

For the company website, keep the existing cPanel package path until the actual cPanel Git/SSH capabilities are verified:

```text
GitHub
  -> Release Gate
  -> cPanel Ready Bundle
  -> verified cPanel deployment path
  -> nexvary.com
```

If cPanel Git Version Control is enabled on the hosting account, prefer a clean Git-controlled deployment with a checked-in `.cpanel.yml` created only after the real account paths are known. Do not guess `public_html`, home-directory, PHP or Composer paths in production. The current ZIP workflow remains the safe fallback.

## Coolify integration

Application code reads:

- `COOLIFY_BASE_URL`
- `COOLIFY_API_TOKEN`
- `NEXVARY_DEPLOY_TIMEOUT`

Use a restricted Coolify token. The token never belongs in Git, project manifests, logs, issue bodies or screenshots.

The deployment UI supports two modes:

- **Deploy & Publish**: deploy, wait for completion, verify the configured HTTPS health URL, then allow publication.
- **Deploy Only**: deploy without making the project public in Our Work.

A successful queue response is not treated as a successful release. Publication requires a server-side or CI-side HTTP 200 health verification.

## Signed metadata publication

`POST /api/deployments/publish` accepts a project manifest and deployment metadata only when the request includes:

- `X-NEXVARY-Timestamp`
- `X-NEXVARY-Signature`

The signature is HMAC-SHA256 over:

```text
<unix-timestamp>.<raw-request-body>
```

using `NEXVARY_DEPLOY_HMAC_SECRET`. Requests older than five minutes are rejected. Website and health URLs are restricted to HTTPS on the configured NEXVARY domain suffix to reduce SSRF risk.

## Health gates

Before public publication:

1. Deployment provider reports completion.
2. Domain is an allowed NEXVARY HTTPS URL.
3. TLS validation succeeds through the normal HTTP client.
4. Health URL returns exactly HTTP 200.
5. `publishToWebsite` is true.
6. Deployment mode is `deploy_publish`.
7. Project visibility is `public`.

If any gate fails, the previous public record remains the source of truth.

## Rollback

The admin rollback action selects the most recent earlier **Healthy** deployment with a commit SHA and asks Coolify to roll the application back to that commit. The rollback is recorded as a deployment run.

Application rollback does not imply database, persistent-volume, queue, object-storage or external-service rollback. Schema changes must be backward-compatible or have an explicit migration rollback plan.

## AI-ready boundary

Future agents may read project metadata, README files, build logs and deployment summaries, propose environment variables, diagnose failures and prepare release notes. Agents should operate through scoped APIs and deployment identities. Do not grant an AI agent unrestricted root access to a VPS.
