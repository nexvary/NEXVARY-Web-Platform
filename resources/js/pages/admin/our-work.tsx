import { Head, router, useForm } from '@inertiajs/react';
import { ExternalLink, PackagePlus, Save, Trash2 } from 'lucide-react';
import { AdminNav } from '../../components/admin/admin-nav';
import { NxCard } from '../../components/ui/nx-card';

type DistributionMode = 'download' | 'showcase' | 'request' | 'internal' | 'coming_soon';

type AppRow = {
  id: number; slug: string; name: string; tagline?: string | null; summary: string; description?: string | null;
  platform: string; category?: string | null; version?: string | null; apk_url?: string | null; apk_size?: string | null;
  sha256?: string | null; icon_url?: string | null; screenshots?: string | null; features?: string | null;
  changelog?: string | null; downloads: number; is_published: boolean; distribution_mode: DistributionMode;
  download_enabled: boolean; request_url?: string | null; availability_note?: string | null;
};

const platforms = ['Android', 'Windows', 'Web', 'Linux', 'Cross-platform'];
const modes: Array<{ value: DistributionMode; label: string; help: string }> = [
  { value: 'download', label: 'Public download', help: 'Public page with a protected NEXVARY download route.' },
  { value: 'showcase', label: 'Showcase only', help: 'Public product page with no download access.' },
  { value: 'request', label: 'Available on request', help: 'Shows a request-access action instead of download.' },
  { value: 'internal', label: 'Private / internal', help: 'Public-facing overview only; no package delivery.' },
  { value: 'coming_soon', label: 'Coming soon', help: 'Announce the project before distribution is enabled.' },
];

function ModeFields({ data, setData }: { data: any; setData: (key: any, value: any) => void }) {
  const selected = modes.find((mode) => mode.value === data.distribution_mode)!;
  return <div className="rounded-2xl border border-cyan-300/10 bg-cyan-300/[.03] p-4">
    <div className="grid gap-3 md:grid-cols-2">
      <label className="space-y-2"><span className="text-xs font-bold uppercase tracking-wider text-slate-400">Distribution</span><select className="min-h-11 w-full rounded-xl border border-white/10 bg-slate-900 px-3" value={data.distribution_mode} onChange={e=>{const value=e.target.value as DistributionMode;setData('distribution_mode',value);if(value!=='download')setData('download_enabled',false);}}>{modes.map(mode=><option key={mode.value} value={mode.value}>{mode.label}</option>)}</select><span className="block text-xs text-slate-500">{selected.help}</span></label>
      <label className="space-y-2"><span className="text-xs font-bold uppercase tracking-wider text-slate-400">Public availability note</span><input className="min-h-11 w-full rounded-xl border border-white/10 bg-slate-900 px-3" value={data.availability_note} onChange={e=>setData('availability_note',e.target.value)} placeholder="e.g. NEXVARY product — not available for public download"/></label>
    </div>
    {data.distribution_mode==='download' && <label className="mt-4 flex items-center gap-2 text-sm text-slate-300"><input type="checkbox" checked={data.download_enabled} onChange={e=>setData('download_enabled',e.target.checked)}/>Enable public download route</label>}
    {data.distribution_mode==='request' && <input className="mt-4 min-h-11 w-full rounded-xl border border-white/10 bg-slate-900 px-3" value={data.request_url} onChange={e=>setData('request_url',e.target.value)} placeholder="Request access URL (https://...)"/>}
  </div>;
}

