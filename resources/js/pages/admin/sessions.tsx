import { Head, router } from '@inertiajs/react';
import { Laptop, LogOut, Smartphone } from 'lucide-react';
import { AdminNav } from '../../components/admin/admin-nav';
import { NxCard } from '../../components/ui/nx-card';

type SessionRow = { id: string; ip_address: string | null; user_agent: string | null; last_activity: string; current: boolean };

export default function Sessions({ sessions }: { sessions: SessionRow[] }) {
  const revoke = (id: string) => router.delete(`/secure-control/sessions/${id}`, { preserveScroll: true });
  const revokeOthers = () => router.delete('/secure-control/sessions', { preserveScroll: true });

  return <><Head title="Active Sessions" /><main className="min-h-screen bg-slate-950 px-4 py-7 text-white sm:px-8"><div className="mx-auto max-w-7xl"><AdminNav active="Sessions" /><div className="mb-6 flex flex-wrap items-end justify-between gap-4"><div><h1 className="text-3xl font-black">Active Sessions</h1><p className="mt-1 text-sm text-slate-400">Review and revoke authenticated browser sessions.</p></div><button type="button" onClick={revokeOthers} className="min-h-11 rounded-xl border border-amber-300/20 bg-amber-300/10 px-4 text-sm font-semibold text-amber-200">Revoke other sessions</button></div><div className="grid gap-4">{sessions.map((session)=><NxCard key={session.id}><div className="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between"><div className="flex min-w-0 gap-3">{session.user_agent?.toLowerCase().includes('mobile')?<Smartphone className="mt-1 shrink-0 text-cyan-300"/>:<Laptop className="mt-1 shrink-0 text-cyan-300"/>}<div className="min-w-0"><div className="flex flex-wrap items-center gap-2"><strong>{session.current?'Current session':'Authenticated session'}</strong>{session.current&&<span className="rounded-full bg-emerald-400/10 px-2 py-0.5 text-xs text-emerald-300">CURRENT</span>}</div><p className="mt-1 truncate text-xs text-slate-500">{session.user_agent||'Unknown client'}</p><p className="mt-1 text-xs text-slate-500">IP: {session.ip_address||'Unknown'} · Last activity: {session.last_activity}</p></div></div>{!session.current&&<button type="button" onClick={()=>revoke(session.id)} className="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl border border-rose-300/20 bg-rose-300/10 px-4 text-sm font-semibold text-rose-200"><LogOut size={16}/>Revoke</button>}</div></NxCard>)}</div></div></main></>;
}
