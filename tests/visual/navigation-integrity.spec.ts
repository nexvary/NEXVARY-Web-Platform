import { expect, test } from '@playwright/test';

const publicTabs = [
  { href: '/', label: /home|الرئيسية/i },
  { href: '/services', label: /services|الخدمات/i },
  { href: '/our-work', label: /our work|أعمالنا/i },
  { href: '/apps', label: /apps|التطبيقات/i },
  { href: '/safescan', label: /safescan/i },
  { href: '/about', label: /about|عنّا/i },
  { href: '/contact', label: /contact|تواصل معنا/i },
];

for (const tab of publicTabs) {
  test(`public tab ${tab.href} opens by clicking the real navigation control`, async ({ page }) => {
    await page.goto('/', { waitUntil: 'networkidle' });
    const link = page.getByRole('navigation').getByRole('link', { name: tab.label }).first();
    await expect(link).toBeVisible();
    await link.click();
    await expect(page).toHaveURL(new RegExp(`${tab.href === '/' ? '\\/$' : `${tab.href.replaceAll('/', '\\/')}$`}`));
    await expect(page.locator('body')).toBeVisible();
  });
}

test('every internal public link discovered from every public page resolves successfully', async ({ page, request }) => {
  const targets = new Set<string>();

  for (const tab of publicTabs) {
    await page.goto(tab.href, { waitUntil: 'networkidle' });
    const hrefs = await page.locator('a[href]').evaluateAll((anchors) => anchors.map((anchor) => anchor.getAttribute('href')).filter(Boolean) as string[]);

    for (const href of hrefs) {
      if (href.startsWith('#') || href.startsWith('mailto:') || href.startsWith('tel:')) continue;
      const target = new URL(href, page.url());
      if (target.origin !== new URL(page.url()).origin) continue;
      if (target.pathname.startsWith('/secure-control') || target.pathname.startsWith('/secure-access')) continue;
      targets.add(`${target.pathname}${target.search}`);
    }
  }

  expect(targets.size).toBeGreaterThan(0);

  for (const target of targets) {
    const response = await request.get(target, { maxRedirects: 5 });
    expect(response.status(), `Internal link ${target} returned HTTP ${response.status()}`).toBeLessThan(400);
  }
});

test('public pages contain no placeholder links or visually dead controls', async ({ page }) => {
  for (const tab of publicTabs) {
    await page.goto(tab.href, { waitUntil: 'networkidle' });

    const placeholders = await page.locator('a[href="#"], a[href=""], a[href^="javascript:"]').count();
    expect(placeholders, `${tab.href} contains placeholder anchors`).toBe(0);

    const deadControls = await page.locator('a, button, select, input[type="button"], input[type="submit"]').evaluateAll((nodes) => nodes.filter((node) => {
      const element = node as HTMLElement;
      const style = getComputedStyle(element);
      const rect = element.getBoundingClientRect();
      const visible = rect.width > 0 && rect.height > 0 && style.visibility !== 'hidden' && style.display !== 'none';
      if (!visible) return false;
      if (style.pointerEvents === 'none') return true;
      if (element instanceof HTMLAnchorElement) return !element.getAttribute('href');
      return false;
    }).length);

    expect(deadControls, `${tab.href} contains a visually dead interactive control`).toBe(0);
  }
});

test('every internal page exposes a working Back button', async ({ page }) => {
  for (const tab of publicTabs.filter((item) => item.href !== '/')) {
    await page.goto('/', { waitUntil: 'networkidle' });
    await page.getByRole('navigation').getByRole('link', { name: tab.label }).first().click();
    await expect(page).toHaveURL(new RegExp(`${tab.href.replaceAll('/', '\\/')}$`));
    const back = page.getByTestId('back-button');
    await expect(back).toBeVisible();
    await back.click();
    await expect(page).toHaveURL(/\/$/);
  }
});
