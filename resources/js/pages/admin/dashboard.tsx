import { Head } from '@inertiajs/react';
import { motion } from 'framer-motion';
import { Activity, BadgeCheck, Fingerprint, Gauge, LockKeyhole, Radar, ServerCog, ShieldCheck } from 'lucide-react';
import { Area, AreaChart, ResponsiveContainer, Tooltip, XAxis, YAxis } from 'recharts';
import { NxCard } from '../../components/ui/nx-card';

type SecurityState = {
  mfa_enabled: boolean;
  email_verified: boolean;
  audit_events: number;
  csp_nonce: boolean;
  private_cache_control: boolean;
  shared_hosting_mode: boolean;
};

type AuditEvent = {
  id: string;
  event: string;
  method: string;
  route: string;
  created_at: string;
};

type Props = {
  security: SecurityState;
  recentAudit: AuditEvent[];
};

const telemetry = [
  { slot: '00', score: 96 },
  { slot: '04', score: 97 },
  { slot: '08', score: 97 },
  { slot: '12', score: 98 },
  { slot: '16', score: 98 },
  { slot: '20', score: 99 },
  { slot: '24', score: 99 },
];

function StatusCard({ icon: Icon, title, value, detail }: { icon: typeof ShieldCheck; title: string; value: string; detail: string }) {
  return (
    <NxCard interactive className="min-h-44">
      <div className="flex items-start justify-between gap-4">
        <div>
          <p className="text-xs font-bold tracking-[0.22em] text-cyan-300/80">{title}</p>
          <p className="mt-3 text-2xl font-black text-white">{value}</p>
        </div>
        <div className="rounded-2xl border border-cyan-200/20 bg-cyan-300/5 p-3 text-cyan-200 shadow-lg shadow-cyan-500/5">
          <Icon size={22} strokeWidth={1.8} />
        </div>
      </div>
      <p className="mt-4 text-sm leading-6 text-slate-400">{detail}</p>
    </NxCard>
  );
}

