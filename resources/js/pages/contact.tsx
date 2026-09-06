import { Head } from '@inertiajs/react';
import SiteShell from '../components/site-shell';
const contacts=[['Website','https://nexvary.com/'],['Facebook','https://www.facebook.com/share/14p9krEn5ij/'],['Email','mailto:info@nexvary.com'],['YouTube','https://www.youtube.com/@NexvaryInc'],['X','https://x.com/Nexvary']];
export default function Contact(){return <SiteShell><Head title="Contact NEXVARY"/><main className="nx-section nx-stack"><p className="nx-kicker">CONTACT</p><h1>Connect with NEXVARY.</h1><div className="nx-card-grid">{contacts.map(([name,href])=><a className="nx-card" key={name} href={href} target={href.startsWith('http')?'_blank':undefined} rel="noreferrer"><h2>{name}</h2><p>{href.replace('mailto:','')}</p></a>)}</div></main></SiteShell>}
