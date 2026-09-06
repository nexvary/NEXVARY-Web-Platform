import { Head, router } from '@inertiajs/react';
import { ShieldCheck, UserCog } from 'lucide-react';
import { AdminNav } from '../../components/admin/admin-nav';
import { NxCard } from '../../components/ui/nx-card';

type UserRow = { id: number; name: string; email: string; role: string; is_admin: boolean; email_verified: boolean; mfa_enabled: boolean; created_at: string };

const roles = ['viewer', 'editor', 'security', 'admin', 'owner'] as const;

export default function Users({ users, currentUserId }: { users: UserRow[]; currentUserId: number }) {
  const updateRole = (user: UserRow, role: string) => {
    router.post(`/secure-control/users/${user.id}/role`, { role }, { preserveScroll: true });
  };

  return <><Head title="Users & Roles" /><main className="min-h-screen bg-slate-950 px-4 py-7 text-white sm:px-8"><div className="mx-auto max-w-7xl"><AdminNav active="Users" /><div className="mb-6 flex items-center gap-3"><UserCog className="text-cyan-300"/><div><h1 className="text-3xl font-black">Users & Roles</h1><p className="text-sm text-slate-400">Explicit administrative roles with identity and MFA posture.</p></div></div><NxCard className="overflow-x-auto p-0"><table className="w-full min-w-[760px] text-left text-sm"><thead className="border-b border-white/10 text-xs uppercase tracking-wider text-slate-500"><tr><th className="px-5 py-4">Identity</th><th className="px-5 py-4">Role</th><th className="px-5 py-4">Verification</th><th className="px-5 py-4">MFA</th><th className="px-5 py-4">Admin</th></tr></thead><tbody className="divide-y divide-white/5">{users.map((user)=><tr key={user.id}><td className="px-5 py-4"><strong>{user.name}</strong><div className="mt-1 text-xs text-slate-500">{user.email}{user.id===currentUserId?' · you':''}</div></td><td className="px-5 py-4"><select aria-label={`Role for ${user.email}`} value={user.role} disabled={user.id===currentUserId && user.role==='owner'} onChange={(event)=>updateRole(user,event.target.value)} className="min-h-11 rounded-xl border border-cyan-300/15 bg-slate-900 px-3">{roles.map(role=><option key={role} value={role}>{role}</option>)}</select></td><td className="px-5 py-4 text-slate-300">{user.email_verified?'Verified':'Pending'}</td><td className="px-5 py-4 text-slate-300">{user.mfa_enabled?'Enabled':'Pending'}</td><td className="px-5 py-4">{user.is_admin?<span className="inline-flex items-center gap-1 text-emerald-300"><ShieldCheck size={16}/>Yes</span>:<span className="text-slate-500">No</span>}</td></tr>)}</tbody></table></NxCard></div></main></>;
}
