import { Head } from '@inertiajs/react';
import { Facebook, Fingerprint, Globe2, Mail, Microscope, ShieldCheck, Youtube } from 'lucide-react';
import SiteShell from '../components/site-shell';

const links = [
  { label: 'Website', href: 'https://nexvary.com/', icon: Globe2 },
  { label: 'Facebook', href: 'https://www.facebook.com/share/14p9krEn5ij/', icon: Facebook },
  { label: 'Email', href: 'mailto:info@nexvary.com', icon: Mail },
  { label: 'YouTube', href: 'https://www.youtube.com/@NexvaryInc', icon: Youtube },
  { label: 'X', href: 'https://x.com/Nexvary', icon: null },
];

const pillars = [
  { title: 'Cybersecurity', text: 'Reducing attack surface and strengthening systems before incidents happen.', icon: ShieldCheck },
  { title: 'Counter-Surveillance', text: 'Technical inspection and awareness for environments where confidentiality matters.', icon: Fingerprint },
  { title: 'Digital Forensics', text: 'Structured investigation and evidence handling with repeatable workflows.', icon: Microscope },
  { title: 'Privacy Technology', text: 'Tools and architecture that minimize unnecessary data exposure by design.', icon: Globe2 },
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
          {pillars.map(({ title, text, icon: Icon }) => (
            <article className="nx-card" key={title}><div className="nx-icon" aria-hidden="true"><Icon /></div><h2>{title}</h2><p>{text}</p></article>
          ))}
        </div>

        <section className="nx-contact-panel">
          <div><p className="nx-kicker">CONNECT</p><h2>{ar ? 'روابط NEXVARY الرسمية' : 'Official NEXVARY channels'}</h2></div>
          <div className="nx-social-grid">
            {links.map(({ label, href, icon: Icon }) => (
              <a key={label} href={href} rel="noreferrer" target={href.startsWith('mailto:') ? undefined : '_blank'}>
                <span className="inline-flex items-center gap-2">{Icon ? <Icon size={18} aria-hidden="true" /> : <span aria-hidden="true" className="nx-x-icon">X</span>}{label}</span>
                <small>{href.replace('mailto:', '')}</small>
              </a>
            ))}
          </div>
        </section>
      </main>
    </SiteShell>
  );
}
