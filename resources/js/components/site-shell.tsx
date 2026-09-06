import { Link, router } from '@inertiajs/react';
import type { PropsWithChildren } from 'react';

const languages = ['en', 'ar', 'tr', 'ru', 'de', 'it', 'es'] as const;

export default function SiteShell({ children }: PropsWithChildren) {
  const ar = document.documentElement.lang.startsWith('ar');
  const switchLocale = (locale: (typeof languages)[number]) => router.post(`/locale/${locale}`, {}, { preserveScroll: true });
  const goBack = () => {
    try {
      if (document.referrer && new URL(document.referrer).origin === window.location.origin) {
        window.history.back();
        return;
      }
    } catch {
      // Use the safe home fallback.
    }
    router.visit('/');
  };

  return <div className="nx-page"><header className="nx-topbar" data-testid="site-header"><Link className="nx-brand" href="/" aria-label="NEXVARY home"><span className="nx-brand-mark" aria-hidden="true">N</span><span>NEXVARY</span></Link><nav className="nx-nav" aria-label={ar?'التنقل الرئيسي':'Primary navigation'}><Link href="/">{ar?'الرئيسية':'Home'}</Link><Link href="/services">{ar?'الخدمات':'Services'}</Link><Link href="/apps">{ar?'التطبيقات':'Apps'}</Link><Link href="/safescan">SafeScan</Link><Link href="/threat-intelligence">{ar?'التهديدات':'Threat Intel'}</Link><Link href="/about">{ar?'عنّا':'About'}</Link><Link href="/contact">{ar?'تواصل':'Contact'}</Link></nav><div className="nx-top-actions"><button type="button" className="nx-back" onClick={goBack}>← {ar?'رجوع':'Back'}</button><label className="nx-language"><span className="sr-only">Language</span><select aria-label="Language" defaultValue={document.documentElement.lang.slice(0,2)} onChange={(event)=>switchLocale(event.target.value as (typeof languages)[number])}>{languages.map((language)=><option key={language} value={language}>{language.toUpperCase()}</option>)}</select></label></div></header>{children}<footer className="nx-footer"><div><strong>NEXVARY</strong><span>{ar?'الأمن أبعد مما تراه.':'Security beyond the visible.'}</span></div><div className="nx-footer-links"><a href="https://nexvary.com/">Website</a><a href="https://www.facebook.com/share/14p9krEn5ij/" rel="noreferrer" target="_blank">Facebook</a><a href="mailto:info@nexvary.com">Email</a><a href="https://www.youtube.com/@NexvaryInc" rel="noreferrer" target="_blank">YouTube</a><a href="https://x.com/Nexvary" rel="noreferrer" target="_blank">X</a><Link href="/privacy">Privacy</Link></div></footer></div>;
}
