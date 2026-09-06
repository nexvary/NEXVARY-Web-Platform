import { expect, test } from '@playwright/test';

const pages = ['/', '/services', '/about', '/apps', '/safescan'];

for (const path of pages) {
  test(`${path} has no horizontal overflow`, async ({ page }, testInfo) => {
    await page.goto(path, { waitUntil: 'networkidle' });
    await expect(page.locator('body')).toBeVisible();
    await expect(page.getByTestId('site-header')).toBeVisible();

    const overflow = await page.evaluate(() => document.documentElement.scrollWidth > document.documentElement.clientWidth + 2);
    expect(overflow).toBeFalsy();

    const clippedControls = await page.locator('a, button, input, select, textarea').evaluateAll((nodes) => nodes.some((node) => {
      const rect = node.getBoundingClientRect();
      return rect.width > 0 && (rect.left < -2 || rect.right > document.documentElement.clientWidth + 2);
    }));
    expect(clippedControls).toBeFalsy();

    const name = path === '/' ? 'home' : path.replaceAll('/', '');
    await page.screenshot({ path: `artifacts/screenshots/${name}-${testInfo.project.name}.png`, fullPage: true });
  });
}

test('Arabic RTL gate', async ({ page }, testInfo) => {
  await page.goto('/?lang=ar', { waitUntil: 'networkidle' });
  await expect(page.locator('html')).toHaveAttribute('dir', 'rtl');
  await expect(page.locator('html')).toHaveAttribute('lang', /ar/);
  await expect(page.getByRole('navigation')).toContainText('الخدمات');

  const overflow = await page.evaluate(() => document.documentElement.scrollWidth > document.documentElement.clientWidth + 2);
  expect(overflow).toBeFalsy();

  await page.screenshot({ path: `artifacts/screenshots/home-ar-${testInfo.project.name}.png`, fullPage: true });
});

test('Android 15 safe-area and tap target gate', async ({ page }) => {
  await page.goto('/', { waitUntil: 'networkidle' });
  const undersized = await page.locator('a, button, select').evaluateAll((nodes) => nodes.filter((node) => {
    const rect = node.getBoundingClientRect();
    return rect.width > 0 && rect.height > 0 && rect.height < 40;
  }).length);
  expect(undersized).toBeLessThanOrEqual(2);
});
