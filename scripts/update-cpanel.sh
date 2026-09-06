#!/usr/bin/env bash
set -euo pipefail

APP_DIR="${1:-$(pwd)}"
cd "$APP_DIR"

STAMP="$(date +%Y%m%d-%H%M%S)"
BACKUP_DIR="storage/app/deploy-backups/$STAMP"
mkdir -p "$BACKUP_DIR"

[ -f .env ] || { echo "ERROR: .env is missing. Run install-cpanel.sh first."; exit 1; }
cp .env "$BACKUP_DIR/.env"
[ -f public/build/manifest.json ] && cp -R public/build "$BACKUP_DIR/build"

echo "NEXVARY Update $STAMP"
php artisan down --retry=30 || true
trap 'php artisan up || true' EXIT

composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader --no-progress

if command -v npm >/dev/null 2>&1; then
  npm ci --no-audit --no-fund
  npm run build
elif [ ! -f public/build/manifest.json ]; then
  echo "ERROR: npm is unavailable and there are no prebuilt assets."
  exit 1
fi

php artisan migrate --force
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan security:audit-verify || true
php artisan up
trap - EXIT

echo "UPDATE COMPLETE"
echo "Rollback snapshot: $BACKUP_DIR"
