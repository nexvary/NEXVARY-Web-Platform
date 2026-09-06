import { Head, Link } from '@inertiajs/react';

const services = [
  ['TSCM', 'RF & counter-surveillance operations'],
  ['Cybersecurity', 'Hardening, assessment and incident response'],
  ['SafeScan', 'Zero-storage browser-side file inspection'],
  ['Audio Shield', 'Privacy-aware audio protection tools'],
  ['Tower Guard', 'Cellular anomaly awareness and validation'],
  ['Digital Forensics', 'Structured evidence and investigation workflows'],
];

export default function Home() {
  const isArabic = document.documentElement.lang.startsWith('ar');

  return (
    <>
      <Head title="Security Beyond the Visible" />
      <main className="nx-page">
        <header className="nx-topbar">
          <Link className="nx-brand" href="/" aria-label="NEXVARY home">
            <span className="nx-brand-mark">N</span>
            <span>NEXVARY</span>
          </Link>
          <nav className="nx-nav" aria-label="Primary navigation">
            <Link href="/about">{isArabic ? 'عنّا' : 'About'}</Link>
            <Link href="/apps">{isArabic ? 'التطبيقات' : 'Apps'}</Link>
            <Link href="/safescan">SafeScan</Link>
            <a href="https://nexvary.com/">nexvary.com</a>
          </nav>
        </header>

        <section className="nx-hero">
          <div className="nx-hero-copy">
            <p className="nx-kicker">NEXVARY SECURITY PLATFORM · STAGE 080</p>
            <h1>{isArabic ? 'الأمن أبعد مما تراه.' : 'Security Beyond the Visible.'}</h1>
            <p className="nx-lead">
              {isArabic
                ? 'منصة أمنية حديثة تجمع الأمن السيبراني، مكافحة التجسس، التحليل الجنائي الرقمي وأدوات الخصوصية داخل تجربة تقنية موحدة.'
                : 'A modern security platform unifying cybersecurity, counter-surveillance, digital forensics and privacy tooling in one controlled experience.'}
            </p>
            <div className="nx-actions">
              <Link className="nx-btn nx-btn-primary" href="/apps">{isArabic ? 'استكشف المنصة' : 'Explore platform'}</Link>
              <Link className="nx-btn" href="/about">{isArabic ? 'عن NEXVARY' : 'About NEXVARY'}</Link>
            </div>
            <div className="nx-trust-row">
              <span>Laravel 13</span><span>React 19</span><span>Inertia 3</span><span>TypeScript</span><span>Zero-Storage</span>
            </div>
          </div>

          <div className="nx-command" aria-label="Security command visualization">
            <div className="nx-radar">
              <span className="nx-sweep" />
              <i className="p1" /><i className="p2" /><i className="p3" /><i className="p4" />
              <div className="nx-core">N</div>
            </div>
            <div className="nx-command-meta">
              <span><b>SECURE</b> release-gated development</span>
              <span><b>RTL</b> Arabic-first layout support</span>
              <span><b>CI</b> security + visual validation</span>
            </div>
          </div>
        </section>

        <section className="nx-section">
          <div className="nx-section-title"><p>CAPABILITIES</p><h2>{isArabic ? 'منظومة أمنية متعددة الطبقات' : 'Multi-layer security capability'}</h2></div>
          <div className="nx-grid">
            {services.map(([title, desc], index) => (
              <article className="nx-card" key={title}>
                <div className="nx-icon" aria-hidden="true">{String(index + 1).padStart(2, '0')}</div>
                <h3>{title}</h3><p>{desc}</p>
              </article>
            ))}
          </div>
        </section>

        <footer className="nx-footer">
          <span>© NEXVARY</span>
          <div><a href="mailto:info@nexvary.com">info@nexvary.com</a><a href="https://x.com/Nexvary">X</a><a href="https://www.youtube.com/@NexvaryInc">YouTube</a><a href="https://www.facebook.com/share/14p9krEn5ij/">Facebook</a></div>
        </footer>
      </main>
    </>
  );
}