function Editor({ app }: { app: AppRow }) {
  const form = useForm({
    name: app.name, slug: app.slug, tagline: app.tagline || '', summary: app.summary, description: app.description || '',
    platform: app.platform, category: app.category || '', version: app.version || '', apk_url: app.apk_url || '', apk_size: app.apk_size || '', sha256: app.sha256 || '', icon_url: app.icon_url || '', screenshots: app.screenshots || '', features: app.features || '', changelog: app.changelog || '',
    distribution_mode: app.distribution_mode || 'download' as DistributionMode, download_enabled: !!app.download_enabled, request_url: app.request_url || '', availability_note: app.availability_note || '', is_published: !!app.is_published,
  });
  return <NxCard className="p-5"><form onSubmit={(e)=>{e.preventDefault();form.post(`/secure-control/our-work/${app.id}`,{preserveScroll:true});}} className="space-y-4">
    <div className="flex flex-wrap items-center justify-between gap-3"><div><h3 className="text-xl font-black">{app.name}</h3><p className="text-xs text-slate-500">/{app.slug} · {app.downloads} downloads · {app.distribution_mode}</p></div><a href={`/our-work/${app.slug}`} target="_blank" rel="noreferrer" className="inline-flex min-h-11 items-center gap-2 rounded-xl border border-cyan-300/15 px-3 text-sm text-cyan-200"><ExternalLink size={15}/>Preview</a></div>
    <div className="grid gap-3 md:grid-cols-2"><input className="min-h-11 rounded-xl border border-white/10 bg-slate-900 px-3" value={form.data.name} onChange={e=>form.setData('name',e.target.value)} placeholder="Name"/><input className="min-h-11 rounded-xl border border-white/10 bg-slate-900 px-3" value={form.data.slug} onChange={e=>form.setData('slug',e.target.value)} placeholder="slug"/></div>
    <input className="min-h-11 w-full rounded-xl border border-white/10 bg-slate-900 px-3" value={form.data.tagline} onChange={e=>form.setData('tagline',e.target.value)} placeholder="Tagline"/>
    <textarea className="min-h-24 w-full rounded-xl border border-white/10 bg-slate-900 p-3" value={form.data.summary} onChange={e=>form.setData('summary',e.target.value)} placeholder="Short summary"/>
    <textarea className="min-h-32 w-full rounded-xl border border-white/10 bg-slate-900 p-3" value={form.data.description} onChange={e=>form.setData('description',e.target.value)} placeholder="Full description"/>
    <div className="grid gap-3 md:grid-cols-3"><select className="min-h-11 rounded-xl border border-white/10 bg-slate-900 px-3" value={form.data.platform} onChange={e=>form.setData('platform',e.target.value)}>{platforms.map(x=><option key={x}>{x}</option>)}</select><input className="min-h-11 rounded-xl border border-white/10 bg-slate-900 px-3" value={form.data.category} onChange={e=>form.setData('category',e.target.value)} placeholder="Category"/><input className="min-h-11 rounded-xl border border-white/10 bg-slate-900 px-3" value={form.data.version} onChange={e=>form.setData('version',e.target.value)} placeholder="Version"/></div>
    <ModeFields data={form.data} setData={form.setData as any}/>
    <input className="min-h-11 w-full rounded-xl border border-white/10 bg-slate-900 px-3" value={form.data.apk_url} onChange={e=>form.setData('apk_url',e.target.value)} placeholder="APK/EXE direct URL — stored for download mode only"/>
    <div className="grid gap-3 md:grid-cols-2"><input className="min-h-11 rounded-xl border border-white/10 bg-slate-900 px-3" value={form.data.apk_size} onChange={e=>form.setData('apk_size',e.target.value)} placeholder="Package size"/><input className="min-h-11 rounded-xl border border-white/10 bg-slate-900 px-3" value={form.data.sha256} onChange={e=>form.setData('sha256',e.target.value)} placeholder="SHA-256 (64 hex chars)"/></div>
    <input className="min-h-11 w-full rounded-xl border border-white/10 bg-slate-900 px-3" value={form.data.icon_url} onChange={e=>form.setData('icon_url',e.target.value)} placeholder="Icon URL"/>
    <textarea className="min-h-24 w-full rounded-xl border border-white/10 bg-slate-900 p-3" value={form.data.screenshots} onChange={e=>form.setData('screenshots',e.target.value)} placeholder="Screenshot URLs — one per line"/>
    <textarea className="min-h-24 w-full rounded-xl border border-white/10 bg-slate-900 p-3" value={form.data.features} onChange={e=>form.setData('features',e.target.value)} placeholder="Features — one per line"/>
    <textarea className="min-h-24 w-full rounded-xl border border-white/10 bg-slate-900 p-3" value={form.data.changelog} onChange={e=>form.setData('changelog',e.target.value)} placeholder="Changelog"/>
    <label className="flex items-center gap-2 text-sm text-slate-300"><input type="checkbox" checked={form.data.is_published} onChange={e=>form.setData('is_published',e.target.checked)}/>Published</label>
    {Object.keys(form.errors).length>0 && <div className="rounded-xl border border-red-400/20 bg-red-400/5 p-3 text-sm text-red-200">{Object.values(form.errors).join(' · ')}</div>}
    <div className="flex flex-wrap gap-3"><button disabled={form.processing} className="inline-flex min-h-11 items-center gap-2 rounded-xl bg-cyan-300 px-4 font-black text-slate-950"><Save size={16}/>Save</button><button type="button" onClick={()=>{if(confirm('Delete this project page?')) router.delete(`/secure-control/our-work/${app.id}`,{preserveScroll:true});}} className="inline-flex min-h-11 items-center gap-2 rounded-xl border border-red-400/20 px-4 text-red-300"><Trash2 size={16}/>Delete</button></div>
  </form></NxCard>;
}

