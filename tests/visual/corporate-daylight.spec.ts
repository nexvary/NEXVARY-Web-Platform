import { test, expect } from '@playwright/test';
import AxeBuilder from '@axe-core/playwright';

for (const locale of ['en', 'ar']) {
  test(`corporate daylight identity and accessibility ${locale}`, async ({ page, request }, info) => {
    const errors: string[] = [];
    page.on('pageerror', error => errors.push(error.message));
    page.on('console', message => { if (message.type() === 'error') errors.push(message.text()); });
    await page.goto(`/?lang=${locale}`, { waitUntil: 'networkidle' });
    await expect(page.locator('html')).toHaveAttribute('dir', locale === 'ar' ? 'rtl' : 'ltr');
    await expect(page.locator('h1')).toHaveCount(1);
    await expect(page.locator('.nc-brand img')).toHaveAttribute('src', '/assets/corporate/seal-nav.webp');
    await expect(page.locator('.nx-brand-mark,.nx-world-stage,.nx-radar,.nx-command-home')).toHaveCount(0);
    await expect(page.locator('main')).not.toContainText(/Audio Shield|Tower Guard|SafeScan|DEMONSTRATION DATA/);
    const hero = page.locator('.nc-hero-picture img');
    expect(await hero.evaluate((image: HTMLImageElement) => image.complete && image.naturalWidth > 0)).toBeTruthy();
    expect(await page.evaluate(() => document.documentElement.scrollWidth <= innerWidth + 2)).toBeTruthy();
    const imageSource = await hero.evaluate((image: HTMLImageElement) => image.currentSrc);
    if (info.project.name === 'android15') expect(imageSource).not.toMatch(/-1536\.(avif|webp|jpg)$/);
    for (const path of ['/favicon.ico', '/assets/corporate/seal-16.png', '/assets/corporate/seal-32.png', '/assets/corporate/seal-48.png', '/apple-touch-icon.png', '/site.webmanifest']) {
      expect((await request.get(path)).ok()).toBeTruthy();
    }
    expect(await page.locator('meta[property="og:image"]').getAttribute('content')).toContain('seal-512.png');
    expect(await page.locator('script[type="application/ld+json"]').textContent()).not.toContain('2026-002118182');
    const results = await new AxeBuilder({ page }).withTags(['wcag2a', 'wcag2aa', 'wcag21aa']).analyze();
    expect(results.violations).toEqual([]);
    expect(errors).toEqual([]);
    await page.screenshot({ path: `artifacts/screenshots/daylight-${locale}-${info.project.name}.png`, fullPage: true });
    for (const path of ['/about', '/services', '/research', '/cybersecurity', '/artificial-intelligence', '/contact', '/privacy', '/terms']) {
      const response = await request.get(`${path}?lang=${locale}`);
      expect(response.status(), path).toBe(200);
    }
    await page.locator('.nc-actions a').first().click();
    await expect(page).toHaveURL(new RegExp(`/about${locale === 'ar' ? '\\?lang=ar' : ''}$`));
    expect(await page.locator('html').getAttribute('lang')).toBe(locale);
  });
}
