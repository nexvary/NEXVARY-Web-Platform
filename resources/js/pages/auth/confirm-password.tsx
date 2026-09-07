import { Head, useForm } from '@inertiajs/react';
import { LockKeyhole } from 'lucide-react';

export default function ConfirmPassword() {
  const form = useForm({ password: '' });
  const submit = (event: React.FormEvent) => {
    event.preventDefault();
    form.post('/secure-access/user/confirm-password');
  };
  return <><Head title="Confirm password" /><main className="grid min-h-screen place-items-center bg-slate-950 px-4 text-white"><form onSubmit={submit} className="w-full max-w-md rounded-3xl border border-cyan-300/15 bg-slate-900/70 p-6 shadow-2xl"><div className="flex items-center gap-3 text-cyan-300"><LockKeyhole/><span className="text-xs font-black tracking-[0.2em]">SENSITIVE ACTION</span></div><h1 className="mt-4 text-2xl font-black">Confirm your password</h1><p className="mt-2 text-sm leading-6 text-slate-400">Re-authentication is required before changing MFA or passkeys.</p><input autoFocus autoComplete="current-password" type="password" value={form.data.password} onChange={(e)=>form.setData('password',e.target.value)} className="mt-5 min-h-12 w-full rounded-xl border border-white/10 bg-slate-950 px-3" placeholder="Password"/>{form.errors.password&&<p className="mt-2 text-sm text-rose-300">{form.errors.password}</p>}<button disabled={form.processing} className="mt-5 min-h-12 w-full rounded-xl border border-cyan-300/20 bg-cyan-300/10 font-semibold text-cyan-100 disabled:opacity-50">Continue securely</button></form></main></>;
}