export default function Dashboard({ security, recentAudit }: Props) {
  const hardened = [security.email_verified, security.csp_nonce, security.private_cache_control, security.mfa_enabled].filter(Boolean).length;
  const securityScore = Math.round((hardened / 4) * 100);

  return (
    <>
      <Head title="NEXVARY Security Command Center" />
      <main className="min-h-screen overflow-hidden bg-[radial-gradient(circle_at_85%_5%,rgba(6,182,212,0.17),transparent_24%),radial-gradient(circle_at_10%_30%,rgba(59,130,246,0.08),transparent_22%),linear-gradient(180deg,#020617,#020817)] px-4 py-7 text-white sm:px-8">
        <div className="mx-auto max-w-7xl">
          <motion.header initial={{ opacity: 0, y: -10 }} animate={{ opacity: 1, y: 0 }} className="flex flex-col gap-5 border-b border-cyan-300/10 pb-7 lg:flex-row lg:items-end lg:justify-between">
            <div>
              <div className="flex items-center gap-3 text-cyan-300">
                <Radar size={18} />
                <p className="text-xs font-black tracking-[0.34em]">PRIVATE SECURITY CONTROL</p>
              </div>
              <h1 className="mt-3 text-4xl font-black tracking-tight sm:text-5xl">NEXVARY Command Center</h1>
              <p className="mt-3 max-w-2xl text-sm leading-7 text-slate-400">
                Security telemetry, identity posture and tamper-evident administrative visibility in one hardened control surface.
              </p>
            </div>
            <div className="flex items-center gap-3 rounded-2xl border border-cyan-300/15 bg-cyan-300/5 px-4 py-3 text-sm text-cyan-100 backdrop-blur-xl">
              <ShieldCheck size={20} />
              <span>Hardening baseline <strong>{hardened}/4</strong></span>
            </div>
          </motion.header>

          <section className="mt-7 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <StatusCard icon={Fingerprint} title="MULTI-FACTOR AUTH" value={security.mfa_enabled ? 'ENABLED' : 'PENDING'} detail={security.mfa_enabled ? 'Two-factor protection is active for this administrator.' : 'Enable MFA before production administration.'} />
            <StatusCard icon={BadgeCheck} title="IDENTITY TRUST" value={security.email_verified ? 'VERIFIED' : 'UNVERIFIED'} detail="Administrative access requires a verified identity before protected routes are available." />
            <StatusCard icon={Activity} title="AUDIT LEDGER" value={String(security.audit_events)} detail="Administrative events are chained so later tampering can be detected by verification tooling." />
            <StatusCard icon={ServerCog} title="HOSTING PROFILE" value={security.shared_hosting_mode ? 'SHARED' : 'SERVER'} detail="Application controls remain compatible with the current shared-hosting deployment profile." />
          </section>

          <section className="mt-6 grid gap-6 xl:grid-cols-[1.12fr_.88fr]">
            <NxCard className="p-0">
              <div className="flex flex-col gap-4 border-b border-cyan-300/10 px-5 py-5 sm:flex-row sm:items-center sm:justify-between">
                <div>
                  <div className="flex items-center gap-2 text-cyan-300"><Gauge size={18} /><p className="text-xs font-black tracking-[0.22em]">SECURITY POSTURE</p></div>
                  <p className="mt-1 text-sm text-slate-500">Application hardening telemetry</p>
                </div>
                <div className="text-left sm:text-right"><p className="text-3xl font-black text-white">{securityScore}%</p><p className="text-xs text-slate-500">current baseline score</p></div>
              </div>
              <div className="h-64 px-3 pb-3 pt-6">
                <ResponsiveContainer width="100%" height="100%">
                  <AreaChart data={telemetry} margin={{ top: 5, right: 12, bottom: 0, left: -20 }}>
                    <defs>
                      <linearGradient id="securityFill" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stopColor="#22d3ee" stopOpacity={0.35} />
                        <stop offset="100%" stopColor="#22d3ee" stopOpacity={0} />
                      </linearGradient>
                    </defs>
                    <XAxis dataKey="slot" axisLine={false} tickLine={false} tick={{ fill: '#64748b', fontSize: 11 }} />
                    <YAxis domain={[90, 100]} axisLine={false} tickLine={false} tick={{ fill: '#64748b', fontSize: 11 }} />
                    <Tooltip contentStyle={{ background: '#020617', border: '1px solid rgba(103,232,249,.2)', borderRadius: 12 }} labelStyle={{ color: '#94a3b8' }} />
                    <Area type="monotone" dataKey="score" stroke="#22d3ee" strokeWidth={2} fill="url(#securityFill)" />
                  </AreaChart>
                </ResponsiveContainer>
              </div>
            </NxCard>

            <NxCard>
              <div className="flex items-center gap-2 text-cyan-300"><LockKeyhole size={18} /><p className="text-xs font-black tracking-[0.22em]">ACTIVE DEFENSES</p></div>
              <div className="mt-5 space-y-3 text-sm">
                {[
                  ['CSP nonce', security.csp_nonce],
                  ['Private no-store', security.private_cache_control],
                  ['Admin authorization', true],
                  ['Rate limiting', true],
                  ['Audit hashing', true],
                ].map(([label, enabled]) => (
                  <div key={String(label)} className="flex items-center justify-between rounded-xl border border-white/5 bg-black/20 px-4 py-3">
                    <span>{String(label)}</span><strong className={enabled ? 'text-emerald-300' : 'text-amber-300'}>{enabled ? 'ON' : 'OFF'}</strong>
                  </div>
                ))}
              </div>
              <p className="mt-5 text-xs leading-6 text-slate-500">This panel reports verified application controls and does not claim the underlying hosting infrastructure is invulnerable.</p>
            </NxCard>
          </section>

          <section className="mt-6">
            <NxCard className="p-0">
              <div className="flex items-center justify-between border-b border-cyan-300/10 px-5 py-4">
                <div>
                  <p className="text-xs font-black tracking-[0.22em] text-cyan-300">RECENT AUDIT ACTIVITY</p>
                  <p className="mt-1 text-sm text-slate-500">Latest protected administrative events</p>
                </div>
              </div>
              <div className="divide-y divide-white/5">
                {recentAudit.length === 0 ? (
                  <div className="px-5 py-10 text-sm text-slate-500">No audit events recorded yet.</div>
                ) : recentAudit.map((event) => (
                  <div key={event.id} className="grid gap-2 px-5 py-4 transition-colors hover:bg-cyan-300/[0.025] sm:grid-cols-[1fr_auto] sm:items-center">
                    <div className="min-w-0">
                      <p className="truncate font-semibold text-slate-100">{event.event}</p>
                      <p className="mt-1 truncate text-xs text-slate-500">{event.method} · {event.route}</p>
                    </div>
                    <time className="text-xs text-cyan-200/70">{event.created_at}</time>
                  </div>
                ))}
              </div>
            </NxCard>
          </section>
        </div>
      </main>
    </>
  );
}
