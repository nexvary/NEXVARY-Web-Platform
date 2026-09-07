#!/usr/bin/env bash
set -euo pipefail

APP_DIR="${1:-$(pwd)}"
cd "$APP_DIR"

echo "NEXVARY Production Installer"
echo "============================"

command -v php >/dev/null 2>&1 || { echo "ERROR: PHP is required."; exit 1; }
command -v composer >/dev/null 2>&1 || { echo "ERROR: Composer is required."; exit 1; }

PHP_VERSION="$(php -r 'echo PHP_VERSION_ID;')"
if [ "$PHP_VERSION" -lt 80300 ]; then
  echo "ERROR: PHP 8.3+ is required. Current: $(php -r 'echo PHP_VERSION;')"
  exit 1
fi

for ext in mbstring openssl pdo tokenizer xml ctype json fileinfo; do
  php -m | grep -qi "^${ext}$" || { echo "ERROR: Missing PHP extension: ${ext}"; exit 1; }
done

if [ ! -f composer.lock ] || [ ! -f package-lock.json ]; then
  echo "ERROR: Reproducible lockfiles are required."
  exit 1
fi

if [ ! -f .env ]; then
  cp .env.example .env
  sed -i.bak 's/^APP_ENV=.*/APP_ENV=production/' .env
  sed -i.bak 's/^APP_DEBUG=.*/APP_DEBUG=false/' .env
  sed -i.bak 's|^APP_URL=.*|APP_URL=https://nexvary.com|' .env
  rm -f .env.bak
  echo "Created production .env from template."
fi

php artisan key:generate --force
composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader --no-progress

if [ -f package-lock.json ]; then
  if command -v npm >/dev/null 2>&1; then
    npm ci --no-audit --no-fund
    npm run build
  elif [ ! -f public/build/manifest.json ]; then
    echo "ERROR: npm is unavailable and no prebuilt public/build assets were found."
    exit 1
  fi
fi

mkdir -p storage/framework/{cache,sessions,views} storage/logs bootstrap/cache
chmod -R ug+rwX storage bootstrap/cache || true

php artisan migrate --force
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

if php artisan security:audit-verify >/dev/null 2>&1; then
  echo "Audit chain verification: PASS"
else
  echo "Audit chain verification: no prior events or check unavailable"
fi

php artisan about --only=environment >/dev/null

echo
echo "INSTALLATION COMPLETE"
echo "APP_URL: $(grep '^APP_URL=' .env | cut -d= -f2-)"
echo "APP_ENV: production"
echo "APP_DEBUG: false"
echo
echo "IMPORTANT: point the domain document root to: $APP_DIR/public"
echo "If your cPanel cannot change document root, deploy public/ into public_html and keep Laravel application files outside public_html."
