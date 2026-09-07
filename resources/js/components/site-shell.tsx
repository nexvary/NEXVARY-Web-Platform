import { Link, router } from '@inertiajs/react';
import { ArrowLeft, ArrowRight, Facebook, Globe2, Mail, Youtube } from 'lucide-react';
import type { PropsWithChildren } from 'react';

const languages = ['en', 'ar', 'tr', 'ru', 'de', 'it', 'es'] as const;

export default function SiteShell({ children }: PropsWithChildren) {
  const ar = document.documentElement.lang.startsWith('ar');
  const isHome = window.location.pathname === '/';

  const switchLocale = (locale: (typeof languages)[number]) => {
    router.post(`/locale/${locale}`, {}, { preserveScroll: true });
  };

  const goBack = () => {
    try {
      const referrer = document.referrer ? new URL(document.referrer) : null;
      if (window.history.length > 1 && referrer?.origin === window.location.origin) {
        window.history.back();
        return;
      }
    } catch {
      // Ignore malformed/blocked referrer and use the safe home fallback.
    }
    router.visit('/');
  };

  return (
    <div className="nx-page">
      <header className="nx-topbar" data-testid="site-header">
        <Link className="nx-brand" href="/" aria-label="NEXVARY home">
          <span className="nx-brand-mark" aria-hidden="true">N</span>
          <span>NEXVARY</span>
        </Link>
        <nav className="nx-nav" aria-label={ar ? 'التنقل الرئيسي' : 'Primary navigation'}>
          <Link href="/">{ar ? 'الرئيسية' : 'Home'}</Link>
          <Link href="/services">{ar ? 'الخدمات' : 'Services'}</Link>
          <Link href="/apps">{ar ? 'التطبيقات' : 'Apps'}</Link>
          <Link href="/safescan">SafeScan</Link>
          <Link href="/about">{ar ? 'عنّا' : 'About'}</Link>
          <Link href="/contact">{ar ? 'تواصل معنا' : 'Contact'}</Link>
        </nav>
        <label className="nx-language">
          <span className="sr-only">Language</span>
          <select aria-label="Language" defaultValue={document.documentElement.lang.slice(0, 2)} onChange={(event) => switchLocale(event.target.value as (typeof languages)[number])}>
            {languages.map((language) => <option key={language} value={language}>{language.toUpperCase()}</option>)}
          </select>
        </label>
      </header>

      {!isHome && (
        <div className="nx-back-row">
          <button type="button" className="nx-back-button" onClick={goBack} data-testid="back-button" aria-label={ar ? 'رجوع' : 'Back'}>
            {ar ? <ArrowRight size={18} aria-hidden="true" /> : <ArrowLeft size={18} aria-hidden="true" />}
            <span>{ar ? 'رجوع' : 'Back'}</span>
          </button>
        </div>
      )}

      {children}
      <footer className="nx-footer">
        <div><strong>NEXVARY</strong><span>{ar ? 'الأمن أبعد مما تراه.' : 'Security beyond the visible.'}</span></div>
        <div className="nx-footer-links" aria-label={ar ? 'روابط NEXVARY الرسمية' : 'Official NEXVARY links'}>
          <a href="https://nexvary.com/" aria-label="Website"><Globe2 size={17} aria-hidden="true" /><span>Website</span></a>
          <a href="https://www.facebook.com/share/14p9krEn5ij/" rel="noreferrer" target="_blank" aria-label="Facebook"><Facebook size={17} aria-hidden="true" /><span>Facebook</span></a>
          <a href="mailto:info@nexvary.com" aria-label="Email"><Mail size={17} aria-hidden="true" /><span>Email</span></a>
          <a href="https://www.youtube.com/@NexvaryInc" rel="noreferrer" target="_blank" aria-label="YouTube"><Youtube size={17} aria-hidden="true" /><span>YouTube</span></a>
          <a href="https://x.com/Nexvary" rel="noreferrer" target="_blank" aria-label="X"><span className="nx-x-icon" aria-hidden="true">X</span><span>X</span></a>
        </div>
      </footer>
    </div>
  );
}
