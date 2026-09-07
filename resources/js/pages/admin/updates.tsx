import { Head, router } from '@inertiajs/react';
import { CheckCircle2, DownloadCloud, RefreshCw, ShieldCheck, TriangleAlert } from 'lucide-react';
import { useState } from 'react';
import { AdminNav } from '../../components/admin/admin-nav';
import { NxCard } from '../../components/ui/nx-card';

type Status = {
  current_version: string;
  channel: string;
  configured: boolean;
  manifest_url: string | null;
};

type Checked = {
  current_version: string;
  available_version: string;
  update_available: boolean;
  notes: string | null;
  published_at: string | null;
  download_url: string;
  sha256: string;
} | null;

type Result = { installed_version: string; backup_path: string } | null;

type Props = { status: Status; checked: Checked; result: Result; error: string | null };

export default function Updates({ status, checked, result, error }: Props) {
  const [checking, setChecking] = useState(false);
  const [installing, setInstalling] = useState(false);

  const checkNow = () => {
    setChecking(true);
    router.post('/secure-control/updates/check', {}, { preserveScroll: true, onFinish: () => setChecking(false) });
  };

  const install = () => {
    if (!checked?.update_available) return;
    if (!window.confirm(`Install NEXVARY ${checked.available_version}? The site will briefly enter maintenance mode.`)) return;
    setInstalling(true);
    router.post('/secure-control/updates/install', {
      version: checked.available_version,
      download_url: checked.download_url,
      sha256: checked.sha256,
    }, { preserveScroll: true, onFinish: () => setInstalling(false) });
  };

  return <><Head title="Update Center"/><main className="min-h-screen bg-slate-950 px-4 py-7 text-white sm:px-8"><div className="mx-auto max-w-6xl"><AdminNav active="Updates"/>
    <div className="mb-6 flex items-center gap-3"><DownloadCloud className="text-cyan-300"/><div><h1 className="text-3xl font-black">NEXVARY Update Center</h1><p className="text-sm text-slate-400">Receive, verify and install trusted theme/platform releases from inside the control panel.</p></div></div>
    <div className="grid gap-5 lg:grid-cols-3">
      <NxCard><div className="flex items-center gap-2 text-cyan-300"><ShieldCheck size={19}/><strong>Installed version</strong></div><div className="mt-3 text-2xl font-black">{status.current_version}</div><p className="mt-2 text-sm text-slate-400">Channel: {status.channel}</p></NxCard>
      <NxCard><div className="flex items-center gap-2 text-cyan-300"><RefreshCw size={19}/><strong>Update source</strong></div><p className="mt-3 text-sm text-slate-300">{status.configured ? 'Configured' : 'Not configured yet'}</p><p className="mt-2 break-all text-xs text-slate-500">{status.manifest_url ?? 'Set NEXVARY_UPDATE_MANIFEST_URL in production.'}</p></NxCard>
      <NxCard><div className="flex items-center gap-2 text-cyan-300"><CheckCircle2 size={19}/><strong>Integrity policy</strong></div><p className="mt-3 text-sm leading-6 text-slate-400">HTTPS allow-list, SHA-256 verification, release marker validation, maintenance mode, database migration and rollback backup.</p></NxCard>
    </div>

    <NxCard className="mt-5">
      <div className="flex flex-wrap items-center justify-between gap-4"><div><h2 className="text-xl font-black">Updates</h2><p className="mt-1 text-sm text-slate-400">The server re-checks update metadata before installation. Client-submitted metadata is never trusted by itself.</p></div><button type="button" onClick={checkNow} disabled={!status.configured || checking || installing} className="min-h-11 rounded-xl border border-cyan-300/20 bg-cyan-300/10 px-4 font-semibold text-cyan-100 disabled:opacity-40">{checking ? 'Checking…' : 'Check for updates'}</button></div>
      {error && <div className="mt-5 flex items-start gap-3 rounded-xl border border-rose-300/20 bg-rose-300/10 p-4 text-sm text-rose-100"><TriangleAlert size={18} className="mt-0.5 shrink-0"/><span>{error}</span></div>}
      {result && <div className="mt-5 rounded-xl border border-emerald-300/20 bg-emerald-300/10 p-4 text-sm text-emerald-100">Installed {result.installed_version} successfully. A pre-update backup was created.</div>}
      {checked && <div className="mt-5 rounded-2xl border border-white/10 bg-black/20 p-5"><div className="flex flex-wrap items-center justify-between gap-4"><div><div className="text-sm text-slate-400">Available version</div><div className="mt-1 text-2xl font-black">{checked.available_version}</div>{checked.published_at && <div className="mt-1 text-xs text-slate-500">Published: {checked.published_at}</div>}</div><span className={`rounded-full px-3 py-1 text-xs font-bold ${checked.update_available ? 'bg-emerald-400/10 text-emerald-300' : 'bg-slate-400/10 text-slate-300'}`}>{checked.update_available ? 'UPDATE AVAILABLE' : 'UP TO DATE'}</span></div>{checked.notes && <p className="mt-4 whitespace-pre-wrap text-sm leading-6 text-slate-300">{checked.notes}</p>}{checked.update_available && <button type="button" onClick={install} disabled={installing} className="mt-5 min-h-11 rounded-xl border border-emerald-300/20 bg-emerald-300/10 px-4 font-bold text-emerald-100 disabled:opacity-40">{installing ? 'Installing…' : `Install ${checked.available_version}`}</button>}</div>}
    </NxCard>
  </div></main></>;
}
