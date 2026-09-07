import { Head } from '@inertiajs/react';
import { Activity, ShieldCheck } from 'lucide-react';
import { AdminNav } from '../../components/admin/admin-nav';
import { NxCard } from '../../components/ui/nx-card';

type AuditEvent = { id: string; event: string; method: string; route: string; created_at: string };

export default function Audit({ events }: { events: AuditEvent[] }) {
  return <><Head title="Security Audit Ledger" /><main className="min-h-screen bg-slate-950 px-4 py-7 text-white sm:px-8"><div className="mx-auto max-w-7xl"><AdminNav active="Audit" /><div className="mb-6 flex items-center gap-3"><div className="rounded-2xl border border-cyan-300/20 bg-cyan-300/5 p-3 text-cyan-300"><Activity /></div><div><h1 className="text-3xl font-black">Security Audit Ledger</h1><p className="text-sm text-slate-400">Tamper-evident administrative activity stream.</p></div></div><NxCard className="p-0"><div className="divide-y divide-white/5">{events.length === 0 ? <div className="px-5 py-12 text-slate-500">No audit events recorded.</div> : events.map((event) => <div key={event.id} className="grid gap-3 px-5 py-4 md:grid-cols-[1fr_auto] md:items-center"><div><div className="flex items-center gap-2"><ShieldCheck size={16} className="text-emerald-300"/><p className="font-semibold">{event.event}</p></div><p className="mt-1 text-xs text-slate-500">{event.method} · {event.route}</p></div><time className="text-xs text-cyan-200/70">{event.created_at}</time></div>)}</div></NxCard></div></main></>;
}
