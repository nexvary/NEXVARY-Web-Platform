import { Head } from '@inertiajs/react';

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

function StatusCard({ title, value, detail }: { title: string; value: string; detail: string }) {
  return (
    <article className="rounded-2xl border border-cyan-300/15 bg-slate-900/70 p-5 shadow-2xl shadow-black/10">
      <p className="text-xs font-bold tracking-[0.22em] text-cyan-300/80">{title}</p>
      <p className="mt-3 text-2xl font-black text-white">{value}</p>
      <p className="mt-2 text-sm leading-6 text-slate-400">{detail}</p>
    </article>
  );
}

export default function Dashboard({ security, recentAudit }: Props) {
  const hardened = [security.email_verified, security.csp_nonce, security.private_cache_control].filter(Boolean).length;

  return (
    <>
      <Head title="Secure Control" />
      <main className="min-h-screen bg-[radial-gradient(circle_at_top_right,_rgba(8,145,178,0.16),_transparent_28%),linear-gradient(180deg,#020617,#020817)] px-4 py-8 text-white sm:px-8">
        <div className="mx-auto max-w-7xl">
          <div className="flex flex-col gap-5 border-b border-cyan-300/10 pb-7 lg:flex-row lg:items-end lg:justify-between">
            <div>
              <p className="text-xs font-black tracking-[0.34em] text-cyan-300">PRIVATE SECURITY CONTROL</p>
              <h1 className="mt-3 text-4xl font-black tracking-tight sm:text-5xl">NEXVARY Command Center</h1>
              <p className="mt-3 max-w-2xl text-sm leading-7 text-slate-400">
                Security-first administration surface for shared hosting, authentication health, and tamper-evident audit visibility.
              </p>
            </div>
            <div className="rounded-2xl border border-cyan-300/15 bg-cyan-300/5 px-4 py-3 text-sm text-cyan-100">
              Hardening baseline: <strong>{hardened}/3 active</strong>
            </div>
          </div>

          <section className="mt-7 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <StatusCard
              title="MULTI-FACTOR AUTH"
              value={security.mfa_enabled ? 'ENABLED' : 'PENDING'}
              detail={security.mfa_enabled ? 'Two-factor secret is active for this administrator.' : 'Enable MFA before production administration.'}
            />
            <StatusCard
              title="EMAIL TRUST"
              value={security.email_verified ? 'VERIFIED' : 'UNVERIFIED'}
              detail="Administrative access requires a verified identity before protected routes are available."
            />
            <StatusCard
              title="AUDIT LEDGER"
              value={String(security.audit_events)}
              detail="Security events are chained with keyed hashes so later tampering can be detected."
            />
            <StatusCard
              title="HOSTING PROFILE"
              value={security.shared_hosting_mode ? 'SHARED' : 'SERVER'}
              detail="Current controls avoid dependencies on Redis, Docker, Cloudflare, or a VPS."
            />
          </section>

          <section className="mt-7 grid gap-6 xl:grid-cols-[1.2fr_.8fr]">
            <div className="overflow-hidden rounded-3xl border border-cyan-300/15 bg-slate-950/70">
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
                  <div key={event.id} className="grid gap-2 px-5 py-4 sm:grid-cols-[1fr_auto] sm:items-center">
                    <div className="min-w-0">
                      <p className="truncate font-semibold text-slate-100">{event.event}</p>
                      <p className="mt-1 truncate text-xs text-slate-500">{event.method} · {event.route}</p>
                    </div>
                    <time className="text-xs text-cyan-200/70">{event.created_at}</time>
                  </div>
                ))}
              </div>
            </div>

            <aside className="rounded-3xl border border-cyan-300/15 bg-slate-900/65 p-6">
              <p className="text-xs font-black tracking-[0.22em] text-cyan-300">ACTIVE DEFENSES</p>
              <div className="mt-5 space-y-3 text-sm">
                <div className="flex items-center justify-between rounded-xl border border-white/5 bg-black/20 px-4 py-3"><span>CSP nonce</span><strong>{security.csp_nonce ? 'ON' : 'OFF'}</strong></div>
                <div className="flex items-center justify-between rounded-xl border border-white/5 bg-black/20 px-4 py-3"><span>Private no-store</span><strong>{security.private_cache_control ? 'ON' : 'OFF'}</strong></div>
                <div className="flex items-center justify-between rounded-xl border border-white/5 bg-black/20 px-4 py-3"><span>Admin authorization</span><strong>ON</strong></div>
                <div className="flex items-center justify-between rounded-xl border border-white/5 bg-black/20 px-4 py-3"><span>Rate limiting</span><strong>ON</strong></div>
                <div className="flex items-center justify-between rounded-xl border border-white/5 bg-black/20 px-4 py-3"><span>Audit hashing</span><strong>ON</strong></div>
              </div>
              <p className="mt-5 text-xs leading-6 text-slate-500">This panel reports application-level controls. It does not claim the hosting infrastructure itself is invulnerable.</p>
            </aside>
          </section>
        </div>
      </main>
    </>
  );
}
