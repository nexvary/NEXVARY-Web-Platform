import { Head, Link } from '@inertiajs/react';
import { motion } from 'framer-motion';
import { AudioLines, Eye, FileSearch, RadioTower, ScanSearch, ShieldCheck } from 'lucide-react';
import SiteShell from '../components/site-shell';
import { NxCard } from '../components/ui/nx-card';

const capabilities = [
  { title: 'TSCM', desc: 'RF & counter-surveillance operations', icon: Eye },
  { title: 'Cybersecurity', desc: 'Hardening, assessment and incident response', icon: ShieldCheck },
  { title: 'SafeScan', desc: 'Zero-storage browser-side file inspection', icon: ScanSearch },
  { title: 'Audio Shield', desc: 'Privacy-aware audio protection tools', icon: AudioLines },
  { title: 'Tower Guard', desc: 'Cellular anomaly awareness and validation', icon: RadioTower },
  { title: 'Digital Forensics', desc: 'Structured evidence and investigation workflows', icon: FileSearch },
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
          <motion.div className="nx-hero-copy" initial={{ opacity: 0, x: ar ? 24 : -24 }} animate={{ opacity: 1, x: 0 }} transition={{ duration: 0.45 }}>
            <p className="nx-kicker">NEXVARY SECURITY PLATFORM · MODERN STACK</p>
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
              <span>Laravel 13</span><span>React 19</span><span>Inertia 3</span><span>TypeScript</span><span>Motion UI</span><span>Release Gate</span>
            </div>
          </motion.div>

          <motion.div className="nx-command" aria-label="Security command visualization" initial={{ opacity: 0, scale: 0.96 }} animate={{ opacity: 1, scale: 1 }} transition={{ delay: 0.1, duration: 0.45 }}>
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
          </motion.div>
        </section>

        <section className="nx-section">
          <div className="nx-section-title"><p>CAPABILITIES</p><h2>{ar ? 'منظومة أمنية متعددة الطبقات' : 'A multi-layer security capability'}</h2></div>
          <div className="nx-grid">
            {capabilities.map(({ title, desc, icon: Icon }) => (
              <NxCard key={title} interactive className="nx-card">
                <div className="nx-icon" aria-hidden="true"><Icon size={22} strokeWidth={1.8} /></div>
                <h3>{title}</h3><p>{desc}</p>
              </NxCard>
            ))}
          </div>
        </section>
      </main>
    </SiteShell>
  );
}
