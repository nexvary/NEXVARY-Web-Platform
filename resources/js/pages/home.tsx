import { Head, Link } from '@inertiajs/react';
import SiteShell from '../components/site-shell';

const capabilities = [
  ['TSCM', 'RF & counter-surveillance operations'],
  ['Cybersecurity', 'Hardening, assessment and incident response'],
  ['SafeScan', 'Zero-storage browser-side file inspection'],
  ['Audio Shield', 'Privacy-aware audio protection tools'],
  ['Tower Guard', 'Cellular anomaly awareness and validation'],
  ['Digital Forensics', 'Structured evidence and investigation workflows'],
];

export default function Home() {
  const ar = document.documentElement.lang.startsWith('ar');

  return (
    <SiteShell>
      <Head title={ar ? 'NEXVARY — الأمن أبعد مما تراه' : 'NEXVARY — Security Beyond the Visible'}>
        <meta name="description" content="NEXVARY cybersecurity, counter-surveillance, digital forensics and privacy technology platform." />
        <link rel="canonical" href="https://nexvary.com/" />
      </Head>
      <main>
        <section className="nx-hero">
          <div className="nx-hero-copy">
            <p className="nx-kicker">NEXVARY SECURITY PLATFORM · STAGE 160</p>
            <h1>{ar ? 'الأمن أبعد مما تراه.' : 'Security Beyond the Visible.'}</h1>
            <p className="nx-lead">
              {ar
                ? 'منصة أمنية حديثة تجمع الأمن السيبراني، مكافحة التجسس، التحليل الجنائي الرقمي وأدوات الخصوصية داخل تجربة موحدة مبنية على اختبارات إصدار صارمة.'
                : 'A security platform unifying cybersecurity, counter-surveillance, digital forensics and privacy tooling behind a strict release-gated engineering process.'}
            </p>
            <div className="nx-actions">
              <Link className="nx-btn nx-btn-primary" href="/services">{ar ? 'استكشف الخدمات' : 'Explore services'}</Link>
              <Link className="nx-btn" href="/apps">{ar ? 'التطبيقات' : 'Applications'}</Link>
            </div>
            <div className="nx-trust-row" aria-label="Platform technologies">
              <span>Laravel 13</span><span>React 19</span><span>Inertia 3</span><span>TypeScript</span><span>Zero-Storage</span><span>Release Gate</span>
            </div>
          </div>

          <div className="nx-command" aria-label="Security command visualization">
            <div className="nx-radar">
              <span className="nx-sweep" />
              <i className="p1" /><i className="p2" /><i className="p3" /><i className="p4" />
              <div className="nx-core">N</div>
            </div>
            <div className="nx-command-meta">
              <span><b>SECURE</b>{ar ? 'بوابات إصدار واختبارات أمنية' : 'release-gated engineering'}</span>
              <span><b>RTL</b>{ar ? 'واجهة عربية أصلية' : 'native Arabic layout support'}</span>
              <span><b>VISUAL</b>{ar ? 'فحص مرئي آلي' : 'automated visual QA'}</span>
            </div>
          </div>
        </section>

        <section className="nx-section">
          <div className="nx-section-title"><p>CAPABILITIES</p><h2>{ar ? 'منظومة أمنية متعددة الطبقات' : 'A multi-layer security capability'}</h2></div>
          <div className="nx-grid">
            {capabilities.map(([title, desc], index) => (
              <article className="nx-card" key={title}>
                <div className="nx-icon" aria-hidden="true">{String(index + 1).padStart(2, '0')}</div>
                <h3>{title}</h3><p>{desc}</p>
              </article>
            ))}
          </div>
        </section>
      </main>
    </SiteShell>
  );
}
