import { readFileSync } from 'node:fs';
import { execFileSync } from 'node:child_process';

const failures = [];
const warnings = [];
const env = readFileSync('.env.example', 'utf8');

const requireEnv = (key, expected) => {
  const match = env.match(new RegExp(`^${key}=(.*)$`, 'm'));
  if (!match) failures.push(`${key} is missing from .env.example`);
  else if (expected !== undefined && match[1].trim().toLowerCase() !== String(expected).toLowerCase()) {
    failures.push(`${key} must be ${expected}, found ${match[1].trim() || '<empty>'}`);
  }
};

requireEnv('APP_DEBUG', 'false');
requireEnv('SESSION_ENCRYPT', 'true');
requireEnv('SESSION_SECURE_COOKIE', 'true');
requireEnv('SESSION_HTTP_ONLY', 'true');
requireEnv('NEXVARY_CSP_REPORT_ONLY', 'false');

const sameSite = env.match(/^SESSION_SAME_SITE=(.*)$/m)?.[1]?.trim().toLowerCase();
if (!['lax', 'strict'].includes(sameSite ?? '')) failures.push('SESSION_SAME_SITE must be lax or strict');

const tracked = execFileSync('git', ['ls-files'], { encoding: 'utf8' })
  .split('\n')
  .filter(Boolean)
  .filter((file) => /\.(php|tsx?|jsx?|mjs|cjs|json|ya?ml|env|md)$/i.test(file))
  .filter((file) => !file.startsWith('vendor/') && !file.startsWith('node_modules/'));

const secretPatterns = [
  { name: 'private key', rx: /-----BEGIN (?:RSA |EC |OPENSSH )?PRIVATE KEY-----/ },
  { name: 'AWS access key', rx: /\bAKIA[0-9A-Z]{16}\b/ },
  { name: 'GitHub token', rx: /\bgh[pousr]_[A-Za-z0-9_]{30,}\b/ },
];

for (const file of tracked) {
  let text = '';
  try { text = readFileSync(file, 'utf8'); } catch { continue; }
  for (const pattern of secretPatterns) {
    if (pattern.rx.test(text)) failures.push(`${pattern.name} signature found in ${file}`);
  }
  if (/APP_DEBUG\s*=\s*true/i.test(text) && file !== '.env.example') warnings.push(`debug=true marker in ${file}`);
}

const packageJson = JSON.parse(readFileSync('package.json', 'utf8'));
for (const required of ['types:check', 'build', 'visual:test', 'security:gate']) {
  if (!packageJson.scripts?.[required]) failures.push(`npm script '${required}' is missing`);
}

console.log('NEXVARY Security Release Gate');
console.log(`Scanned ${tracked.length} tracked text files.`);
for (const warning of warnings) console.warn(`WARN: ${warning}`);
if (failures.length) {
  for (const failure of failures) console.error(`FAIL: ${failure}`);
  process.exit(1);
}
console.log('PASS: baseline security controls and secret scan are clean.');
