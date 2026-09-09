#!/usr/bin/env bash
set -euo pipefail

ROOT="${RUNNER_TEMP:-/tmp}/cpanel-bundle"
APP="$ROOT/nexvary_app"
WEB="$ROOT/public_html"
rm -rf "$ROOT"
mkdir -p "$APP" "$WEB"

rsync -a ./ "$APP/" --exclude='.git' --exclude='.github' --exclude='node_modules' --exclude='tests' --exclude='/public/' --exclude='.env' --exclude='dist'
cp -a public/. "$WEB/"
mkdir -p "$APP/public"
cp -a public/build "$APP/public/build"

python3 - <<'PY'
from pathlib import Path
import os
p = Path(os.environ.get('RUNNER_TEMP', '/tmp')) / 'cpanel-bundle' / 'public_html' / 'index.php'
text = p.read_text().replace("__DIR__.'/../vendor/autoload.php'", "__DIR__.'/../nexvary_app/vendor/autoload.php'").replace("__DIR__.'/../bootstrap/app.php'", "__DIR__.'/../nexvary_app/bootstrap/app.php'")
p.write_text(text)
PY

TOKEN="$(openssl rand -hex 18)"
export TOKEN ROOT
python3 - <<'PY'
import os
from pathlib import Path
root=Path(os.environ['ROOT']); token=os.environ['TOKEN']
setup=root/'public_html'/'setup-nexvary.php'
setup.write_text(f'''<?php
declare(strict_types=1);
$expected='{token}';
if (!hash_equals($expected,(string)($_GET['key']??''))) {{ http_response_code(403); exit('Invalid setup key.'); }}
$appDir=realpath(__DIR__.'/../nexvary_app');
if ($appDir===false) {{ http_response_code(500); exit('Application folder not found.'); }}
$installed=$appDir.'/.installed';
foreach ([$appDir.'/storage',$appDir.'/storage/framework/sessions',$appDir.'/storage/framework/cache',$appDir.'/storage/framework/views',$appDir.'/bootstrap/cache'] as $d) {{ if(!is_dir($d)) @mkdir($d,0775,true); @chmod($d,0775); }}
$db=$appDir.'/database/database.sqlite'; if(!is_file($db)) {{ touch($db); @chmod($db,0660); }}
$env=$appDir.'/.env';
if(!is_file($env)) {{ $host=preg_replace('/[^A-Za-z0-9.:-]/','',(string)($_SERVER['HTTP_HOST']??'nexvary.com')); $lines=['APP_NAME="NEXVARY"','APP_ENV=production','APP_KEY=base64:'.base64_encode(random_bytes(32)),'APP_DEBUG=false','APP_URL=https://'.$host,'LOG_CHANNEL=stack','DB_CONNECTION=sqlite','DB_DATABASE='.$db,'SESSION_DRIVER=file','SESSION_LIFETIME=120','SESSION_ENCRYPT=true','SESSION_PATH=/','SESSION_DOMAIN=','SESSION_SECURE_COOKIE=true','SESSION_HTTP_ONLY=true','SESSION_SAME_SITE=lax','CACHE_STORE=file','QUEUE_CONNECTION=sync']; file_put_contents($env,implode(PHP_EOL,$lines).PHP_EOL); @chmod($env,0640); }}
require $appDir.'/vendor/autoload.php'; $app=require $appDir.'/bootstrap/app.php'; $kernel=$app->make(Illuminate\\Contracts\\Console\\Kernel::class); $kernel->bootstrap();
$error=''; $done=false;
try {{ $kernel->call('migrate',['--force'=>true]); $hasOwner=App\\Models\\User::query()->where('role','owner')->orWhere('is_admin',true)->exists();
if ($_SERVER['REQUEST_METHOD']==='POST' && !$hasOwner) {{
$name=trim((string)($_POST['name']??'')); $email=strtolower(trim((string)($_POST['email']??''))); $password=(string)($_POST['password']??''); $confirm=(string)($_POST['password_confirmation']??'');
if(mb_strlen($name)<2) throw new RuntimeException('الاسم مطلوب.'); if(!filter_var($email,FILTER_VALIDATE_EMAIL)) throw new RuntimeException('البريد الإلكتروني غير صحيح.'); if(strlen($password)<12) throw new RuntimeException('كلمة المرور يجب ألا تقل عن 12 حرفاً.'); if($password!==$confirm) throw new RuntimeException('تأكيد كلمة المرور غير مطابق.'); if(App\\Models\\User::query()->where('email',$email)->exists()) throw new RuntimeException('البريد مستخدم بالفعل.');
App\\Models\\User::create(['name'=>$name,'email'=>$email,'password'=>$password,'email_verified_at'=>now(),'is_admin'=>true,'role'=>'owner']); $kernel->call('optimize'); file_put_contents($installed,date(DATE_ATOM).PHP_EOL); $done=true; }}
}} catch(Throwable $e) {{ $error=$e->getMessage(); }}
header('Content-Type: text/html; charset=UTF-8');
if($done) {{ echo '<!doctype html><html lang="ar" dir="rtl"><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><body style="font-family:Arial;background:#06111f;color:white;padding:40px;text-align:center"><h1>تم إنشاء حساب المالك بنجاح</h1><p>يمكنك الآن دخول لوحة الإدارة.</p><a style="color:#72d9ff" href="/secure-control">فتح لوحة الإدارة</a></body></html>'; @unlink(__FILE__); exit; }}
if(App\\Models\\User::query()->where('role','owner')->orWhere('is_admin',true)->exists()) {{ echo '<!doctype html><html lang="ar" dir="rtl"><meta charset="utf-8"><body style="font-family:Arial;background:#06111f;color:white;padding:40px;text-align:center"><h1>حساب الإدارة موجود بالفعل</h1><a style="color:#72d9ff" href="/secure-control">فتح لوحة الإدارة</a></body></html>'; exit; }}
$err=$error?'<p style="color:#ff8a8a">'.htmlspecialchars($error,ENT_QUOTES,'UTF-8').'</p>':'';
echo '<!doctype html><html lang="ar" dir="rtl"><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>إعداد NEXVARY</title><body style="font-family:Arial;background:#06111f;color:#fff;margin:auto;max-width:560px;padding:35px"><h1>إنشاء حساب المالك الأول</h1><p>أدخل بياناتك مرة واحدة. لن توجد كلمة مرور افتراضية.</p>'.$err.'<form method="post"><label>الاسم</label><input required name="name" style="width:100%;padding:12px;margin:7px 0 15px"><label>البريد الإلكتروني</label><input required type="email" name="email" style="width:100%;padding:12px;margin:7px 0 15px"><label>كلمة المرور (12 حرفاً على الأقل)</label><input required minlength="12" type="password" name="password" style="width:100%;padding:12px;margin:7px 0 15px"><label>تأكيد كلمة المرور</label><input required minlength="12" type="password" name="password_confirmation" style="width:100%;padding:12px;margin:7px 0 20px"><button style="width:100%;padding:14px;background:#41c7ff;border:0;border-radius:8px;font-weight:bold">إنشاء Owner وفتح الإدارة</button></form></body></html>';
''')
(root/'INSTALL-NEXVARY.txt').write_text(f'''NEXVARY cPanel Ready\n1) Extract in cPanel HOME.\n2) Open once: https://YOUR-DOMAIN/setup-nexvary.php?key={token}\n3) Enter owner name, email and a password of at least 12 characters.\n4) After success open /secure-control.\nThe setup file deletes itself after creating the first owner.\n''')
PY
(cd "$ROOT" && zip -qr "$GITHUB_WORKSPACE/NEXVARY-cPanel-Ready.zip" nexvary_app public_html INSTALL-NEXVARY.txt)
