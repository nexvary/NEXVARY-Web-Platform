import { Head } from '@inertiajs/react';
import SiteShell from '../components/site-shell';

const services = [
  { code: '01', title: 'TSCM & Counter-Surveillance', text: 'Technical inspections for offices, vehicles, rooms, devices, networks and RF environments.' },
  { code: '02', title: 'Cybersecurity', text: 'Assessment, hardening, vulnerability reduction, incident response and security architecture.' },
  { code: '03', title: 'Digital Privacy', text: 'Privacy-first workflows, exposure reduction and defensive technology for sensitive users and teams.' },
  { code: '04', title: 'Digital Forensics', text: 'Structured evidence review, investigation workflows and defensible reporting.' },
  { code: '05', title: 'Physical Security Technology', text: 'CCTV, access control, alarms, secure networking and sensor integration.' },
  { code: '06', title: 'Security Intelligence', text: 'Curated threat, breach and AI-security intelligence surfaces for decision support.' },
];

export default function Services() {
  const ar = document.documentElement.lang.startsWith('ar');

  return (
    <SiteShell>
      <Head title={ar ? 'خدمات NEXVARY' : 'NEXVARY Services'}>
        <meta name="description" content="NEXVARY security services: TSCM, cybersecurity, privacy, digital forensics and physical security technology." />
        <link rel="canonical" href="https://nexvary.com/services" />
      </Head>
      <main className="nx-section nx-page-body">
        <div className="nx-section-title">
          <p>SECURITY SERVICES</p>
          <h1>{ar ? 'حماية تقنية متعددة التخصصات' : 'Multi-disciplinary technical protection'}</h1>
          <p className="nx-lead">{ar ? 'خدمات مصممة لتقليل التعرض الرقمي والمادي، مع فصل واضح بين التوعية، الفحص الفني، والتحقق المهني.' : 'Services designed to reduce digital and physical exposure with a clear separation between awareness, technical inspection and professional validation.'}</p>
        </div>
        <div className="nx-grid">
          {services.map((service) => (
            <article className="nx-card nx-service-card" key={service.code}>
              <div className="nx-icon">{service.code}</div>
              <h2>{service.title}</h2>
              <p>{service.text}</p>
              <span className="nx-card-status">NEXVARY · CONTROLLED DELIVERY</span>
            </article>
          ))}
        </div>
      </main>
    </SiteShell>
  );
}
