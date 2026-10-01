import { readFileSync } from 'node:fs';

const file = process.argv[2] || 'nexvary-project.json';
const manifest = JSON.parse(readFileSync(file, 'utf8'));
const errors = [];
const required = ['name', 'slug', 'description', 'category', 'status', 'visibility', 'technologies', 'publishToWebsite'];
for (const key of required) if (!(key in manifest)) errors.push(`Missing required key: ${key}`);

if (!/^[a-z0-9]+(?:-[a-z0-9]+)*$/.test(manifest.slug || '')) errors.push('slug must use lowercase kebab-case');
if (!['Available', 'Beta', 'In Development', 'Private Preview', 'Internal'].includes(manifest.status)) errors.push('status is not allowed');
if (!['public', 'private', 'internal'].includes(manifest.visibility)) errors.push('visibility is not allowed');
if (!Array.isArray(manifest.technologies) || manifest.technologies.length < 1) errors.push('technologies must contain at least one item');
if (typeof manifest.publishToWebsite !== 'boolean') errors.push('publishToWebsite must be boolean');
if (manifest.deploymentMode && !['deploy_publish', 'deploy_only'].includes(manifest.deploymentMode)) errors.push('deploymentMode is not allowed');

for (const key of ['website', 'healthCheck']) {
  if (manifest[key] && !String(manifest[key]).startsWith('https://')) errors.push(`${key} must use HTTPS`);
}
if (manifest.repository && !/^https:\/\/github\.com\//i.test(manifest.repository)) errors.push('repository must be an HTTPS GitHub URL');

if (errors.length) {
  errors.forEach(error => console.error(`FAIL: ${error}`));
  process.exit(1);
}

console.log(`PASS: ${file} satisfies the NEXVARY manifest baseline.`);
