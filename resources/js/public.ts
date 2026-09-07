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
    window.location.assign('/');
  });
}
