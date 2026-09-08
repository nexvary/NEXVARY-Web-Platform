import { Head } from '@inertiajs/react';
import { Activity, Radio, ShieldAlert, ShieldCheck } from 'lucide-react';
import { AdminNav } from '../../components/admin/admin-nav';
import { MetricTile, RiskBadge, ThreatTable, type RiskLevel, type ThreatRow } from '../../components/ui/security-command';
import { NxCard } from '../../components/ui/nx-card';

type Signal = {
  id: string;
  source: string;
  vector: string;
  location?: string | null;
  score: number;
  level: RiskLevel;
  occurred_at: string;
  metadata?: Record<string, unknown>;
};

type SourceHealth = { key: string; enabled: boolean; configured: boolean };

type Props = {
  riskScore: number;
  riskLevel: RiskLevel;
  signalCount: number;
  criticalCount: number;
  highCount: number;
  signals: Signal[];
  sources: SourceHealth[];
};

export default function SecurityCommand({ riskScore, riskLevel, signalCount, criticalCount, highCount, signals, sources }: Props) {
  const rows: ThreatRow[] = signals.map((signal) => ({
    id: signal.id,
    source: signal.source,
    vector: signal.vector,
    location: signal.location ?? undefined,
    score: signal.score,
    level: signal.level,
    time: signal.occurred_at,
  }));

  return <><Head title="Security Command Center" /><main className="min-h-screen bg-slate-950 px-4 py-7 text-white sm:px-8"><div className="mx-auto max-w-7xl"><AdminNav active="Command" />
    <div className="mb-6 flex flex-wrap items-start justify-between gap-4">
      <div className="flex items-center gap-3"><ShieldAlert className="text-cyan-300"/><div><h1 className="text-3xl font-black">Security Command Center</h1><p className="text-sm text-slate-400">Unified application, WAF and behavioral threat intelligence.</p></div></div>
      <RiskBadge level={riskLevel} score={riskScore}/>
    </div>
    <section className="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
      <MetricTile label="Global Risk" value={`${riskScore}/100`} status={riskLevel} hint="Highest normalized active signal" />
      <MetricTile label="Signals" value={signalCount} status={signalCount > 0 ? 'guarded' : 'low'} hint="Normalized recent security events" />
      <MetricTile label="High + Critical" value={highCount} status={highCount > 0 ? 'high' : 'low'} hint={`${criticalCount} critical`} />
      <MetricTile label="Sources Online" value={sources.filter((source)=>source.enabled).length} status={sources.some((source)=>source.enabled) ? 'guarded' : 'low'} hint={`${sources.length} adapters registered`} />
    </section>
    <section className="mt-5 grid gap-5 xl:grid-cols-[1.5fr_.5fr]">
      <div><h2 className="mb-3 text-sm font-bold uppercase tracking-[0.18em] text-slate-400">Threat Feed</h2><ThreatTable rows={rows}/></div>
      <div><h2 className="mb-3 text-sm font-bold uppercase tracking-[0.18em] text-slate-400">Integration Health</h2><NxCard><div className="space-y-3">{sources.map((source)=><div key={source.key} className="flex items-center justify-between gap-4 rounded-xl border border-white/5 bg-black/20 px-4 py-3"><div className="flex items-center gap-3">{source.key === 'waf' ? <ShieldCheck size={17} className="text-cyan-300"/> : source.key === 'crowd' ? <Activity size={17} className="text-cyan-300"/> : <Radio size={17} className="text-cyan-300"/>}<div><strong className="block uppercase tracking-[0.12em]">{source.key}</strong><span className="text-xs text-slate-500">{source.configured ? 'Configured' : 'Not configured'}</span></div></div><span className={`rounded-full px-3 py-1 text-xs font-bold ${source.enabled?'bg-emerald-400/10 text-emerald-300':'bg-slate-400/10 text-slate-400'}`}>{source.enabled?'ONLINE':'OFF'}</span></div>)}</div></NxCard></div>
    </section>
  </div></main></>;
}
