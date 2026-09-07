#!/usr/bin/env bash
set -euo pipefail

ROOT="${RUNNER_TEMP:-/tmp}/cpanel-bundle"
APP="$ROOT/nexvary_app"
WEB="$ROOT/public_html"
rm -rf "$ROOT"
mkdir -p "$APP" "$WEB"

rsync -a ./ "$APP/" \
  --exclude='.git' \
  --exclude='.github' \
  --exclude='node_modules' \
  --exclude='tests' \
  --exclude='public' \
  --exclude='.env' \
  --exclude='dist'

cp -a public/. "$WEB/"

python3 - <<'PY'
from pathlib import Path
import os
p = Path(os.environ.get('RUNNER_TEMP', '/tmp')) / 'cpanel-bundle' / 'public_html' / 'index.php'
text = p.read_text()
text = text.replace("__DIR__.'/../vendor/autoload.php'", "__DIR__.'/../nexvary_app/vendor/autoload.php'")
text = text.replace("__DIR__.'/../bootstrap/app.php'", "__DIR__.'/../nexvary_app/bootstrap/app.php'")
p.write_text(text)
PY

TOKEN="$(openssl rand -hex 18)"
export TOKEN ROOT

python3 - <<'PY'
import os
from pathlib import Path
root = Path(os.environ['ROOT'])
token = os.environ['TOKEN']
setup = root / 'public_html' / 'setup-nexvary.php'
setup.write_text(f'''<?php
declare(strict_types=1);

$expected = '{token}';
$provided = (string) ($_GET['key'] ?? '');
if (!hash_equals($expected, $provided)) {{
    http_response_code(403);
    exit('Invalid setup key.');
}}

$appDir = realpath(__DIR__.'/../nexvary_app');
if ($appDir === false) {{
    http_response_code(500);
    exit('Application folder not found. Extract the bundle in the cPanel home directory.');
}}

$installed = $appDir.'/.installed';
if (is_file($installed)) {{
    http_response_code(410);
    exit('NEXVARY is already installed.');
}}

foreach ([$appDir.'/storage', $appDir.'/storage/framework', $appDir.'/storage/framework/sessions', $appDir.'/storage/framework/cache', $appDir.'/storage/framework/views', $appDir.'/bootstrap/cache'] as $writable) {{
    if (!is_dir($writable)) {{
        @mkdir($writable, 0775, true);
    }}
    @chmod($writable, 0775);
}}

$db = $appDir.'/database/database.sqlite';
if (!is_file($db)) {{
    touch($db);
    @chmod($db, 0660);
}}

$env = $appDir.'/.env';
if (!is_file($env)) {{
    $host = preg_replace('/[^A-Za-z0-9.:-]/', '', (string) ($_SERVER['HTTP_HOST'] ?? 'nexvary.com'));
    $url = 'https://'.$host;
    $key = 'base64:'.base64_encode(random_bytes(32));
    $lines = [
        'APP_NAME="NEXVARY"',
        'APP_ENV=production',
        'APP_KEY='.$key,
        'APP_DEBUG=false',
        'APP_URL='.$url,
        'LOG_CHANNEL=stack',
        'DB_CONNECTION=sqlite',
        'DB_DATABASE='.$db,
        'SESSION_DRIVER=file',
        'SESSION_LIFETIME=120',
        'SESSION_ENCRYPT=true',
        'SESSION_PATH=/',
        'SESSION_DOMAIN=',
        'SESSION_SECURE_COOKIE=true',
        'SESSION_HTTP_ONLY=true',
        'SESSION_SAME_SITE=lax',
        'CACHE_STORE=file',
        'QUEUE_CONNECTION=sync',
    ];
    file_put_contents($env, implode(PHP_EOL, $lines).PHP_EOL);
    @chmod($env, 0640);
}}

require $appDir.'/vendor/autoload.php';
$app = require $appDir.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\\Contracts\\Console\\Kernel::class);
$kernel->bootstrap();

try {{
    $probe = $appDir.'/storage/framework/sessions/.write-test-'.bin2hex(random_bytes(4));
    if (@file_put_contents($probe, 'ok') === false) {{
        throw new RuntimeException('Session storage is not writable.');
    }}
    @unlink($probe);

    $code = $kernel->call('migrate', ['--force' => true]);
    if ($code !== 0) {{
        throw new RuntimeException('Database migration failed.');
    }}
    $kernel->call('optimize');
    file_put_contents($installed, date(DATE_ATOM).PHP_EOL);
    @unlink(__FILE__);
}} catch (Throwable $e) {{
    report($e);
    http_response_code(500);
    exit('Setup failed safely. Check storage/logs/laravel.log.');
}}

header('Content-Type: text/html; charset=UTF-8');
echo '<!doctype html><html lang="ar" dir="rtl"><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>NEXVARY</title><body style="font-family:Arial,sans-serif;background:#06111f;color:#fff;padding:40px;text-align:center"><h1>تم تثبيت NEXVARY بنجاح</h1><p>تم إنشاء قاعدة البيانات ومفتاح التطبيق وتشغيل التحديثات وتحسين Laravel.</p><p><a style="color:#72d9ff" href="/">فتح الموقع</a></p></body></html>';
''')

guide = root / 'INSTALL-NEXVARY.txt'
guide.write_text(f'''NEXVARY cPanel Ready — One-Click Setup

1) في cPanel > File Manager اذهب إلى HOME (المجلد الذي يحتوي public_html)، وليس داخل public_html.
2) خذ نسخة احتياطية من موقعك الحالي، ثم ارفع هذا ZIP واعمل Extract في HOME.
3) افتح هذا الرابط مرة واحدة فقط:
   https://nexvary.com/setup-nexvary.php?key={token}

بعد نجاح التثبيت سيحذف ملف setup-nexvary.php نفسه تلقائياً.
الحزمة تستخدم SQLite افتراضياً لتجنب إنشاء قاعدة MySQL يدوياً.
لا ترفع ملف ZIP داخل public_html؛ ارفعه في HOME ثم Extract.
''')
PY

(cd "$ROOT" && zip -qr "$GITHUB_WORKSPACE/NEXVARY-cPanel-Ready.zip" nexvary_app public_html INSTALL-NEXVARY.txt)
