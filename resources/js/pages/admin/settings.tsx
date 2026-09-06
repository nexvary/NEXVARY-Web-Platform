import { Head, useForm } from '@inertiajs/react';
import { SearchCheck, Settings as SettingsIcon } from 'lucide-react';
import { AdminNav } from '../../components/admin/admin-nav';
import { NxCard } from '../../components/ui/nx-card';

type Settings = Record<string, string>;

export default function Settings({ settings }: { settings: Settings }) {
  const form = useForm({
    website_url: settings['site.url'] ?? 'https://nexvary.com/',
    contact_email: settings['contact.email'] ?? 'info@nexvary.com',
    facebook: settings['social.facebook'] ?? '',
    youtube: settings['social.youtube'] ?? '',
    x: settings['social.x'] ?? '',
    seo_title: settings['seo.default_title'] ?? 'NEXVARY',
    seo_description: settings['seo.default_description'] ?? '',
    index_public_pages: (settings['seo.index_public_pages'] ?? '1') === '1',
  });
  const submit = () => form.post('/secure-control/settings', { preserveScroll: true });
  return <><Head title="Site Settings & SEO" /><main className="min-h-screen bg-slate-950 px-4 py-7 text-white sm:px-8"><div className="mx-auto max-w-6xl"><AdminNav active="Settings" /><div className="mb-6 flex items-center gap-3"><SettingsIcon className="text-cyan-300"/><div><h1 className="text-3xl font-black">Settings & SEO</h1><p className="text-sm text-slate-400">Site identity, official channels and search visibility defaults.</p></div></div><div className="grid gap-5 lg:grid-cols-2"><NxCard><h2 className="text-lg font-black">Identity & channels</h2>{[['Website URL','website_url','url'],['Contact email','contact_email','email'],['Facebook','facebook','url'],['YouTube','youtube','url'],['X','x','url']].map(([label,key,type])=><label key={key} className="mt-4 block text-sm"><span className="text-slate-300">{label}</span><input type={type} value={String(form.data[key as keyof typeof form.data])} onChange={(e)=>form.setData(key as keyof typeof form.data,e.target.value as never)} className="mt-2 min-h-11 w-full rounded-xl border border-white/10 bg-slate-900 px-3"/></label>)}</NxCard><NxCard><div className="flex items-center gap-2 text-cyan-300"><SearchCheck size={19}/><h2 className="text-lg font-black text-white">SEO defaults</h2></div><label className="mt-4 block text-sm"><span>Default title</span><input value={form.data.seo_title} onChange={(e)=>form.setData('seo_title',e.target.value)} className="mt-2 min-h-11 w-full rounded-xl border border-white/10 bg-slate-900 px-3"/></label><label className="mt-4 block text-sm"><span>Default description</span><textarea value={form.data.seo_description} onChange={(e)=>form.setData('seo_description',e.target.value)} className="mt-2 min-h-28 w-full rounded-xl border border-white/10 bg-slate-900 p-3"/></label><label className="mt-4 flex min-h-11 items-center justify-between gap-4"><span>Index public pages</span><input type="checkbox" checked={form.data.index_public_pages} onChange={(e)=>form.setData('index_public_pages',e.target.checked)}/></label><p className="mt-3 text-xs leading-6 text-slate-500">Private administration remains noindex/no-store regardless of this public setting.</p></NxCard></div><button type="button" onClick={submit} disabled={form.processing} className="mt-5 min-h-11 rounded-xl border border-cyan-300/20 bg-cyan-300/10 px-5 font-semibold text-cyan-100 disabled:opacity-50">Save settings</button></div></main></>;
}
