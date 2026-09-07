import { expect, test } from '@playwright/test';

const pages = ['/', '/services', '/our-work', '/about', '/contact', '/apps', '/safescan'];

for (const path of pages) {
  test(`${path} has no horizontal overflow or clipped controls`, async ({ page }, testInfo) => {
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

    const escapedIcons = await page.locator('a svg, button svg, .nx-icon svg').evaluateAll((icons) => icons.some((icon) => {
      const iconRect = icon.getBoundingClientRect();
      const host = icon.closest('a, button, .nx-icon');
      if (!host || iconRect.width === 0) return false;
      const hostRect = host.getBoundingClientRect();
      return iconRect.left < hostRect.left - 1 || iconRect.right > hostRect.right + 1 || iconRect.top < hostRect.top - 1 || iconRect.bottom > hostRect.bottom + 1;
    }));
    expect(escapedIcons).toBeFalsy();

    const name = path === '/' ? 'home' : path.replaceAll('/', '');
    await page.screenshot({ path: `artifacts/screenshots/${name}-${testInfo.project.name}.png`, fullPage: true });
  });
}

test('back button returns to the previous same-origin page', async ({ page }) => {
  await page.goto('/', { waitUntil: 'networkidle' });
  await page.getByRole('link', { name: /about|عنّا/i }).click();
  await expect(page).toHaveURL(/\/about$/);
  const back = page.getByTestId('back-button');
  await expect(back).toBeVisible();
  await back.click();
  await expect(page).toHaveURL(/\/$/);
});

test('Arabic RTL alignment gate', async ({ page }, testInfo) => {
  await page.goto('/?lang=ar', { waitUntil: 'networkidle' });
  await expect(page.locator('html')).toHaveAttribute('dir', 'rtl');
  await expect(page.locator('html')).toHaveAttribute('lang', /ar/);
  await expect(page.getByRole('navigation')).toContainText('الخدمات');

  const state = await page.evaluate(() => ({
    overflow: document.documentElement.scrollWidth > document.documentElement.clientWidth + 2,
    direction: getComputedStyle(document.body).direction,
    alignment: getComputedStyle(document.body).textAlign,
  }));
  expect(state.overflow).toBeFalsy();
  expect(state.direction).toBe('rtl');
  expect(state.alignment).toBe('right');

  await page.screenshot({ path: `artifacts/screenshots/home-ar-${testInfo.project.name}.png`, fullPage: true });
});

test('Android 15 viewport-safe tap target gate', async ({ page }) => {
  await page.goto('/', { waitUntil: 'networkidle' });
  const undersized = await page.locator('a, button, select').evaluateAll((nodes) => nodes.filter((node) => {
    const rect = node.getBoundingClientRect();
    return rect.width > 0 && rect.height > 0 && rect.height < 44;
  }).length);
  expect(undersized).toBe(0);

  const viewportMeta = await page.locator('meta[name="viewport"]').getAttribute('content');
  expect(viewportMeta).toContain('viewport-fit=cover');
});

test('About exposes all official NEXVARY channels', async ({ page }) => {
  await page.goto('/about', { waitUntil: 'networkidle' });
  for (const label of ['Website', 'Facebook', 'Email', 'YouTube', 'X']) {
    await expect(page.getByText(label, { exact: true }).first()).toBeVisible();
  }
});
