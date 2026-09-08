import { Head, router, useForm } from '@inertiajs/react';
import { CheckCircle2, Clock3, PackagePlus, RefreshCw, ShieldCheck, UploadCloud } from 'lucide-react';

type AppRow = {
  id: number;
  slug: string;
  name: string;
  platform: string;
  version?: string | null;
  review_status: 'draft' | 'pending' | 'approved';
  is_published: boolean;
  updated_at: string;
};

type ReleaseRow = {
  id: number;
  portfolio_app_id: number;
  app_slug: string;
  app_name: string;
  version: string;
  channel: string;
  status: 'draft' | 'pending' | 'published' | 'superseded';
  file_size?: string | null;
  published_at?: string | null;
};

type AuditRow = { id: number; action: string; app_slug?: string | null; created_at: string };

type Props = { apps: AppRow[]; releases: ReleaseRow[]; audit: AuditRow[]; canApprove: boolean; role: string };

export default function AppCenterDashboard({ apps, releases, audit, canApprove, role }: Props) {
  const form = useForm({
    slug: '', name: '', tagline: '', summary: '', description: '', platform: 'Android', category: '', version: '',
    apk_url: '', apk_size: '', sha256: '', icon_url: '', distribution_mode: 'download', request_url: '', availability_note: '',
  });

  const releaseForm = useForm({
    portfolio_app_id: '', version: '', channel: 'stable', download_url: '', sha256: '', file_size: '', changelog: '', is_mandatory: false,
  });

  const submit = (e: React.FormEvent) => {
    e.preventDefault();
    form.post('/app-center/apps', { preserveScroll: true, onSuccess: () => form.reset() });
  };

  const submitRelease = (e: React.FormEvent) => {
    e.preventDefault();
    releaseForm.post('/app-center/releases', { preserveScroll: true, onSuccess: () => releaseForm.reset() });
  };

  return (
    <div className="min-h-screen bg-slate-950 text-slate-100">
      <Head title="NEXVARY App Center" />
      <header className="border-b border-cyan-300/10 bg-slate-950/95 px-5 py-5">
        <div className="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-4">
          <div><p className="text-xs font-bold tracking-[.28em] text-cyan-300">NEXVARY APP CENTER</p><h1 className="mt-1 text-2xl font-black">Publishing & Update Command Center</h1></div>
          <div className="rounded-xl border border-white/10 bg-white/5 px-4 py-2 text-sm"><ShieldCheck className="mr-2 inline" size={17}/>{role.toUpperCase()}</div>
        </div>
      </header>

      <main className="mx-auto grid max-w-7xl gap-6 p-5 lg:grid-cols-[1.05fr_.95fr]">
        <section className="space-y-6">
          <div className="rounded-3xl border border-white/10 bg-white/[.03] p-5">
            <div className="mb-5 flex items-center gap-3"><PackagePlus className="text-cyan-300"/><div><h2 className="font-black">New application</h2><p className="text-sm text-slate-400">Assistants prepare drafts. Admin/Owner approves public publishing.</p></div></div>
            <form onSubmit={submit} className="grid gap-4 md:grid-cols-2">
              <input className="rounded-xl border border-white/10 bg-slate-900 p-3" placeholder="App name" value={form.data.name} onChange={e=>form.setData('name',e.target.value)} required/>
              <input className="rounded-xl border border-white/10 bg-slate-900 p-3" placeholder="slug-example" value={form.data.slug} onChange={e=>form.setData('slug',e.target.value)} required/>
              <select className="rounded-xl border border-white/10 bg-slate-900 p-3" value={form.data.platform} onChange={e=>form.setData('platform',e.target.value)}><option>Android</option><option>Windows</option><option>Linux</option><option>Web</option><option>Cross-platform</option></select>
              <input className="rounded-xl border border-white/10 bg-slate-900 p-3" placeholder="Initial version (optional)" value={form.data.version} onChange={e=>form.setData('version',e.target.value)}/>
              <input className="rounded-xl border border-white/10 bg-slate-900 p-3 md:col-span-2" placeholder="Short tagline" value={form.data.tagline} onChange={e=>form.setData('tagline',e.target.value)}/>
              <textarea className="min-h-28 rounded-xl border border-white/10 bg-slate-900 p-3 md:col-span-2" placeholder="Summary" value={form.data.summary} onChange={e=>form.setData('summary',e.target.value)} required/>
              <input className="rounded-xl border border-white/10 bg-slate-900 p-3 md:col-span-2" placeholder="Initial download URL (optional)" value={form.data.apk_url} onChange={e=>form.setData('apk_url',e.target.value)}/>
              <input className="rounded-xl border border-white/10 bg-slate-900 p-3" placeholder="Initial SHA-256 (optional)" value={form.data.sha256} onChange={e=>form.setData('sha256',e.target.value)}/>
              <select className="rounded-xl border border-white/10 bg-slate-900 p-3" value={form.data.distribution_mode} onChange={e=>form.setData('distribution_mode',e.target.value)}><option value="download">Direct download</option><option value="request">Request access</option><option value="private">Private</option></select>
              <button disabled={form.processing} className="md:col-span-2 inline-flex min-h-12 items-center justify-center gap-2 rounded-xl border border-cyan-300/30 bg-cyan-300/10 font-bold text-cyan-100"><UploadCloud size={18}/>Save app draft</button>
            </form>
          </div>

          <div className="rounded-3xl border border-white/10 bg-white/[.03] p-5">
            <div className="mb-5 flex items-center gap-3"><RefreshCw className="text-emerald-300"/><div><h2 className="font-black">Publish application update</h2><p className="text-sm text-slate-400">Create a signed release record. The public update manifest changes only after Admin/Owner approval.</p></div></div>
            <form onSubmit={submitRelease} className="grid gap-4 md:grid-cols-2">
              <select className="rounded-xl border border-white/10 bg-slate-900 p-3" value={releaseForm.data.portfolio_app_id} onChange={e=>releaseForm.setData('portfolio_app_id',e.target.value)} required>
                <option value="">Select application</option>
                {apps.map(app=><option key={app.id} value={app.id}>{app.name} · {app.platform}</option>)}
              </select>
              <input className="rounded-xl border border-white/10 bg-slate-900 p-3" placeholder="Version e.g. 1.4.0" value={releaseForm.data.version} onChange={e=>releaseForm.setData('version',e.target.value)} required/>
              <select className="rounded-xl border border-white/10 bg-slate-900 p-3" value={releaseForm.data.channel} onChange={e=>releaseForm.setData('channel',e.target.value)}><option value="stable">Stable</option><option value="beta">Beta</option><option value="internal">Internal</option></select>
              <input className="rounded-xl border border-white/10 bg-slate-900 p-3" placeholder="File size e.g. 42 MB" value={releaseForm.data.file_size} onChange={e=>releaseForm.setData('file_size',e.target.value)}/>
              <input className="rounded-xl border border-white/10 bg-slate-900 p-3 md:col-span-2" placeholder="HTTPS download URL" value={releaseForm.data.download_url} onChange={e=>releaseForm.setData('download_url',e.target.value)} required/>
              <input className="rounded-xl border border-white/10 bg-slate-900 p-3 md:col-span-2" placeholder="SHA-256" value={releaseForm.data.sha256} onChange={e=>releaseForm.setData('sha256',e.target.value)} required/>
              <textarea className="min-h-28 rounded-xl border border-white/10 bg-slate-900 p-3 md:col-span-2" placeholder="What's new / changelog" value={releaseForm.data.changelog} onChange={e=>releaseForm.setData('changelog',e.target.value)}/>
              <label className="md:col-span-2 flex items-center gap-3 text-sm text-slate-300"><input type="checkbox" checked={releaseForm.data.is_mandatory} onChange={e=>releaseForm.setData('is_mandatory',e.target.checked)}/>Mark as mandatory update</label>
              <button disabled={releaseForm.processing} className="md:col-span-2 inline-flex min-h-12 items-center justify-center gap-2 rounded-xl border border-emerald-300/30 bg-emerald-300/10 font-bold text-emerald-100"><RefreshCw size={18}/>Save update draft</button>
            </form>
          </div>
        </section>

        <section className="space-y-6">
          <div className="rounded-3xl border border-white/10 bg-white/[.03] p-5">
            <h2 className="mb-4 font-black">Application queue</h2>
            <div className="space-y-3">{apps.map(app => <article key={app.id} className="rounded-2xl border border-white/10 bg-slate-900/70 p-4">
              <div className="flex flex-wrap items-start justify-between gap-3"><div><h3 className="font-bold">{app.name}</h3><p className="text-xs text-slate-400">{app.platform} · {app.version || 'no version'} · {app.slug}</p></div><span className="rounded-full border border-white/10 px-3 py-1 text-xs">{app.review_status}</span></div>
              <div className="mt-4 flex flex-wrap gap-2">
                {app.review_status === 'draft' && <button onClick={()=>router.post(`/app-center/apps/${app.id}/submit`)} className="rounded-lg border border-amber-300/20 px-3 py-2 text-xs text-amber-200"><Clock3 className="mr-1 inline" size={14}/>Submit for approval</button>}
                {canApprove && app.review_status === 'pending' && <button onClick={()=>router.post(`/app-center/apps/${app.id}/approve`)} className="rounded-lg border border-emerald-300/20 px-3 py-2 text-xs text-emerald-200"><CheckCircle2 className="mr-1 inline" size={14}/>Approve & publish</button>}
                {canApprove && app.is_published && <button onClick={()=>router.post(`/app-center/apps/${app.id}/unpublish`)} className="rounded-lg border border-rose-300/20 px-3 py-2 text-xs text-rose-200">Unpublish</button>}
              </div>
            </article>)}</div>
          </div>

          <div className="rounded-3xl border border-white/10 bg-white/[.03] p-5">
            <h2 className="mb-4 font-black">Update release queue</h2>
            <div className="space-y-3">{releases.map(release => <article key={release.id} className="rounded-2xl border border-white/10 bg-slate-900/70 p-4">
              <div className="flex flex-wrap items-start justify-between gap-3"><div><h3 className="font-bold">{release.app_name} · v{release.version}</h3><p className="text-xs text-slate-400">{release.channel} · {release.file_size || 'size not set'} · /apps/{release.app_slug}/update.json</p></div><span className="rounded-full border border-white/10 px-3 py-1 text-xs">{release.status}</span></div>
              <div className="mt-4 flex flex-wrap gap-2">
                {release.status === 'draft' && <button onClick={()=>router.post(`/app-center/releases/${release.id}/submit`)} className="rounded-lg border border-amber-300/20 px-3 py-2 text-xs text-amber-200"><Clock3 className="mr-1 inline" size={14}/>Submit update</button>}
                {canApprove && release.status === 'pending' && <button onClick={()=>router.post(`/app-center/releases/${release.id}/approve`)} className="rounded-lg border border-emerald-300/20 px-3 py-2 text-xs text-emerald-200"><CheckCircle2 className="mr-1 inline" size={14}/>Publish update</button>}
                {release.status === 'published' && <span className="rounded-lg border border-cyan-300/20 px-3 py-2 text-xs text-cyan-200">Live manifest</span>}
              </div>
            </article>)}</div>
          </div>

          <div className="rounded-3xl border border-white/10 bg-white/[.03] p-5"><h2 className="mb-4 font-black">Audit log</h2><div className="space-y-2 text-sm text-slate-400">{audit.map(row=><div key={row.id} className="flex justify-between gap-4 border-b border-white/5 pb-2"><span>{row.action} {row.app_slug ? `· ${row.app_slug}` : ''}</span><time>{row.created_at}</time></div>)}</div></div>
        </section>
      </main>
    </div>
  );
}
