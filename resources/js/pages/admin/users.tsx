import { Head, router, useForm } from '@inertiajs/react';
import { ShieldCheck, UserCog, UserPlus } from 'lucide-react';
import { FormEvent } from 'react';
import { AdminNav } from '../../components/admin/admin-nav';
import { NxCard } from '../../components/ui/nx-card';

type UserRow = { id: number; name: string; email: string; role: string; is_admin: boolean; email_verified: boolean; mfa_enabled: boolean; created_at: string };

const roles = ['viewer', 'editor', 'security', 'admin', 'owner'] as const;
const createRoles = ['viewer', 'editor', 'security', 'admin'] as const;

export default function Users({ users, currentUserId }: { users: UserRow[]; currentUserId: number }) {
  const currentUser = users.find((user) => user.id === currentUserId);
  const canCreate = currentUser?.role === 'owner';
  const createForm = useForm({ name: '', email: '', password: '', password_confirmation: '', role: 'admin' });

  const updateRole = (user: UserRow, role: string) => {
    router.post(`/secure-control/users/${user.id}/role`, { role }, { preserveScroll: true });
  };

  const createUser = (event: FormEvent) => {
    event.preventDefault();
    createForm.post('/secure-control/users', {
      preserveScroll: true,
      onSuccess: () => createForm.reset(),
    });
  };

  return <><Head title="Users & Roles" /><main className="min-h-screen bg-slate-950 px-4 py-7 text-white sm:px-8"><div className="mx-auto max-w-7xl"><AdminNav active="Users" />
    <div className="mb-6 flex items-center gap-3"><UserCog className="text-cyan-300"/><div><h1 className="text-3xl font-black">Users & Roles</h1><p className="text-sm text-slate-400">Explicit administrative roles with identity and MFA posture.</p></div></div>

    {canCreate && <NxCard className="mb-6 p-5 sm:p-6"><div className="mb-5 flex items-center gap-3"><UserPlus className="text-cyan-300"/><div><h2 className="text-xl font-black">Create administrator</h2><p className="text-sm text-slate-400">Create a second trusted account without exposing or changing the owner password.</p></div></div>
      {Object.values(createForm.errors).length > 0 && <div role="alert" className="mb-4 rounded-xl border border-red-400/25 bg-red-500/10 p-4 text-sm text-red-200">{Object.values(createForm.errors).map((error)=><div key={error}>{error}</div>)}</div>}
      <form onSubmit={createUser} className="grid gap-4 md:grid-cols-2">
        <label className="text-sm text-slate-300">Name<input className="mt-2 min-h-12 w-full rounded-xl border border-cyan-300/15 bg-slate-900 px-4 text-white" value={createForm.data.name} onChange={(event)=>createForm.setData('name',event.target.value)} required /></label>
        <label className="text-sm text-slate-300">Email<input type="email" className="mt-2 min-h-12 w-full rounded-xl border border-cyan-300/15 bg-slate-900 px-4 text-white" value={createForm.data.email} onChange={(event)=>createForm.setData('email',event.target.value)} required /></label>
        <label className="text-sm text-slate-300">Password<input type="password" autoComplete="new-password" minLength={12} className="mt-2 min-h-12 w-full rounded-xl border border-cyan-300/15 bg-slate-900 px-4 text-white" value={createForm.data.password} onChange={(event)=>createForm.setData('password',event.target.value)} required /></label>
        <label className="text-sm text-slate-300">Confirm password<input type="password" autoComplete="new-password" minLength={12} className="mt-2 min-h-12 w-full rounded-xl border border-cyan-300/15 bg-slate-900 px-4 text-white" value={createForm.data.password_confirmation} onChange={(event)=>createForm.setData('password_confirmation',event.target.value)} required /></label>
        <label className="text-sm text-slate-300">Role<select className="mt-2 min-h-12 w-full rounded-xl border border-cyan-300/15 bg-slate-900 px-4 text-white" value={createForm.data.role} onChange={(event)=>createForm.setData('role',event.target.value)}>{createRoles.map((role)=><option key={role} value={role}>{role}</option>)}</select></label>
        <div className="flex items-end"><button type="submit" disabled={createForm.processing} className="nx-btn nx-btn-primary min-h-12 w-full md:w-auto">{createForm.processing?'Creating…':'Create account'}</button></div>
      </form>
      <p className="mt-4 text-xs text-slate-500">New accounts are marked email-verified by the owner. Owner role cannot be created from this form.</p>
    </NxCard>}

    <NxCard className="overflow-x-auto p-0"><table className="w-full min-w-[760px] text-left text-sm"><thead className="border-b border-white/10 text-xs uppercase tracking-wider text-slate-500"><tr><th className="px-5 py-4">Identity</th><th className="px-5 py-4">Role</th><th className="px-5 py-4">Verification</th><th className="px-5 py-4">MFA</th><th className="px-5 py-4">Admin</th></tr></thead><tbody className="divide-y divide-white/5">{users.map((user)=><tr key={user.id}><td className="px-5 py-4"><strong>{user.name}</strong><div className="mt-1 text-xs text-slate-500">{user.email}{user.id===currentUserId?' · you':''}</div></td><td className="px-5 py-4"><select aria-label={`Role for ${user.email}`} value={user.role} disabled={user.id===currentUserId && user.role==='owner'} onChange={(event)=>updateRole(user,event.target.value)} className="min-h-11 rounded-xl border border-cyan-300/15 bg-slate-900 px-3">{roles.map(role=><option key={role} value={role}>{role}</option>)}</select></td><td className="px-5 py-4 text-slate-300">{user.email_verified?'Verified':'Pending'}</td><td className="px-5 py-4 text-slate-300">{user.mfa_enabled?'Enabled':'Pending'}</td><td className="px-5 py-4">{user.is_admin?<span className="inline-flex items-center gap-1 text-emerald-300"><ShieldCheck size={16}/>Yes</span>:<span className="text-slate-500">No</span>}</td></tr>)}</tbody></table></NxCard></div></main></>;
}
