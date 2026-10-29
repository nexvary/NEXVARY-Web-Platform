import { expect, test } from '@playwright/test';
import AxeBuilder from '@axe-core/playwright';

const paths = ['/', '/about', '/our-work', '/artificial-intelligence', '/cybersecurity', '/research', '/contact', '/our-work/foodguard', '/our-work/guardian-studio', '/our-work/nexvary-mr'];

test('language selection changes live content and preserves Arabic on project navigation', async ({ page }) => {
  await page.goto('/?lang=en');
  await page.getByLabel('Language', { exact: true }).selectOption('ar');
  await expect(page.locator('html')).toHaveAttribute('lang', 'ar');
  await expect(page.locator('html')).toHaveAttribute('dir', 'rtl');
  await page.getByRole('link', { name: 'اكتشف أعمالنا', exact: true }).click();
  await expect(page.locator('html')).toHaveAttribute('lang', 'ar');
  await page.getByRole('link', { name: 'NEXVARY FoodGuard', exact: true }).click();
  await expect(page.locator('html')).toHaveAttribute('lang', 'ar');
  await page.getByLabel('Language', { exact: true }).selectOption('en');
  await expect(page.locator('html')).toHaveAttribute('lang', 'en');
});

test('corporate pages pass WCAG AA automated checks in both languages', async ({ page }) => {
  test.setTimeout(180_000);
  for (const locale of ['en', 'ar']) {
    for (const path of paths) {
      await page.goto(`${path}?lang=${locale}`, { waitUntil: 'networkidle' });
      const result = await new AxeBuilder({ page }).withTags(['wcag2a', 'wcag2aa', 'wcag21a', 'wcag21aa']).analyze();
      expect(result.violations, `${path} (${locale}): ${JSON.stringify(result.violations.map(v => ({ id: v.id, nodes: v.nodes.map(n => n.target) })))}`).toEqual([]);
    }
  }
});

test('small phone, tablet and wide wall retain all content', async ({ page }) => {
  for (const width of [320, 768, 1920]) {
    await page.setViewportSize({ width, height: 1000 });
    for (const locale of ['en', 'ar']) {
      await page.goto(`/?lang=${locale}`, { waitUntil: 'networkidle' });
      expect(await page.evaluate(() => document.documentElement.scrollWidth <= document.documentElement.clientWidth + 1)).toBeTruthy();
      await expect(page.getByRole('heading', { level: 1 })).toBeVisible();
      await page.screenshot({ path: `artifacts/screenshots/wall-${width}-${locale}.png`, fullPage: true });
    }
  }
});

test('every first-party asset loaded by corporate home succeeds', async ({ page }) => {
  const failures: string[] = [];
  page.on('response', response => { if (response.url().startsWith('http://127.0.0.1:8000') && response.status() >= 400) failures.push(`${response.status()} ${response.url()}`); });
  page.on('pageerror', error => failures.push(error.message));
  await page.goto('/?lang=ar', { waitUntil: 'networkidle' });
  await page.evaluate(() => document.fonts.ready);
  expect(failures).toEqual([]);
  const css = await page.locator('body').evaluate(el => ({ background: getComputedStyle(el).backgroundColor, direction: getComputedStyle(el).direction }));
  expect(css.direction).toBe('rtl');
  expect(css.background).toBe('rgb(233, 221, 200)');
});
