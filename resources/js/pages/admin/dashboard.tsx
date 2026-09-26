import { Head } from '@inertiajs/react';
import { Activity, BadgeCheck, Fingerprint, LockKeyhole, Radar, ServerCog, ShieldCheck } from 'lucide-react';
import { AdminNav } from '../../components/admin/admin-nav';
import { NxCard } from '../../components/ui/nx-card';

type SecurityState = { mfa_enabled: boolean; email_verified: boolean; audit_events: number; csp_nonce: boolean; private_cache_control: boolean; shared_hosting_mode: boolean };
type AuditEvent = { id: string; event: string; method: string; route: string; created_at: string };
type Props = { security: SecurityState; recentAudit: AuditEvent[] };

function StatusCard({ icon: Icon, title, value, detail, tone = 'neutral' }: { icon: typeof ShieldCheck; title: string; value: string; detail: string; tone?: 'neutral' | 'warning' }) {
  return <NxCard className="nx-admin-stat"><div className="nx-admin-stat-top"><span>{title}</span><Icon size={20} strokeWidth={1.7} aria-hidden="true" /></div><strong className={tone === 'warning' ? 'nx-admin-warning' : ''}>{value}</strong><p>{detail}</p></NxCard>;
}

export default function Dashboard({ security, recentAudit }: Props) {
  const controls = [
    { label: 'Verified owner identity', enabled: security.email_verified, icon: BadgeCheck },
    { label: 'Multi-factor authentication', enabled: security.mfa_enabled, icon: Fingerprint },
    { label: 'CSP nonce protection', enabled: security.csp_nonce, icon: LockKeyhole },
    { label: 'Private no-store responses', enabled: security.private_cache_control, icon: ShieldCheck },
  ];
  const hardened = controls.filter((control) => control.enabled).length;
  return <><Head title="NEXVARY Security Command Center" /><main className="nx-admin-dashboard"><div className="nx-admin-container">
    <AdminNav active="Overview" />
    <header className="nx-admin-heading"><div><p className="nx-admin-kicker"><Radar size={17} /> PRIVATE SECURITY CONTROL</p><h1>NEXVARY Command Center</h1><p>Owner access, operational controls and administrative activity in one protected workspace.</p></div><div className="nx-admin-baseline"><ShieldCheck size={19} /><span>Baseline controls <strong>{hardened} / {controls.length}</strong></span></div></header>
    <section aria-label="Platform overview" className="nx-admin-stats">
      <StatusCard icon={Fingerprint} title="MULTI-FACTOR AUTH" value={security.mfa_enabled ? 'ENABLED' : 'ACTION REQUIRED'} detail={security.mfa_enabled ? 'Additional sign-in factor is active.' : 'Enable MFA before production administration.'} tone={security.mfa_enabled ? 'neutral' : 'warning'} />
      <StatusCard icon={BadgeCheck} title="IDENTITY TRUST" value={security.email_verified ? 'VERIFIED' : 'UNVERIFIED'} detail="Owner email verification status." />
      <StatusCard icon={Activity} title="AUDIT EVENTS" value={String(security.audit_events)} detail="Recorded administrative requests." />
      <StatusCard icon={ServerCog} title="HOSTING PROFILE" value={security.shared_hosting_mode ? 'SHARED' : 'SERVER'} detail="Current application deployment profile." />
    </section>
    <section className="nx-admin-details"><NxCard className="nx-admin-control-card"><div className="nx-admin-panel-heading"><div><p className="nx-admin-kicker"><ShieldCheck size={17} /> HARDENING BASELINE</p><h2>{hardened} of {controls.length} controls active</h2><p>Current server-reported configuration. Complete pending controls before live administration.</p></div><strong>{Math.round(hardened / controls.length * 100)}%</strong></div><div className="nx-admin-progress" role="progressbar" aria-valuenow={hardened} aria-valuemin={0} aria-valuemax={controls.length} aria-label="Active baseline controls"><span style={{ width: `${hardened / controls.length * 100}%` }} /></div><div className="nx-admin-control-list">{controls.map(({ label, enabled, icon: Icon }) => <div key={label}><Icon size={17} aria-hidden="true" /><span>{label}</span><strong className={enabled ? 'nx-admin-ok' : 'nx-admin-warning'}>{enabled ? 'ACTIVE' : 'PENDING'}</strong></div>)}</div></NxCard><NxCard className="nx-admin-defense-card"><p className="nx-admin-kicker"><LockKeyhole size={17} /> ACCESS &amp; AUDIT</p><h2>Protected administration</h2><p>Private pages use authorization, request throttling and audit recording. Review activity and manage account security from the controls below.</p><a href="/secure-control/security">Review security settings <span aria-hidden="true">→</span></a><a href="/secure-control/audit">Open audit ledger <span aria-hidden="true">→</span></a></NxCard></section>
    <section className="nx-admin-activity"><NxCard><div className="nx-admin-panel-heading"><div><p className="nx-admin-kicker"><Activity size={17} /> RECENT AUDIT ACTIVITY</p><h2>Latest administrative events</h2></div><a href="/secure-control/audit">View all <span aria-hidden="true">→</span></a></div><div className="nx-admin-events">{recentAudit.length === 0 ? <p>No audit events recorded yet.</p> : recentAudit.map((event) => <div key={event.id}><div><strong>{event.event}</strong><small>{event.method} · {event.route}</small></div><time>{event.created_at}</time></div>)}</div></NxCard></section>
  </div></main></>;
}
