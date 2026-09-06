import { Head, useForm } from '@inertiajs/react';
import { Languages as LanguagesIcon } from 'lucide-react';
import { AdminNav } from '../../components/admin/admin-nav';
import { NxCard } from '../../components/ui/nx-card';

type Language = { locale: string; label: string; is_rtl: boolean; is_enabled: boolean; completion_percent: number };

function LanguageRow({ language }: { language: Language }) {
  const form = useForm({ is_enabled: language.is_enabled, completion_percent: language.completion_percent });
  return <div className="grid gap-4 border-b border-white/5 px-5 py-4 md:grid-cols-[1fr_auto_auto] md:items-center"><div><div className="flex items-center gap-3"><strong>{language.label}</strong><span className="rounded-full border border-cyan-300/15 px-2 py-0.5 text-xs text-cyan-300">{language.locale.toUpperCase()}</span>{language.is_rtl&&<span className="text-xs text-amber-300">RTL</span>}</div><p className="mt-1 text-xs text-slate-500">Translation readiness: {form.data.completion_percent}%</p></div><input aria-label={`${language.label} completion`} type="range" min={0} max={100} value={form.data.completion_percent} onChange={e=>form.setData('completion_percent',Number(e.target.value))}/><button type="button" onClick={()=>{form.setData('is_enabled',!form.data.is_enabled); form.transform(data=>({...data,is_enabled:!form.data.is_enabled})).post(`/secure-control/languages/${language.locale}`);}} className={`min-h-11 rounded-xl border px-4 text-sm font-semibold ${form.data.is_enabled?'border-emerald-300/20 bg-emerald-300/10 text-emerald-200':'border-white/10 bg-white/5 text-slate-400'}`}>{form.data.is_enabled?'Enabled':'Disabled'}</button></div>;
}

export default function Languages({ languages }: { languages: Language[] }) {
  return <><Head title="Language Management" /><main className="min-h-screen bg-slate-950 px-4 py-7 text-white sm:px-8"><div className="mx-auto max-w-7xl"><AdminNav active="Languages" /><div className="mb-6 flex items-center gap-3"><LanguagesIcon className="text-cyan-300"/><div><h1 className="text-3xl font-black">Language Management</h1><p className="text-sm text-slate-400">Enable locales, track translation readiness and RTL coverage.</p></div></div><NxCard className="p-0">{languages.map(language=><LanguageRow key={language.locale} language={language}/>)}</NxCard></div></main></>;
}
