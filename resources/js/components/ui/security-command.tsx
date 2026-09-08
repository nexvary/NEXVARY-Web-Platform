import type { ReactNode } from 'react';
import { ShieldCheck, TriangleAlert } from 'lucide-react';
import { cn } from '../../lib/cn';
import { NxCard } from './nx-card';

export type RiskLevel = 'low' | 'guarded' | 'elevated' | 'high' | 'critical';

const riskTone: Record<RiskLevel, string> = {
  low: 'border-emerald-400/30 bg-emerald-400/10 text-emerald-200',
  guarded: 'border-cyan-400/30 bg-cyan-400/10 text-cyan-200',
  elevated: 'border-amber-400/30 bg-amber-400/10 text-amber-200',
  high: 'border-orange-400/30 bg-orange-400/10 text-orange-200',
  critical: 'border-rose-400/30 bg-rose-400/10 text-rose-200',
};

export function RiskBadge({ level, score }: { level: RiskLevel; score?: number }) {
  return (
    <span className={cn('inline-flex min-h-8 items-center gap-2 rounded-full border px-3 text-xs font-semibold uppercase tracking-[0.14em]', riskTone[level])}>
      {level === 'critical' || level === 'high' ? <TriangleAlert size={14} /> : <ShieldCheck size={14} />}
      {level}{typeof score === 'number' ? ` · ${score}/100` : ''}
    </span>
  );
}

export function MetricTile({ label, value, hint, status = 'guarded' }: { label: string; value: ReactNode; hint?: string; status?: RiskLevel }) {
  return (
    <NxCard className="min-h-36">
      <div className="flex items-start justify-between gap-4">
        <div>
          <p className="text-xs font-medium uppercase tracking-[0.18em] text-slate-400">{label}</p>
          <div className="mt-3 text-3xl font-semibold text-white">{value}</div>
          {hint ? <p className="mt-2 text-sm text-slate-400">{hint}</p> : null}
        </div>
        <RiskBadge level={status} />
      </div>
    </NxCard>
  );
}

export type ThreatRow = {
  id: string;
  source: string;
  vector: string;
  location?: string;
  level: RiskLevel;
  score: number;
  time: string;
};

export function ThreatTable({ rows }: { rows: ThreatRow[] }) {
  return (
    <NxCard>
      <div className="overflow-x-auto">
        <table className="w-full min-w-[720px] text-sm">
          <thead className="text-left text-xs uppercase tracking-[0.14em] text-slate-500">
            <tr>
              <th className="pb-3">Source</th><th className="pb-3">Vector</th><th className="pb-3">Location</th><th className="pb-3">Risk</th><th className="pb-3">Time</th>
            </tr>
          </thead>
          <tbody className="divide-y divide-white/5">
            {rows.map((row) => (
              <tr key={row.id} className="text-slate-300">
                <td className="py-3 font-mono text-xs text-cyan-200">{row.source}</td>
                <td className="py-3">{row.vector}</td>
                <td className="py-3 text-slate-400">{row.location ?? '—'}</td>
                <td className="py-3"><RiskBadge level={row.level} score={row.score} /></td>
                <td className="py-3 text-slate-500">{row.time}</td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </NxCard>
  );
}

export type AuditEvent = { id: string; title: string; detail?: string; time: string; actor?: string };

export function AuditTimeline({ events }: { events: AuditEvent[] }) {
  return (
    <NxCard>
      <ol className="space-y-4">
        {events.map((event) => (
          <li key={event.id} className="relative border-s border-cyan-300/15 ps-5">
            <span className="absolute -start-1 top-1.5 size-2 rounded-full bg-cyan-300 shadow-[0_0_14px_rgba(103,232,249,.8)]" />
            <div className="flex flex-wrap items-baseline justify-between gap-2">
              <p className="font-medium text-slate-100">{event.title}</p>
              <time className="text-xs text-slate-500">{event.time}</time>
            </div>
            {event.detail ? <p className="mt-1 text-sm text-slate-400">{event.detail}</p> : null}
            {event.actor ? <p className="mt-1 text-xs text-cyan-200/70">Actor: {event.actor}</p> : null}
          </li>
        ))}
      </ol>
    </NxCard>
  );
}
