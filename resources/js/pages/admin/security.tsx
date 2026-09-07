import { Head, router, useForm } from '@inertiajs/react';
import { Passkeys } from '@laravel/passkeys';
import { Fingerprint, KeyRound, ShieldCheck } from 'lucide-react';
import { useEffect, useState } from 'react';
import { AdminNav } from '../../components/admin/admin-nav';
import { NxCard } from '../../components/ui/nx-card';

type PasskeyRow = { id: number; name: string; last_used_at: string | null; created_at: string };
type Props = { mfaEnabled: boolean; mfaConfirmed: boolean; passkeys: PasskeyRow[]; authPrefix: string };

export default function Security({ mfaEnabled, mfaConfirmed, passkeys, authPrefix }: Props) {
  const [registering, setRegistering] = useState(false);
  const [passkeyError, setPasskeyError] = useState<string | null>(null);
  const [qrSvg, setQrSvg] = useState<string | null>(null);
  const [recoveryCodes, setRecoveryCodes] = useState<string[]>([]);
  const [loadingMfaData, setLoadingMfaData] = useState(false);
  const confirmForm = useForm({ code: '' });
  const base = `/${authPrefix}`;

  const loadMfaData = async () => {
    if (!mfaEnabled) return;
    setLoadingMfaData(true);
    try {
      const [qrResponse, codesResponse] = await Promise.all([
        fetch(`${base}/user/two-factor-qr-code`, { headers: { Accept: 'application/json' }, credentials: 'same-origin' }),
        fetch(`${base}/user/two-factor-recovery-codes`, { headers: { Accept: 'application/json' }, credentials: 'same-origin' }),
      ]);
      if (qrResponse.ok) {
        const qr = await qrResponse.json() as { svg?: string };
        setQrSvg(qr.svg ?? null);
      }
      if (codesResponse.ok) {
        const codes = await codesResponse.json() as string[];
        setRecoveryCodes(Array.isArray(codes) ? codes : []);
      }
    } finally {
      setLoadingMfaData(false);
    }
  };

  useEffect(() => { void loadMfaData(); }, [mfaEnabled]);

  const enableMfa = () => router.post(`${base}/user/two-factor-authentication`, {}, { preserveScroll: true });
  const disableMfa = () => router.delete(`${base}/user/two-factor-authentication`, { preserveScroll: true });
  const confirmMfa = () => confirmForm.post(`${base}/user/confirmed-two-factor-authentication`, { preserveScroll: true, onSuccess: () => { confirmForm.reset(); void loadMfaData(); } });
  const regenerateCodes = () => router.post(`${base}/user/two-factor-recovery-codes`, {}, { preserveScroll: true, onSuccess: () => void loadMfaData() });

  const registerPasskey = async () => {
    setRegistering(true);
    setPasskeyError(null);
    try {
      await Passkeys.register({ name: `NEXVARY-${new Date().toISOString().slice(0, 10)}`, routes: { options: `${base}/user/passkeys/options`, submit: `${base}/user/passkeys` } });
      window.location.reload();
    } catch (error) {
      setPasskeyError(error instanceof Error ? error.message : 'Passkey registration failed.');
    } finally {
      setRegistering(false);
    }
  };

  const deletePasskey = (id: number) => router.delete(`${base}/user/passkeys/${id}`, { preserveScroll: true });

  return <><Head title="Identity Security" /><main className="min-h-screen bg-slate-950 px-4 py-7 text-white sm:px-8"><div className="mx-auto max-w-6xl"><AdminNav active="Security" /><div className="mb-6 flex items-center gap-3"><Fingerprint className="text-cyan-300"/><div><h1 className="text-3xl font-black">Identity Security</h1><p className="text-sm text-slate-400">TOTP multi-factor authentication and WebAuthn passkeys.</p></div></div><div className="grid gap-5 lg:grid-cols-2"><NxCard><div className="flex items-center justify-between gap-4"><div><div className="flex items-center gap-2 text-cyan-300"><ShieldCheck size={19}/><strong>TOTP MFA</strong></div><p className="mt-2 text-sm text-slate-400">Status: {mfaEnabled ? (mfaConfirmed ? 'Enabled and confirmed' : 'Awaiting confirmation') : 'Disabled'}</p></div><span className={`rounded-full px-3 py-1 text-xs font-bold ${mfaConfirmed?'bg-emerald-400/10 text-emerald-300':'bg-amber-400/10 text-amber-300'}`}>{mfaConfirmed?'ACTIVE':mfaEnabled?'SETUP':'OFF'}</span></div>{!mfaEnabled?<button type="button" onClick={enableMfa} className="mt-5 min-h-11 rounded-xl border border-cyan-300/20 bg-cyan-300/10 px-4 font-semibold text-cyan-100">Enable MFA</button>:<><div className="mt-5 rounded-2xl border border-white/5 bg-black/20 p-4">{loadingMfaData?<p className="text-sm text-slate-500">Loading authenticator setup…</p>:qrSvg?<div className="mx-auto w-fit rounded-xl bg-white p-3" dangerouslySetInnerHTML={{ __html: qrSvg }}/>:<p className="text-sm text-slate-500">QR code is available after password confirmation.</p>}</div>{!mfaConfirmed&&<div className="mt-4"><label className="text-sm font-semibold">Authenticator code<input inputMode="numeric" autoComplete="one-time-code" maxLength={8} value={confirmForm.data.code} onChange={(e)=>confirmForm.setData('code',e.target.value.replace(/\D/g,''))} className="mt-2 min-h-11 w-full rounded-xl border border-white/10 bg-slate-900 px-3" placeholder="123456"/></label>{confirmForm.errors.code&&<p className="mt-2 text-sm text-rose-300">{confirmForm.errors.code}</p>}<button type="button" onClick={confirmMfa} disabled={confirmForm.processing||confirmForm.data.code.length<6} className="mt-3 min-h-11 rounded-xl border border-emerald-300/20 bg-emerald-300/10 px-4 font-semibold text-emerald-200 disabled:opacity-50">Confirm MFA</button></div>}{recoveryCodes.length>0&&<div className="mt-5"><div className="flex items-center justify-between gap-3"><strong className="text-sm">Recovery codes</strong><button type="button" onClick={regenerateCodes} className="min-h-11 rounded-xl border border-white/10 px-3 text-sm">Regenerate</button></div><div className="mt-3 grid gap-2 sm:grid-cols-2">{recoveryCodes.map((code)=><code key={code} className="rounded-lg border border-white/5 bg-black/30 px-3 py-2 text-xs text-slate-300">{code}</code>)}</div></div>}<button type="button" onClick={disableMfa} className="mt-5 min-h-11 rounded-xl border border-rose-300/20 bg-rose-300/10 px-4 font-semibold text-rose-200">Disable MFA</button></>}<p className="mt-4 text-xs leading-6 text-slate-500">Password confirmation is required before sensitive identity changes.</p></NxCard><NxCard><div className="flex items-center gap-2 text-cyan-300"><KeyRound size={19}/><strong>Passkeys</strong></div><p className="mt-2 text-sm leading-6 text-slate-400">Use biometric unlock, a device PIN, or a hardware security key for phishing-resistant WebAuthn authentication.</p><button type="button" onClick={registerPasskey} disabled={registering} className="mt-5 min-h-11 rounded-xl border border-cyan-300/20 bg-cyan-300/10 px-4 font-semibold text-cyan-100 disabled:opacity-50">{registering?'Registering…':'Register new passkey'}</button>{passkeyError&&<p className="mt-3 text-sm text-rose-300">{passkeyError}</p>}<div className="mt-5 space-y-3">{passkeys.length===0?<p className="text-sm text-slate-500">No passkeys registered yet.</p>:passkeys.map(passkey=><div key={passkey.id} className="flex items-center justify-between gap-4 rounded-xl border border-white/5 bg-black/20 px-4 py-3"><div className="min-w-0"><strong className="block truncate">{passkey.name}</strong><span className="text-xs text-slate-500">Last used: {passkey.last_used_at||'Never'}</span></div><button type="button" onClick={()=>deletePasskey(passkey.id)} className="min-h-11 rounded-xl border border-rose-300/20 px-3 text-sm text-rose-200">Remove</button></div>)}</div></NxCard></div></div></main></>;
}
