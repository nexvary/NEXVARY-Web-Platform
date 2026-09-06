import { expect, test } from '@playwright/test';

const pages = ['/', '/about', '/apps', '/safescan'];

for (const path of pages) {
  test(`${path} has no horizontal overflow`, async ({ page }, testInfo) => {
    await page.goto(path, { waitUntil: 'networkidle' });
    await expect(page.locator('body')).toBeVisible();
    const overflow = await page.evaluate(() => document.documentElement.scrollWidth > document.documentElement.clientWidth + 2);
    expect(overflow).toBeFalsy();
    const name = path === '/' ? 'home' : path.replaceAll('/', '');
    await page.screenshot({ path: `artifacts/screenshots/${name}-${testInfo.project.name}.png`, fullPage: true });
  });
}

test('Arabic RTL gate', async ({ page }, testInfo) => {
  await page.goto('/?lang=ar', { waitUntil: 'networkidle' });
  await expect(page.locator('html')).toHaveAttribute('dir', 'rtl');
  await expect(page.locator('html')).toHaveAttribute('lang', /ar/);
  const overflow = await page.evaluate(() => document.documentElement.scrollWidth > document.documentElement.clientWidth + 2);
  expect(overflow).toBeFalsy();
  await page.screenshot({ path: `artifacts/screenshots/home-ar-${testInfo.project.name}.png`, fullPage: true });
});
