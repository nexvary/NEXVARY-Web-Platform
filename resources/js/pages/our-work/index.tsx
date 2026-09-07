import { Head, Link } from '@inertiajs/react';
import { ArrowUpRight, Download, LockKeyhole, MessageCircleMore, ShieldCheck } from 'lucide-react';
import SiteShell from '../../components/site-shell';

type DistributionMode = 'download' | 'showcase' | 'request' | 'internal' | 'coming_soon';
type AppCard = {
  slug: string; name: string; tagline?: string | null; summary: string; platform: string; category?: string | null;
  version?: string | null; icon_url?: string | null; downloads: number; published_at?: string | null;
  distribution_mode: DistributionMode; download_enabled: boolean; availability_note?: string | null;
};

export default function OurWorkIndex({ apps }: { apps: AppCard[] }) {
  const ar = document.documentElement.lang.startsWith('ar');
  const labels: Record<DistributionMode, string> = {
    download: ar ? 'متاح للتنزيل' : 'Download available',
    showcase: ar ? 'عرض فقط' : 'Showcase only',
    request: ar ? 'متاح عند الطلب' : 'Available on request',
    internal: ar ? 'خاص / داخلي' : 'Private / internal',
    coming_soon: ar ? 'قريبًا' : 'Coming soon',
  };

  return <SiteShell><Head title={ar ? 'أعمالنا' : 'Our Work'} /><main className="mx-auto w-full max-w-7xl px-4 py-10 text-white sm:px-8">
    <section className="mb-10 rounded-3xl border border-cyan-300/15 bg-slate-950/70 p-6 shadow-2xl backdrop-blur sm:p-10"><div className="mb-4 inline-flex items-center gap-2 rounded-full border border-cyan-300/20 bg-cyan-300/10 px-4 py-2 text-sm font-bold text-cyan-200"><ShieldCheck size={17}/> NEXVARY PRODUCT LAB</div><h1 className="text-4xl font-black sm:text-6xl">{ar ? 'أعمالنا' : 'Our Work'}</h1><p className="mt-4 max-w-3xl text-base leading-8 text-slate-300 sm:text-lg">{ar ? 'مشروعات ومنتجات NEXVARY. بعض المنتجات متاحة للتنزيل، بينما يُعرض بعضها للتعريف فقط أو يكون متاحًا عند الطلب.' : 'NEXVARY projects and products. Some are available for download, while others are showcase-only, private, coming soon, or available on request.'}</p></section>
    {apps.length===0 ? <div className="rounded-3xl border border-white/10 bg-slate-950/60 p-10 text-center text-slate-400">{ar ? 'سيتم نشر أعمالنا هنا قريبًا.' : 'Our published work will appear here soon.'}</div> : <section className="grid gap-6 md:grid-cols-2 xl:grid-cols-3">{apps.map(app=><article key={app.slug} className="group rounded-3xl border border-white/10 bg-slate-950/70 p-6 transition hover:-translate-y-1 hover:border-cyan-300/30"><div className="mb-5 flex items-center gap-4">{app.icon_url?<img src={app.icon_url} alt="" className="h-16 w-16 rounded-2xl border border-cyan-300/15 object-cover"/>:<div className="grid h-16 w-16 place-items-center rounded-2xl border border-cyan-300/20 bg-cyan-300/10 text-2xl font-black text-cyan-200">N</div>}<div><h2 className="text-2xl font-black">{app.name}</h2><p className="text-sm text-cyan-300">{app.tagline||app.category||app.platform}</p></div></div><p className="min-h-24 leading-7 text-slate-300">{app.summary}</p><div className="mt-5 flex flex-wrap gap-2 text-xs text-slate-400"><span className="rounded-full border border-white/10 px-3 py-1">{app.platform}</span>{app.version&&<span className="rounded-full border border-white/10 px-3 py-1">v{app.version}</span>}<span className="inline-flex items-center gap-1 rounded-full border border-white/10 px-3 py-1">{app.distribution_mode==='download'?<Download size={13}/>:app.distribution_mode==='request'?<MessageCircleMore size={13}/>:<LockKeyhole size={13}/>} {labels[app.distribution_mode]}</span></div><Link href={`/our-work/${app.slug}`} className="mt-6 inline-flex min-h-11 items-center gap-2 rounded-xl border border-cyan-300/20 bg-cyan-300/10 px-4 py-2 font-bold text-cyan-100 hover:bg-cyan-300/15">{ar ? 'عرض المشروع' : 'View project'}<ArrowUpRight size={16}/></Link></article>)}</section>}
  </main></SiteShell>;
}
