import { Head, Link } from '@inertiajs/react';

const products = [
  { name: 'Audio Shield', text: 'Privacy-aware audio protection and awareness tooling.', tag: 'PRIVACY' },
  { name: 'Tower Guard', text: 'Cellular anomaly awareness with professional validation guidance.', tag: 'CELLULAR' },
  { name: 'SafeScan', text: 'Browser-side zero-storage static inspection with optional hash reputation.', tag: 'ZERO-STORAGE' },
  { name: 'Threat Intelligence', text: 'Curated security intelligence and advisory surfaces.', tag: 'INTEL' },
  { name: 'AI Intelligence', text: 'Focused AI security and technology intelligence.', tag: 'AI' },
  { name: 'DFIR Lab', text: 'Structured digital-forensics workflows and investigation support.', tag: 'FORENSICS' },
];

export default function Apps() {
  const ar = document.documentElement.lang.startsWith('ar');
  return (
    <>
      <Head title={ar ? 'تطبيقات NEXVARY' : 'NEXVARY Apps'} />
      <main className="nx-page">
        <header className="nx-topbar">
          <Link className="nx-brand" href="/"><span className="nx-brand-mark">N</span><span>NEXVARY</span></Link>
          <nav className="nx-nav"><Link href="/">{ar ? 'الرئيسية' : 'Home'}</Link><Link href="/about">{ar ? 'عنّا' : 'About'}</Link><Link href="/safescan">SafeScan</Link></nav>
        </header>
        <section className="nx-section" style={{paddingTop: '72px'}}>
          <div className="nx-section-title"><p>APPLICATION ECOSYSTEM</p><h2>{ar ? 'تطبيقات أمنية بواجهة موحدة' : 'Security applications, one platform'}</h2></div>
          <div className="nx-grid">
            {products.map((p) => <article className="nx-card" key={p.name}><div className="nx-icon">{p.tag.slice(0,2)}</div><h3>{p.name}</h3><p>{p.text}</p></article>)}
          </div>
          <div className="nx-actions"><button className="nx-btn" type="button" onClick={() => history.length > 1 ? history.back() : location.assign('/')}>{ar ? 'رجوع' : 'Back'}</button></div>
        </section>
      </main>
    </>
  );
}
