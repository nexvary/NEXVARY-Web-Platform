<?php

declare(strict_types=1);

$root = dirname(__DIR__);
$lockFile = $root.'/storage/app/.nexvary-installed';
$envFile = $root.'/.env';
$envExample = $root.'/.env.example';
$dbFile = $root.'/database/database.sqlite';

header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('Referrer-Policy: no-referrer');
header("Content-Security-Policy: default-src 'none'; style-src 'unsafe-inline'; form-action 'self'; base-uri 'none'; frame-ancestors 'none'");
header('Cache-Control: no-store, max-age=0');

if (is_file($lockFile)) {
    http_response_code(410);
    echo '<!doctype html><html><meta charset="utf-8"><title>NEXVARY Installed</title><body style="font-family:system-ui;background:#020617;color:#e2e8f0;padding:40px"><h1>NEXVARY is already installed</h1><p>The quick installer is locked for security.</p><p><a style="color:#67e8f9" href="/">Open website</a></p></body></html>';
    exit;
}

if (PHP_VERSION_ID < 80300) {
    http_response_code(500);
    exit('PHP 8.3 or newer is required.');
}

if (! is_file($root.'/vendor/autoload.php')) {
    http_response_code(500);
    exit('The production vendor directory is missing. Upload the complete NEXVARY cPanel bundle.');
}

