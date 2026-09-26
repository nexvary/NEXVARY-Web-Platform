import { Head, router } from '@inertiajs/react';
import { Activity, CheckCircle2, Database, FileCheck2, Gauge, HardDrive, RefreshCw, ServerCog, ShieldCheck, Sparkles, TriangleAlert } from 'lucide-react';
import { useState } from 'react';
import { AdminNav } from '../../components/admin/admin-nav';
import { NxCard } from '../../components/ui/nx-card';

type Check = { ok: boolean; label: string };
type Status = {
  healthy: boolean;
  checks: {
    database: Check;
    cache: Check;
    storage: Check;
    build: Check;
  };
  php_version: string;
  laravel_version: string;
  environment: string;
  maintenance_mode: boolean;
  checked_at: string;
};

type ActionResult = { message: string; completed_at: string } | null;
type Integrity = {
  healthy: boolean;
  files: Record<string, { ok: boolean; size: number | null }>;
  checked_at: string;
} | null;

type Props = {
  status: Status;
  lastAction: ActionResult;
  integrity: Integrity;
  error: string | null;
};

const actionButton = 'min-h-11 rounded-xl border border-cyan-300/20 bg-cyan-300/10 px-4 py-2 text-sm font-bold text-cyan-100 transition hover:bg-cyan-300/15 disabled:cursor-not-allowed disabled:opacity-40';

