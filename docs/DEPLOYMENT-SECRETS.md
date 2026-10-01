# Deployment Secrets

No credentials should be committed to this repository.

## Application environment

Configure these on the NEXVARY web application host:

| Name | Purpose |
| --- | --- |
| `COOLIFY_BASE_URL` | Base URL of the self-hosted Coolify API |
| `COOLIFY_API_TOKEN` | Restricted token for deployment/status/rollback operations |
| `NEXVARY_DEPLOY_HMAC_SECRET` | High-entropy shared secret for signed publish callbacks |
| `NEXVARY_ALLOWED_PROJECT_HOST_SUFFIX` | Allowed project domain suffix; default `nexvary.com` |
| `NEXVARY_DEPLOY_TIMEOUT` | Outbound Coolify API timeout |

## GitHub Actions

Store values under **Settings → Secrets and variables → Actions**.

Secrets:

- `COOLIFY_API_TOKEN`
- `NEXVARY_DEPLOY_HMAC_SECRET`
- `CPANEL_DEPLOY_KEY` only if SSH-based cPanel deployment is activated later
- `DEPLOY_SSH_KEY` only for a separately scoped deployment identity
- `GITHUB_TOKEN` should normally use GitHub Actions' built-in scoped token; for cross-repository private access prefer a GitHub App with narrowly scoped installation permissions rather than a broad personal access token.

Repository/environment variable:

- `COOLIFY_BASE_URL`

If cPanel automation is activated, add host/user/path values as repository or environment variables rather than hard-coding them.

## Least privilege

- Create a dedicated deployment identity.
- Restrict Coolify permissions to required resources.
- Use GitHub App installation access where practical for private repositories.
- Restrict SSH keys to the deployment account and required paths.
- Rotate tokens and keys after staff or infrastructure changes.
- Keep deployment/audit logs, but mask secrets.
- Never paste root passwords, cPanel passwords, AWS keys or long-lived personal access tokens into chat, commits or manifests.
