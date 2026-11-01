import { defineConfig } from '@playwright/test';
import base from './playwright.config';

// Verifies the approved entrance image, simplified identity and accessible SafeScan dial.
// Read-only checks: navigation, asset requests, screenshots and accessibility.
export default defineConfig({
  ...base,
  use: { ...base.use, baseURL: 'https://nexvary.com' },
  workers: 1,
  retries: 0,
  testMatch: ['corporate-daylight.spec.ts', 'project-showcase.spec.ts', 'premium-refinement.spec.ts'],
});