export default function Maintenance({ status, lastAction, integrity, error }: Props) {
  const [busy, setBusy] = useState<string | null>(null);

  const run = (action: 'clear' | 'optimize' | 'check') => {
    const labels = {
      clear: 'Clear application caches now?',
      optimize: 'Rebuild safe optimization caches now?',
      check: 'Run the system and file integrity check now?',
    };
    if (!window.confirm(labels[action])) return;
    setBusy(action);
    router.post(`/secure-control/maintenance/${action}`, {}, {
      preserveScroll: true,
      onFinish: () => setBusy(null),
    });
  };

  const healthItems = [
    { key: 'database', icon: Database, check: status.checks.database },
    { key: 'cache', icon: Activity, check: status.checks.cache },
    { key: 'storage', icon: HardDrive, check: status.checks.storage },
    { key: 'build', icon: FileCheck2, check: status.checks.build },
  ];

  return <>
    <Head title="Maintenance & Updates"/>
    <main className="min-h-screen bg-slate-950 px-4 py-7 text-white sm:px-8">
      <div className="mx-auto max-w-6xl">
        <AdminNav active="Maintenance"/>

        <div className="mb-6 flex flex-wrap items-start justify-between gap-4">
          <div className="flex items-start gap-3">
            <ServerCog className="mt-1 text-cyan-300"/>
            <div>
              <h1 className="text-3xl font-black">Maintenance & Updates</h1>
              <p className="mt-1 max-w-3xl text-sm leading-6 text-slate-400">Run the routine cPanel maintenance tasks from the control panel. No Terminal or shell command is required.</p>
            </div>
          </div>
          <div className={`rounded-full border px-3 py-1 text-xs font-black ${status.healthy ? 'border-emerald-300/20 bg-emerald-300/10 text-emerald-200' : 'border-amber-300/20 bg-amber-300/10 text-amber-100'}`}>
            {status.healthy ? 'SYSTEM HEALTHY' : 'CHECK REQUIRED'}
          </div>
        </div>

        {error && <div className="mb-5 flex items-start gap-3 rounded-xl border border-rose-300/20 bg-rose-300/10 p-4 text-sm text-rose-100"><TriangleAlert size={18} className="mt-0.5 shrink-0"/><span>{error}</span></div>}
        {lastAction && <div className="mb-5 flex items-start gap-3 rounded-xl border border-emerald-300/20 bg-emerald-300/10 p-4 text-sm text-emerald-100"><CheckCircle2 size={18} className="mt-0.5 shrink-0"/><span>{lastAction.message}</span></div>}

        <div className="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
          {healthItems.map(({ key, icon: Icon, check }) => <NxCard key={key}>
            <div className="flex items-center justify-between gap-3"><Icon size={19} className="text-cyan-300"/><span className={`h-2.5 w-2.5 rounded-full ${check.ok ? 'bg-emerald-400' : 'bg-rose-400'}`}/></div>
            <div className="mt-3 font-bold">{check.label}</div>
            <div className={`mt-1 text-xs ${check.ok ? 'text-emerald-300' : 'text-rose-300'}`}>{check.ok ? 'Operational' : 'Needs attention'}</div>
          </NxCard>)}
        </div>

        <div className="mt-5 grid gap-5 lg:grid-cols-3">
          <NxCard>
            <div className="flex items-center gap-2 text-cyan-300"><RefreshCw size={19}/><strong>Clear caches</strong></div>
            <p className="mt-3 text-sm leading-6 text-slate-400">Clears application, configuration, route, view and event caches. Useful after uploading a patch or changing configuration.</p>
            <button type="button" onClick={() => run('clear')} disabled={busy !== null} className={`${actionButton} mt-5`}>{busy === 'clear' ? 'Clearing…' : 'Clear caches'}</button>
          </NxCard>

          <NxCard>
            <div className="flex items-center gap-2 text-cyan-300"><Sparkles size={19}/><strong>Safe optimize</strong></div>
            <p className="mt-3 text-sm leading-6 text-slate-400">Rebuilds configuration and compiled-view caches. Route caching is intentionally excluded because this platform uses closure routes.</p>
            <button type="button" onClick={() => run('optimize')} disabled={busy !== null} className={`${actionButton} mt-5`}>{busy === 'optimize' ? 'Optimizing…' : 'Optimize safely'}</button>
          </NxCard>

          <NxCard>
            <div className="flex items-center gap-2 text-cyan-300"><ShieldCheck size={19}/><strong>System verification</strong></div>
            <p className="mt-3 text-sm leading-6 text-slate-400">Checks the database, cache, writable storage, frontend manifest and critical runtime files without exposing secrets.</p>
            <button type="button" onClick={() => run('check')} disabled={busy !== null} className={`${actionButton} mt-5`}>{busy === 'check' ? 'Checking…' : 'Verify system'}</button>
          </NxCard>
        </div>

        <div className="mt-5 grid gap-5 lg:grid-cols-2">
          <NxCard>
            <div className="flex items-center gap-2 text-cyan-300"><Gauge size={19}/><strong>Runtime status</strong></div>
            <dl className="mt-4 space-y-3 text-sm">
              <div className="flex items-center justify-between gap-4 border-b border-white/5 pb-3"><dt className="text-slate-400">Environment</dt><dd className="font-semibold">{status.environment}</dd></div>
              <div className="flex items-center justify-between gap-4 border-b border-white/5 pb-3"><dt className="text-slate-400">PHP</dt><dd className="font-semibold">{status.php_version}</dd></div>
              <div className="flex items-center justify-between gap-4 border-b border-white/5 pb-3"><dt className="text-slate-400">Laravel</dt><dd className="font-semibold">{status.laravel_version}</dd></div>
              <div className="flex items-center justify-between gap-4"><dt className="text-slate-400">Maintenance mode</dt><dd className="font-semibold">{status.maintenance_mode ? 'Enabled' : 'Disabled'}</dd></div>
            </dl>
          </NxCard>

          <NxCard>
            <div className="flex items-center gap-2 text-cyan-300"><FileCheck2 size={19}/><strong>Critical files</strong></div>
            {!integrity && <p className="mt-4 text-sm leading-6 text-slate-400">Press “Verify system” to run a fresh critical-file check.</p>}
            {integrity && <div className="mt-4 space-y-2">
              {Object.entries(integrity.files).map(([file, result]) => <div key={file} className="flex items-center justify-between gap-3 rounded-lg border border-white/5 bg-black/20 px-3 py-2 text-xs"><span className="min-w-0 truncate text-slate-300">{file}</span><span className={result.ok ? 'text-emerald-300' : 'text-rose-300'}>{result.ok ? 'OK' : 'MISSING'}</span></div>)}
            </div>}
          </NxCard>
        </div>

        <NxCard className="mt-5">
          <div className="flex flex-wrap items-center justify-between gap-4">
            <div><h2 className="text-lg font-black">Platform updates</h2><p className="mt-1 text-sm text-slate-400">Release installation remains in the verified Update Center with SHA-256 validation, backup and rollback controls.</p></div>
            <a href="/secure-control/updates" className="min-h-11 rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-bold text-slate-100 transition hover:bg-white/10">Open Update Center</a>
          </div>
        </NxCard>
      </div>
    </main>
  </>;
}
