import { defineConfig } from '@playwright/test';
import base from './playwright.config';

// Checks the final footer grid, colored icons and simplified company year.
// Read-only checks: navigation, asset requests, screenshots and accessibility.
export default defineConfig({
  ...base,
  use: { ...base.use, baseURL: 'https://nexvary.com' },
  workers: 1,
  retries: 0,
  testMatch: ['corporate-daylight.spec.ts', 'project-showcase.spec.ts', 'premium-refinement.spec.ts'],
});
