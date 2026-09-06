import { Head } from '@inertiajs/react';
import SiteShell from '../components/site-shell';

const links = [
  ['Website', 'https://nexvary.com/'],
  ['Facebook', 'https://www.facebook.com/share/14p9krEn5ij/'],
  ['Email', 'mailto:info@nexvary.com'],
  ['YouTube', 'https://www.youtube.com/@NexvaryInc'],
  ['X', 'https://x.com/Nexvary'],
];

const pillars = [
  ['01', 'Cybersecurity', 'Reducing attack surface and strengthening systems before incidents happen.'],
  ['02', 'Counter-Surveillance', 'Technical inspection and awareness for environments where confidentiality matters.'],
  ['03', 'Digital Forensics', 'Structured investigation and evidence handling with repeatable workflows.'],
  ['04', 'Privacy Technology', 'Tools and architecture that minimize unnecessary data exposure by design.'],
];

export default function About() {
  const ar = document.documentElement.lang.startsWith('ar');

  return (
    <SiteShell>
      <Head title={ar ? 'عن NEXVARY' : 'About NEXVARY'}>
        <meta name="description" content="About NEXVARY: cybersecurity, counter-surveillance, digital forensics and privacy-first security technology." />
        <link rel="canonical" href="https://nexvary.com/about" />
      </Head>
      <main className="nx-section nx-page-body">
        <section className="nx-about-hero">
          <div>
            <p className="nx-kicker">ABOUT NEXVARY</p>
            <h1>{ar ? 'تقنية أمنية مصممة لما لا يظهر للعين.' : 'Security technology for what is not immediately visible.'}</h1>
            <p className="nx-lead">{ar ? 'تجمع NEXVARY بين الأمن السيبراني، مكافحة التجسس الفني، التحليل الجنائي الرقمي وتقنيات الخصوصية لبناء طبقات حماية مترابطة.' : 'NEXVARY combines cybersecurity, technical counter-surveillance, digital forensics and privacy technology into connected layers of protection.'}</p>
          </div>
          <div className="nx-about-emblem" aria-hidden="true"><span>N</span></div>
        </section>

        <div className="nx-grid nx-about-grid">
          {pillars.map(([code, title, text]) => (
            <article className="nx-card" key={code}><div className="nx-icon">{code}</div><h2>{title}</h2><p>{text}</p></article>
          ))}
        </div>

        <section className="nx-contact-panel">
          <div><p className="nx-kicker">CONNECT</p><h2>{ar ? 'روابط NEXVARY الرسمية' : 'Official NEXVARY channels'}</h2></div>
          <div className="nx-social-grid">
            {links.map(([label, href]) => <a key={label} href={href} rel="noreferrer" target={href.startsWith('mailto:') ? undefined : '_blank'}><span>{label}</span><small>{href.replace('mailto:', '')}</small></a>)}
          </div>
        </section>
      </main>
    </SiteShell>
  );
}