session_name('nexvary_quick_install');
session_start([
    'cookie_httponly' => true,
    'cookie_samesite' => 'Strict',
    'cookie_secure' => (! empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
    'use_strict_mode' => true,
]);

$_SESSION['install_token'] ??= bin2hex(random_bytes(32));
$errors = [];
$complete = false;

function envSet(string $content, string $key, string $value): string
{
    $line = $key.'='.$value;
    $pattern = '/^'.preg_quote($key, '/').'=.*$/m';

    if (preg_match($pattern, $content) === 1) {
        return (string) preg_replace($pattern, $line, $content);
    }

    return rtrim($content).PHP_EOL.$line.PHP_EOL;
}

function ensureDir(string $path): void
{
    if (! is_dir($path) && ! mkdir($path, 0775, true) && ! is_dir($path)) {
        throw new RuntimeException('Unable to create directory: '.$path);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $token = (string) ($_POST['token'] ?? '');
        if ($token === '' || ! hash_equals((string) $_SESSION['install_token'], $token)) {
            throw new RuntimeException('Installer security token mismatch. Refresh the page and try again.');
        }

        if (! is_file($envExample)) {
            throw new RuntimeException('.env.example is missing from the uploaded bundle.');
        }

        ensureDir($root.'/storage/app');
        ensureDir($root.'/storage/framework/cache');
        ensureDir($root.'/storage/framework/sessions');
        ensureDir($root.'/storage/framework/views');
        ensureDir($root.'/storage/logs');
        ensureDir($root.'/bootstrap/cache');
        ensureDir($root.'/database');

        if (! is_file($dbFile) && file_put_contents($dbFile, '') === false) {
            throw new RuntimeException('Unable to create SQLite database. Check database/ permissions.');
        }

        $env = is_file($envFile) ? (string) file_get_contents($envFile) : (string) file_get_contents($envExample);
        if ($env === '') {
            throw new RuntimeException('Unable to read environment template.');
        }

        $host = preg_replace('/[^A-Za-z0-9.:-]/', '', (string) ($_SERVER['HTTP_HOST'] ?? 'nexvary.com')) ?: 'nexvary.com';
        $scheme = (! empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $appUrl = $scheme.'://'.$host;
        $appKey = 'base64:'.base64_encode(random_bytes(32));

        $env = envSet($env, 'APP_ENV', 'production');
        $env = envSet($env, 'APP_DEBUG', 'false');
        $env = envSet($env, 'APP_URL', $appUrl);
        $env = envSet($env, 'APP_KEY', $appKey);
        $env = envSet($env, 'DB_CONNECTION', 'sqlite');
        $env = envSet($env, 'DB_DATABASE', $dbFile);
        $env = envSet($env, 'SESSION_SECURE_COOKIE', $scheme === 'https' ? 'true' : 'false');

        if (file_put_contents($envFile, $env, LOCK_EX) === false) {
            throw new RuntimeException('Unable to write .env. Check application directory permissions.');
        }
        @chmod($envFile, 0640);
        @chmod($dbFile, 0660);

        require $root.'/vendor/autoload.php';
        $app = require $root.'/bootstrap/app.php';
        $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
        $kernel->bootstrap();

        $exit = Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        if ($exit !== 0) {
            throw new RuntimeException('Database migrations failed: '.Illuminate\Support\Facades\Artisan::output());
        }

        Illuminate\Support\Facades\Artisan::call('optimize:clear');
        Illuminate\Support\Facades\Artisan::call('config:cache');
        Illuminate\Support\Facades\Artisan::call('route:cache');
        Illuminate\Support\Facades\Artisan::call('view:cache');

        $marker = json_encode([
            'installed_at' => gmdate('c'),
            'app_url' => $appUrl,
            'php' => PHP_VERSION,
            'installer' => 'quick-install-v1',
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR);

        if (file_put_contents($lockFile, $marker.PHP_EOL, LOCK_EX) === false) {
            throw new RuntimeException('Installation completed but installer lock could not be written.');
        }
        @chmod($lockFile, 0640);

        unset($_SESSION['install_token']);
        $complete = true;
    } catch (Throwable $e) {
        $errors[] = $e->getMessage();
    }
}

$requirements = [
    ['PHP 8.3+', PHP_VERSION_ID >= 80300, PHP_VERSION],
    ['PDO SQLite', extension_loaded('pdo_sqlite'), extension_loaded('pdo_sqlite') ? 'Available' : 'Missing'],
    ['OpenSSL', extension_loaded('openssl'), extension_loaded('openssl') ? 'Available' : 'Missing'],
    ['Mbstring', extension_loaded('mbstring'), extension_loaded('mbstring') ? 'Available' : 'Missing'],
    ['Production dependencies', is_file($root.'/vendor/autoload.php'), is_file($root.'/vendor/autoload.php') ? 'Ready' : 'Missing'],
    ['Application storage', is_writable($root.'/storage') || is_writable($root), (is_writable($root.'/storage') || is_writable($root)) ? 'Writable' : 'Not writable'],
];
$ready = ! in_array(false, array_column($requirements, 1), true);
?>
<!doctype html>
<html lang="en" dir="ltr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>NEXVARY Quick Install</title>
<style>
*{box-sizing:border-box}body{margin:0;background:#020617;color:#e2e8f0;font-family:Inter,system-ui,-apple-system,sans-serif;min-height:100vh;display:grid;place-items:center;padding:24px}.card{width:min(760px,100%);background:linear-gradient(180deg,#0f172a,#07101f);border:1px solid rgba(103,232,249,.2);border-radius:28px;padding:28px;box-shadow:0 24px 80px rgba(0,0,0,.45)}.brand{letter-spacing:.22em;color:#67e8f9;font-weight:800;font-size:12px}.title{font-size:32px;margin:8px 0}.sub{color:#94a3b8;line-height:1.6}.checks{display:grid;gap:10px;margin:24px 0}.row{display:flex;justify-content:space-between;gap:16px;padding:13px 15px;border:1px solid rgba(255,255,255,.08);border-radius:14px;background:rgba(255,255,255,.025)}.ok{color:#86efac}.bad{color:#fda4af}.btn{width:100%;min-height:54px;border:1px solid rgba(103,232,249,.35);border-radius:15px;background:rgba(34,211,238,.12);color:#cffafe;font-weight:800;font-size:16px;cursor:pointer}.btn:disabled{opacity:.45;cursor:not-allowed}.note{font-size:13px;color:#64748b;margin-top:15px}.error{padding:12px 14px;background:rgba(244,63,94,.1);border:1px solid rgba(244,63,94,.25);border-radius:12px;color:#fecdd3;margin:12px 0}.success{padding:22px;border:1px solid rgba(74,222,128,.25);background:rgba(74,222,128,.08);border-radius:18px}.success a{color:#67e8f9;font-weight:700}
</style>
</head>
<body><main class="card">
<div class="brand">NEXVARY · SECURE DEPLOYMENT</div>
<h1 class="title">One-click installation</h1>
<p class="sub">Upload the complete cPanel bundle, open this page, then press the button once. NEXVARY will configure production mode, create the local database, run migrations and optimize the site automatically.</p>

<?php if ($complete): ?>
<div class="success"><h2>Installation complete ✓</h2><p>The installer is now permanently locked. Future website releases can be installed from the NEXVARY update panel.</p><p><a href="/">Open NEXVARY</a></p></div>
<?php else: ?>
<?php foreach ($errors as $error): ?><div class="error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div><?php endforeach; ?>
<div class="checks">
<?php foreach ($requirements as [$name,$ok,$detail]): ?><div class="row"><span><?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?></span><strong class="<?= $ok ? 'ok' : 'bad' ?>"><?= htmlspecialchars((string) $detail, ENT_QUOTES, 'UTF-8') ?></strong></div><?php endforeach; ?>
</div>
<form method="post"><input type="hidden" name="token" value="<?= htmlspecialchars((string) $_SESSION['install_token'], ENT_QUOTES, 'UTF-8') ?>"><button class="btn" type="submit" <?= $ready ? '' : 'disabled' ?>>Install NEXVARY now</button></form>
<p class="note">For security, this installer can run only once. It writes an installation lock immediately after a successful deployment.</p>
<?php endif; ?>
</main></body></html>
