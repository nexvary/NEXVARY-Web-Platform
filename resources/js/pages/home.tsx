import { Head, Link } from '@inertiajs/react';
import { motion } from 'framer-motion';
import { AudioLines, Cloud, Eye, FileSearch, RadioTower, ScanSearch, ShieldCheck } from 'lucide-react';
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

const companySignals = [
  { title: 'Product engineering', text: 'Android, web and infrastructure products are developed through controlled repositories, automated checks and explicit release gates.' },
  { title: 'Cloud-ready systems', text: 'NEXVARY builds services that can move from validated prototypes to production cloud infrastructure without presenting prototypes as finished products.' },
  { title: 'Security by design', text: 'Authentication, least privilege, auditability, privacy boundaries and secure deployment are treated as product requirements.' },
];

export default function Home() {
  const ar = document.documentElement.lang.startsWith('ar');

  return (
    <SiteShell>
      <Head title={ar ? 'NEXVARY — شركة تقنية وأمن سيبراني' : 'NEXVARY — Security & Technology Company'}>
        <meta name="description" content="NEXVARY builds cybersecurity, privacy, digital-forensics and connected technology products with controlled engineering and cloud-ready infrastructure." />
        <link rel="canonical" href="https://nexvary.com/" />
      </Head>
      <main>
        <section className="nx-hero nx-hero-premium">
          <motion.div className="nx-hero-copy" initial={{ opacity: 0, x: ar ? 24 : -24 }} animate={{ opacity: 1, x: 0 }} transition={{ duration: 0.45 }}>
            <p className="nx-kicker">NEXVARY · SECURITY & TECHNOLOGY</p>
            <h1>{ar ? 'نبني تقنيات للحماية والتحكم والثقة الرقمية.' : 'Building technology for security, control and digital trust.'}</h1>
            <p className="nx-lead">
              {ar
                ? 'NEXVARY شركة تقنية تطور منتجات وحلولًا في الأمن السيبراني، الخصوصية، التحليل الجنائي الرقمي، الأنظمة المتصلة ومكافحة المراقبة الفنية، من النموذج الأولي الموثق إلى النشر الإنتاجي.'
                : 'NEXVARY is a technology company developing products and solutions across cybersecurity, privacy, digital forensics, connected systems and technical counter-surveillance—from validated prototypes to production deployment.'}
            </p>
            <div className="nx-actions">
              <Link className="nx-btn nx-btn-primary" href="/our-work">{ar ? 'شاهد ما نبنيه' : 'See what we build'}</Link>
              <Link className="nx-btn" href="/about">{ar ? 'عن الشركة' : 'About the company'}</Link>
            </div>
            <div className="nx-status-strip" aria-label="NEXVARY engineering focus">
              <span><i /> Product Engineering</span><span><i /> Cybersecurity</span><span><i /> Cloud</span><span><i /> Privacy</span>
            </div>
          </motion.div>

          <motion.div className="nx-command nx-command-globe" aria-label="Decorative technology visualization" initial={{ opacity: 0, scale: 0.96 }} animate={{ opacity: 1, scale: 1 }} transition={{ delay: 0.1, duration: 0.45 }}>
            <div className="nx-command-topline" aria-hidden="true"><strong>NEXVARY ENGINEERING</strong><span>SECURE PRODUCT LAYER</span></div>
            <div className="nx-rf-field" aria-hidden="true"><div className="nx-rf-sweep" /><i className="nx-rf-node node-1" /><i className="nx-rf-node node-2" /><i className="nx-rf-node node-3" /><i className="nx-rf-node node-4" /></div>
            <div className="nx-intel-rail nx-intel-left" aria-hidden="true"><div className="nx-intel-chip"><b>BUILD</b><span>product engineering</span></div><div className="nx-intel-chip"><b>SECURE</b><span>defense by design</span></div></div>
            <div className="nx-intel-rail nx-intel-right" aria-hidden="true"><div className="nx-intel-chip"><b>CLOUD</b><span>production infrastructure</span></div><div className="nx-intel-chip"><b>VERIFY</b><span>release-gated delivery</span></div></div>
            <div className="nx-orbit nx-orbit-a" /><div className="nx-orbit nx-orbit-b" />
            <div className="nx-globe-wrap" aria-hidden="true"><div className="nx-globe"><span className="nx-globe-lat lat-1" /><span className="nx-globe-lat lat-2" /><span className="nx-globe-lat lat-3" /><span className="nx-globe-long long-1" /><span className="nx-globe-long long-2" /><span className="nx-globe-long long-3" /><span className="nx-continent nx-continent-a" /><span className="nx-continent nx-continent-b" /><span className="nx-continent nx-continent-c" /><span className="nx-globe-pulse pulse-1" /><span className="nx-globe-pulse pulse-2" /><span className="nx-globe-pulse pulse-3" /><div className="nx-globe-core">N</div></div></div>
            <div className="nx-command-meta"><span><b>PRODUCT</b>controlled lifecycle</span><span><b>CLOUD</b>scalable architecture</span><span><b>SECURE</b>verification gates</span></div>
          </motion.div>
        </section>

        <section className="nx-section">
          <div className="nx-section-title"><p>WHAT WE BUILD</p><h2>{ar ? 'منتجات وقدرات تقنية قابلة للتطوير' : 'Products and technical capabilities built to evolve'}</h2><p className="nx-lead">{ar ? 'نوضح حالة كل مشروع بدل تقديم التجارب كمنتجات مكتملة، وننشر الإصدارات المعتمدة فقط.' : 'We distinguish prototypes, controlled releases and available products, and publish only verified release states.'}</p></div>
          <div className="nx-grid">{capabilities.map(({ title, desc, icon: Icon }) => <NxCard key={title} interactive className="nx-card"><div className="nx-icon" aria-hidden="true"><Icon size={22} strokeWidth={1.8} /></div><h3>{title}</h3><p>{desc}</p></NxCard>)}</div>
        </section>

        <section className="nx-section nx-startup-proof">
          <div className="nx-section-title"><p>ENGINEERING APPROACH</p><h2>{ar ? 'من الفكرة إلى بنية إنتاجية موثوقة' : 'From an idea to dependable production infrastructure'}</h2></div>
          <div className="nx-grid">{companySignals.map((item, index) => <article className="nx-card" key={item.title}><div className="nx-icon" aria-hidden="true">{index === 1 ? <Cloud size={22} /> : <ShieldCheck size={22} />}</div><h3>{item.title}</h3><p>{item.text}</p></article>)}</div>
          <div className="nx-actions"><Link className="nx-btn nx-btn-primary" href="/our-work">{ar ? 'استكشف المشاريع' : 'Explore projects'}</Link><Link className="nx-btn" href="/contact">{ar ? 'تواصل مع NEXVARY' : 'Contact NEXVARY'}</Link></div>
        </section>
      </main>
    </SiteShell>
  );
}
