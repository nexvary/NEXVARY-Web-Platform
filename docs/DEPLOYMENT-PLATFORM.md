# NEXVARY Deployment Platform

## Architecture

Application projects:

```
GitHub -> CI -> tests -> Coolify -> VPS -> HTTPS -> health check -> Projects metadata -> Our Work
```

Main website while hosted on shared cPanel:

```
GitHub -> CI -> build -> cPanel-ready artifact -> cPanel deployment -> nexvary.com
```

Coolify is not installed on shared hosting. It is the deployment engine for VPS-hosted applications and subdomains.

## Project manifest

Each application repository may include `nexvary-project.json`. The canonical schema is `schema/nexvary-project.schema.json`.

Lifecycle values are: Available, Beta, In Development, Private Preview, Internal.

Deployment modes:

- `deploy_publish`: deploy, verify health, then publish when `publishToWebsite=true`.
- `deploy_only`: deploy and verify without public publication.

A successful build is not sufficient for publication. Publication requires an HTTPS health check returning HTTP 200.

## GitHub integration

Projects can be linked to a GitHub repository and branch. The admin **Sync GitHub** action reads the current branch commit and, when present, `nexvary-project.json`.

Public repositories work without a token. Private repositories require a narrowly scoped credential in `GITHUB_TOKEN`. Prefer a GitHub App installation token because it is repository-scoped and short-lived; if a fine-grained personal access token is used instead, grant only the repository read permissions required for contents and metadata.

Do not use a classic broad-scope PAT.

## Coolify

Required secrets:

- `COOLIFY_BASE_URL`
- `COOLIFY_API_TOKEN`
- `NEXVARY_DEPLOY_HMAC_SECRET`

Optional:

- `NEXVARY_ALLOWED_PROJECT_HOST_SUFFIX=nexvary.com`
- `NEXVARY_DEPLOY_TIMEOUT=15`

The token stays outside Git. Use a token limited to the NEXVARY deployment team/resources.

The integration uses:

- POST `/api/v1/deploy`
- GET `/api/v1/deployments/{uuid}`
- POST `/api/v1/applications/{uuid}/rollback`

## Admin workflow

The Deployments panel records project, repository, branch, commit, environment, domain, provider, status, health and log summary.

Actions include Deploy & Publish, Deploy Only, Refresh, Retry, Rollback, Publish/Unpublish and Open Project.

Rollback returns application code to the previous recorded healthy commit. It does not automatically reverse database migrations or persistent data.

## Signed publish callback

`POST /api/deployments/publish` requires:

- `X-NEXVARY-Timestamp`
- `X-NEXVARY-Signature`

Signature:

```
HMAC-SHA256(secret, timestamp + "." + raw_request_body)
```

Requests older than five minutes are rejected. The server performs its own HTTPS health check before publishing.

## cPanel

The existing `.github/workflows/cpanel-package.yml` builds and smoke-tests a cPanel-ready ZIP. At present it produces an artifact; it is not proof of automatic production deployment.

For automatic cPanel deployment use one controlled path supported by the hosting account:

1. cPanel Git Version Control and `.cpanel.yml`.
2. Restricted SSH deploy key stored in GitHub Secrets.
3. Narrowly scoped cPanel API token.

Recommended secret names:

- `CPANEL_DEPLOY_KEY`
- `DEPLOY_SSH_KEY`

Never store cPanel passwords in Git or workflow output.

## Safety

- Production deployment follows CI.
- Retain the previous healthy commit and deployment ID.
- Verify DNS, HTTPS and HTTP 200 before publication.
- Failed deployments do not replace public project metadata.
- Mask secrets in logs.
- Future AI agents must use constrained deployment APIs and must not receive unrestricted root access.