export default function OurWorkAdmin({ apps }: { apps: AppRow[] }) {
  const create = useForm({ name:'',slug:'',tagline:'',summary:'',description:'',platform:'Android',category:'',version:'',apk_url:'',apk_size:'',sha256:'',icon_url:'',screenshots:'',features:'',changelog:'',distribution_mode:'showcase' as DistributionMode,download_enabled:false,request_url:'',availability_note:'',is_published:false });
  return <><Head title="Our Work Publishing"/><main className="min-h-screen bg-slate-950 px-4 py-7 text-white sm:px-8"><div className="mx-auto max-w-7xl"><AdminNav active="Our Work"/>
    <div className="mb-6 flex items-center gap-3"><PackagePlus className="text-cyan-300"/><div><h1 className="text-3xl font-black">Our Work Publishing Center</h1><p className="text-sm text-slate-400">Publish projects independently from distribution. A public page never implies that its package is downloadable.</p></div></div>
    <NxCard className="mb-7 p-5"><form onSubmit={(e)=>{e.preventDefault();create.post('/secure-control/our-work',{preserveScroll:true,onSuccess:()=>create.reset()});}} className="space-y-4"><h2 className="text-xl font-black">Create application page</h2><div className="grid gap-3 md:grid-cols-2"><input required className="min-h-11 rounded-xl border border-white/10 bg-slate-900 px-3" placeholder="Application name" value={create.data.name} onChange={e=>create.setData('name',e.target.value)}/><input required className="min-h-11 rounded-xl border border-white/10 bg-slate-900 px-3" placeholder="slug e.g. audio-shield" value={create.data.slug} onChange={e=>create.setData('slug',e.target.value)}/></div><input className="min-h-11 w-full rounded-xl border border-white/10 bg-slate-900 px-3" placeholder="Tagline" value={create.data.tagline} onChange={e=>create.setData('tagline',e.target.value)}/><textarea required className="min-h-24 w-full rounded-xl border border-white/10 bg-slate-900 p-3" placeholder="Short summary" value={create.data.summary} onChange={e=>create.setData('summary',e.target.value)}/><div className="grid gap-3 md:grid-cols-3"><select className="min-h-11 rounded-xl border border-white/10 bg-slate-900 px-3" value={create.data.platform} onChange={e=>create.setData('platform',e.target.value)}>{platforms.map(x=><option key={x}>{x}</option>)}</select><input className="min-h-11 rounded-xl border border-white/10 bg-slate-900 px-3" placeholder="Category" value={create.data.category} onChange={e=>create.setData('category',e.target.value)}/><input className="min-h-11 rounded-xl border border-white/10 bg-slate-900 px-3" placeholder="Version" value={create.data.version} onChange={e=>create.setData('version',e.target.value)}/></div><ModeFields data={create.data} setData={create.setData as any}/><input className="min-h-11 w-full rounded-xl border border-white/10 bg-slate-900 px-3" placeholder="Package direct URL (optional)" value={create.data.apk_url} onChange={e=>create.setData('apk_url',e.target.value)}/><label className="flex items-center gap-2 text-sm text-slate-300"><input type="checkbox" checked={create.data.is_published} onChange={e=>create.setData('is_published',e.target.checked)}/>Publish immediately</label>{Object.keys(create.errors).length>0&&<div className="rounded-xl border border-red-400/20 bg-red-400/5 p-3 text-sm text-red-200">{Object.values(create.errors).join(' · ')}</div>}<button disabled={create.processing} className="inline-flex min-h-11 items-center gap-2 rounded-xl bg-cyan-300 px-4 font-black text-slate-950"><PackagePlus size={16}/>Create page</button></form></NxCard>
    <div className="space-y-6">{apps.map(app=><Editor key={app.id} app={app}/>)}</div>
  </div></main></>;
}
