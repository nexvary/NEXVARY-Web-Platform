import { Head, Link } from '@inertiajs/react';
import SecurityIcon from '../components/security-icon';
import SiteShell from '../components/site-shell';

const products = [
  { name: 'Audio Shield', text: 'Privacy-aware audio protection and awareness tooling.', tag: 'PRIVACY', icon: 'audio' as const },
  { name: 'Tower Guard', text: 'Cellular anomaly awareness with professional validation guidance.', tag: 'CELLULAR', icon: 'tower' as const },
  { name: 'SafeScan', text: 'Browser-side zero-storage static inspection with optional hash reputation.', tag: 'ZERO-STORAGE', icon: 'shield' as const },
  { name: 'Threat Intelligence', text: 'Curated security intelligence and advisory surfaces.', tag: 'INTEL', icon: 'intel' as const },
  { name: 'AI Intelligence', text: 'Focused AI security and technology intelligence.', tag: 'AI', icon: 'ai' as const },
  { name: 'DFIR Lab', text: 'Structured digital-forensics workflows and investigation support.', tag: 'FORENSICS', icon: 'forensics' as const },
];

export default function Apps() {
  const ar = document.documentElement.lang.startsWith('ar');

  return (
    <SiteShell>
      <Head title={ar ? 'تطبيقات NEXVARY' : 'NEXVARY Apps'}>
        <meta name="description" content="NEXVARY security applications and privacy-first tools." />
        <link rel="canonical" href="https://nexvary.com/apps" />
      </Head>
      <main className="nx-section nx-page-body">
        <div className="nx-section-title"><p>APPLICATION ECOSYSTEM</p><h1>{ar ? 'تطبيقات أمنية بواجهة موحدة' : 'Security applications, one platform'}</h1></div>
        <div className="nx-grid">
          {products.map((product) => (
            <article className="nx-card nx-app-card" key={product.name}>
              <SecurityIcon name={product.icon} />
              <div className="nx-card-tag">{product.tag}</div>
              <h2>{product.name}</h2>
              <p>{product.text}</p>
              {product.name === 'SafeScan' ? <Link className="nx-card-link" href="/safescan">{ar ? 'فتح SafeScan' : 'Open SafeScan'} →</Link> : <span className="nx-card-status">CONTROLLED RELEASE</span>}
            </article>
          ))}
        </div>
      </main>
    </SiteShell>
  );
}
