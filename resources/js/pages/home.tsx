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
        <section className="nx-hero nx-hero-premium">
          <motion.div className="nx-hero-copy" initial={{ opacity: 0, x: ar ? 24 : -24 }} animate={{ opacity: 1, x: 0 }} transition={{ duration: 0.45 }}>
            <p className="nx-kicker">NEXVARY SECURITY INTELLIGENCE</p>
            <h1>{ar ? 'نرى ما لا يراه الآخرون.' : 'Security Beyond the Visible.'}</h1>
            <p className="nx-lead">
              {ar
                ? 'منصة أمنية متقدمة تجمع مكافحة التجسس، الأمن السيبراني، التحليل الجنائي الرقمي وتقنيات الخصوصية في تجربة واحدة فاخرة ومحمية.'
                : 'A premium security platform unifying counter-surveillance, cybersecurity, digital forensics and privacy technology in one protected experience.'}
            </p>
            <div className="nx-actions">
              <Link className="nx-btn nx-btn-primary" href="/services">{ar ? 'استكشف الخدمات' : 'Explore services'}</Link>
              <Link className="nx-btn" href="/apps">{ar ? 'التطبيقات' : 'Applications'}</Link>
            </div>
            <div className="nx-status-strip" aria-label="Platform capability status">
              <span><i /> TSCM</span>
              <span><i /> Cyber Defense</span>
              <span><i /> Digital Forensics</span>
              <span><i /> Privacy</span>
            </div>
          </motion.div>

          <motion.div className="nx-command nx-command-globe" aria-label="Decorative global security visualization" initial={{ opacity: 0, scale: 0.96 }} animate={{ opacity: 1, scale: 1 }} transition={{ delay: 0.1, duration: 0.45 }}>
            <div className="nx-command-topline" aria-hidden="true">
              <strong>NEXVARY COMMAND VISUAL</strong>
              <span>RF AWARENESS LAYER</span>
            </div>

            <div className="nx-rf-field" aria-hidden="true">
              <div className="nx-rf-sweep" />
              <i className="nx-rf-node node-1" />
              <i className="nx-rf-node node-2" />
              <i className="nx-rf-node node-3" />
              <i className="nx-rf-node node-4" />
            </div>

            <div className="nx-intel-rail nx-intel-left" aria-hidden="true">
              <div className="nx-intel-chip"><b>TSCM</b><span>{ar ? 'وعي طيفي متعدد الطبقات' : 'multi-layer spectrum awareness'}</span></div>
              <div className="nx-intel-chip"><b>RF</b><span>{ar ? 'تصور مسح لاسلكي' : 'radio sweep visualization'}</span></div>
            </div>

            <div className="nx-intel-rail nx-intel-right" aria-hidden="true">
              <div className="nx-intel-chip"><b>CYBER</b><span>{ar ? 'دفاع رقمي متكامل' : 'integrated cyber defense'}</span></div>
              <div className="nx-intel-chip"><b>DFIR</b><span>{ar ? 'مسارات أدلة منظمة' : 'structured evidence workflows'}</span></div>
            </div>

            <div className="nx-orbit nx-orbit-a" />
            <div className="nx-orbit nx-orbit-b" />
            <div className="nx-globe-wrap" aria-hidden="true">
              <div className="nx-globe">
                <span className="nx-globe-lat lat-1" />
                <span className="nx-globe-lat lat-2" />
                <span className="nx-globe-lat lat-3" />
                <span className="nx-globe-long long-1" />
                <span className="nx-globe-long long-2" />
                <span className="nx-globe-long long-3" />
                <span className="nx-continent nx-continent-a" />
                <span className="nx-continent nx-continent-b" />
                <span className="nx-continent nx-continent-c" />
                <span className="nx-globe-pulse pulse-1" />
                <span className="nx-globe-pulse pulse-2" />
                <span className="nx-globe-pulse pulse-3" />
                <div className="nx-globe-core">N</div>
              </div>
            </div>
            <div className="nx-command-meta">
              <span><b>GLOBAL</b>{ar ? 'وعي أمني متعدد الطبقات' : 'multi-layer security awareness'}</span>
              <span><b>VISUAL</b>{ar ? 'تصميم بصري ديناميكي' : 'dynamic intelligence visualization'}</span>
              <span><b>SECURE</b>{ar ? 'بوابات إصدار واختبارات' : 'release-gated engineering'}</span>
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
