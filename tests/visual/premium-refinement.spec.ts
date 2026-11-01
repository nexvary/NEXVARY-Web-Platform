import { test, expect } from '@playwright/test';
import AxeBuilder from '@axe-core/playwright';

const paths = ['/', '/about', '/services', '/research', '/cybersecurity', '/artificial-intelligence', '/contact', '/privacy', '/terms', '/apps', '/our-work', '/safescan'];
for (const locale of ['en', 'ar']) {
  test(`premium public pages, multicolor SVG and locale ${locale}`, async ({ page }, info) => {
    test.setTimeout(180_000);
    const errors: string[] = [];
    page.on('pageerror', error => errors.push(error.message));
    page.on('console', message => { if (message.type() === 'error') errors.push(message.text()); });
    // Production checks must not label a post-deployment capture as a before image.
    if (!String(info.project.use.baseURL).startsWith('https://nexvary.com')) {
      await page.goto(`https://nexvary.com/?lang=${locale}`, { waitUntil: 'networkidle' });
      await page.screenshot({ path: `artifacts/screenshots/premium-before-${locale}-${info.project.name}.png`, fullPage: true });
    }
    for (const path of paths) {
      const response = await page.goto(`${path}?lang=${locale}`, { waitUntil: 'networkidle' });
      expect(response?.status(), path).toBe(200);
      await expect(page.locator('html')).toHaveAttribute('dir', locale === 'ar' ? 'rtl' : 'ltr');
      await expect(page.locator('main#main-content')).toHaveCount(1);
      await expect(page.locator('h1')).toHaveCount(1);
      expect(await page.evaluate(() => document.documentElement.scrollWidth <= innerWidth + 2), path).toBeTruthy();
      await expect(page.locator('.nc-footer a[href*="github.com"]')).toHaveCount(0);
      await expect(page.locator('.nc-footer-card')).toHaveCount(14);
      await expect(page.locator('.nc-footer-card svg')).toHaveCount(14);
      await expect(page.locator('.nc-footer a[href="https://nexvary.com/"]')).toHaveCount(0);
      await expect(page.locator('a[href*="wyobiz.wyo.gov"]')).toHaveCount(0);
      if (await page.locator('.nc-identity').count()) {
        await expect(page.locator('.nc-identity dl')).toContainText('2026');
        await expect(page.locator('.nc-identity dl')).not.toContainText('October 28');
        await expect(page.locator('.nc-identity dl')).not.toContainText('٢٨ أكتوبر');
      }
      await expect(page.locator('.nc-registry-note')).toHaveCount(0);
      if (path === '/safescan') {
        await expect(page.locator('.nx-radar-dial')).toHaveCount(1);
        await expect(page.locator('.nx-command-meta > span')).toHaveCount(3);
        for (const fact of await page.locator('.nx-command-meta > span').all()) await expect(fact).toBeVisible();
        await page.emulateMedia({reducedMotion:'reduce'});
        await expect(page.locator('.nx-safescan-radar .nx-sweep')).toHaveCSS('animation-name','none');
      }
      const icons = page.locator('main svg.nc-icon');
      expect(await icons.count(), path).toBeGreaterThan(0);
      const colors = await icons.evaluateAll(nodes => nodes.map(svg => {
        const tones = new Set(Array.from(svg.querySelectorAll('path,rect,circle')).flatMap(node => {
          const style = getComputedStyle(node);
          return [style.stroke, style.fill].filter(color => color !== 'none');
        }));
        return tones.size;
      }));
      expect(colors.every(count => count >= 2), path).toBeTruthy();
      for (const icon of await icons.all()) await expect(icon).toHaveAttribute('aria-hidden', 'true');
      const axe = await new AxeBuilder({ page }).withTags(['wcag2a','wcag2aa','wcag21aa']).analyze();
      expect(axe.violations, path).toEqual([]);
      await page.screenshot({ path: `artifacts/screenshots/premium-after-${path.replaceAll('/', '_') || 'home'}-${locale}-${info.project.name}.png`, fullPage: true });
    }
    expect(errors).toEqual([]);
  });
  test(`narrow viewport and card alignment ${locale}`, async ({ page }) => {
    for (const width of [320, 375, 600, 768, 900, 1024, 1440, 1920]) {
      await page.setViewportSize({width, height: 1000});
      await page.goto(`/?lang=${locale}`);
      expect(await page.evaluate(() => document.documentElement.scrollWidth <= innerWidth + 2), `${width}`).toBeTruthy();
      const cards = await page.locator('.nc-capabilities article').evaluateAll(nodes => nodes.map(node => {
        const r=node.getBoundingClientRect();return {top:r.top,height:r.height};
      }));
      for (const card of cards) for (const peer of cards.filter(peer => Math.abs(peer.top-card.top)<2)) expect(Math.abs(peer.height-card.height)).toBeLessThan(2);
      if (width <= 900) {
        const image = page.locator('.nc-hero-picture img');
        expect(await image.evaluate((node:HTMLImageElement) => {
          const r=node.getBoundingClientRect();
          return getComputedStyle(node).objectFit === 'contain' && Math.abs(r.width/r.height - 1.5)<0.01;
        })).toBeTruthy();
      }
    }
    await page.setViewportSize({width:375,height:900});
    await page.goto(`/?lang=${locale}`);
    await page.locator('.nc-menu summary').click();
    await expect(page.locator('.nc-menu nav')).toBeVisible();
    await page.locator('.nc-menu nav a').nth(1).click();
    await expect(page).toHaveURL(new RegExp(`/about${locale==='ar'?'\\?lang=ar':''}$`));
    await page.locator('.nc-language').click();
    await expect(page.locator('html')).toHaveAttribute('lang',locale==='ar'?'en':'ar');
    await page.goto(`/?lang=${locale}`);
    await page.emulateMedia({reducedMotion:'reduce'});
    await expect(page.locator('.nc-grid article').first()).toHaveCSS('transition-duration','0s');
  });
}
