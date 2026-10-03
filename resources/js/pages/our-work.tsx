import { Head, Link } from '@inertiajs/react';
import { Cloud, ExternalLink, ShieldCheck, Smartphone } from 'lucide-react';
import SiteShell from '../components/site-shell';

const projects = [
  { name: 'FG Link', status: 'Active development', type: 'Android + Cloud', text: 'Local-first connected-device control with authenticated remote relay and a deployable cloud backend.', icon: Smartphone },
  { name: 'NEXVARY Web Platform', status: 'Active development', type: 'Web Platform', text: 'The company platform for product publishing, administration, security controls and deployment operations.', icon: Cloud },
  { name: 'SafeScan', status: 'Controlled release', type: 'Security Tool', text: 'Privacy-oriented file inspection designed around explicit handling and storage boundaries.', icon: ShieldCheck },
  { name: 'Audio Shield', status: 'Development', type: 'Privacy Technology', text: 'Privacy-aware audio protection research and product engineering.', icon: ShieldCheck },
  { name: 'Tower Guard', status: 'Development', type: 'Security Awareness', text: 'Cellular anomaly awareness and validation workflows under controlled development.', icon: ShieldCheck },
];

export default function OurWork() {
 const ar=document.documentElement.lang.startsWith('ar');
 return <SiteShell><Head title={ar?'مشاريع NEXVARY':'NEXVARY Projects'}><meta name="description" content="NEXVARY product portfolio with explicit lifecycle states for active development and controlled releases."/><link rel="canonical" href="https://nexvary.com/our-work"/></Head>
 <main className="nx-section nx-page-body"><div className="nx-section-title"><p>OUR WORK</p><h1>{ar?'ما نبنيه الآن':'What we are building'}</h1><p className="nx-lead">{ar?'نعرض حالة كل مشروع بوضوح، ولا نساوي بين نموذج قيد التطوير ومنتج منشور.':'Project states are explicit: development work is not presented as a generally available product.'}</p></div>
 <div className="nx-grid mt-8">{projects.map(({name,status,type,text,icon:Icon})=><article className="nx-card" key={name}><div className="flex items-start justify-between gap-3"><div className="nx-icon"><Icon size={22}/></div><span className="nx-card-tag">{status}</span></div><h2>{name}</h2><p className="text-cyan-100">{type}</p><p>{text}</p></article>)}</div>
 <div className="nx-actions"><Link className="nx-btn nx-btn-primary" href="/apps">{ar?'الإصدارات المنشورة':'Published releases'} <ExternalLink size={16}/></Link><Link className="nx-btn" href="/contact">{ar?'تواصل معنا':'Contact us'}</Link></div></main></SiteShell>;
}