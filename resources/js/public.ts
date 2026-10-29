const languageSwitches = document.querySelectorAll<HTMLSelectElement>('[data-language-switch]');

for (const select of languageSwitches) {
  select.addEventListener('change', () => {
    const url = new URL(window.location.href);
    url.searchParams.set('lang', select.value);
    window.location.assign(url.toString());
  });
}

for (const button of document.querySelectorAll<HTMLButtonElement>('[data-back-button]')) {
  button.addEventListener('click', () => {
    try {
      const referrer = document.referrer ? new URL(document.referrer) : null;
      if (window.history.length > 1 && referrer?.origin === window.location.origin) {
        window.history.back();
        return;
      }
    } catch {
      // Use the safe home fallback below.
    }
    window.location.assign(document.documentElement.lang === 'ar' ? '/?lang=ar' : '/');
  });
}


const navigation = document.querySelector<HTMLElement>('#primary-nav');
const menuToggle = document.querySelector<HTMLButtonElement>('[data-menu-toggle]');
if (navigation && menuToggle) {
  navigation.setAttribute('data-collapsible', '');
  menuToggle.addEventListener('click', () => {
    const open = menuToggle.getAttribute('aria-expanded') !== 'true';
    menuToggle.setAttribute('aria-expanded', String(open));
    navigation.toggleAttribute('data-open', open);
  });
  navigation.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') {
      menuToggle.setAttribute('aria-expanded', 'false');
      navigation.removeAttribute('data-open');
      menuToggle.focus();
    }
  });
}

// Retain the selected locale even when following a clean, crawlable internal URL.
if (document.documentElement.lang === 'ar') {
  for (const anchor of document.querySelectorAll<HTMLAnchorElement>('a[href^="/"]')) {
    const target = new URL(anchor.href);
    if (target.origin === window.location.origin && !target.hash) {
      target.searchParams.set('lang', 'ar');
      anchor.href = target.toString();
    }
  }
}
