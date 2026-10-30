import { test, expect } from '@playwright/test';

const slugs = ['nexvary-recorder-studio', 'nexvary-foodguard', 'nexvary-promptforge', 'nexvary-realestate-ai-os', 'nexvary-meta-security'];
for (const locale of ['en', 'ar']) {
  test(`development projects without downloads ${locale}`, async ({ page, request }, info) => {
    await page.goto(`/our-work?lang=${locale}`, { waitUntil: 'networkidle' });
    await expect(page.locator('html')).toHaveAttribute('dir', locale === 'ar' ? 'rtl' : 'ltr');
    for (const slug of slugs) {
      const card = page.locator('article').filter({ has: page.locator(`a[href*="/our-work/${slug}"]`) });
      await expect(card).toHaveCount(1);
      await expect(card).toContainText(locale === 'ar' ? 'قيد التطوير' : 'In Development');
      await expect(card.locator('a[href*="/download"],a[href*=".apk"],a[href*="github.com"]')).toHaveCount(0);
      expect((await request.get(`/our-work/${slug}/download`)).status()).toBe(404);
      await page.goto(`/our-work/${slug}?lang=${locale}`);
      await expect(page.locator('h1')).toHaveCount(1);
      await expect(page.locator('main')).toContainText(locale === 'ar' ? 'قيد التطوير' : 'In Development');
      await expect(page.locator('main a[href*="/download"],main a[href*=".apk"]')).toHaveCount(0);
      await page.goto(`/our-work?lang=${locale}`);
    }
    expect(await page.evaluate(() => document.documentElement.scrollWidth <= innerWidth + 2)).toBeTruthy();
    await page.screenshot({ path: `artifacts/screenshots/projects-${locale}-${info.project.name}.png`, fullPage: true });
  });
}
