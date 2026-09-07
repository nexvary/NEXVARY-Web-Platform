import { Head, useForm } from '@inertiajs/react';
import { Passkeys } from '@laravel/passkeys';
import { Fingerprint } from 'lucide-react';
import { useState, type FormEvent } from 'react';

export default function Login() {
  const form = useForm({ email: '', password: '', remember: false });
  const [passkeyBusy, setPasskeyBusy] = useState(false);
  const [passkeyError, setPasskeyError] = useState<string | null>(null);
  const [loginNotice, setLoginNotice] = useState<string | null>(null);

  const submit = (event: FormEvent) => {
    event.preventDefault();
    setLoginNotice(null);
    form.post(location.pathname, {
      preserveScroll: true,
      onError: (errors) => {
        const first = Object.values(errors)[0];
        setLoginNotice(typeof first === 'string' ? first : 'Sign-in was rejected. Please verify the account details and try again.');
      },
      onSuccess: () => setLoginNotice(null),
    });
  };

  const signInWithPasskey = async () => {
    setPasskeyBusy(true);
    setPasskeyError(null);
    try {
      const prefix = location.pathname.split('/').filter(Boolean)[0] ?? 'secure-access';
      await Passkeys.verify({ routes: { options: `/${prefix}/passkeys/login/options`, submit: `/${prefix}/passkeys/login` } });
      location.href = '/secure-control/';
    } catch (error) {
      setPasskeyError(error instanceof Error ? error.message : 'Passkey sign-in was not completed.');
    } finally {
      setPasskeyBusy(false);
    }
  };

  const errors = Object.values(form.errors);

  return (
    <main className="nx-auth-page">
      <Head title="Secure Access — NEXVARY"><meta name="robots" content="noindex,nofollow,noarchive" /></Head>
      <section className="nx-auth-card">
        <div className="nx-about-emblem nx-auth-emblem" aria-hidden="true"><span>N</span></div>
        <p className="nx-kicker">PRIVATE ADMIN ACCESS</p>
        <h1>NEXVARY Secure Portal</h1>
        <p className="nx-auth-note">Authentication, verified email, rate limiting, MFA and WebAuthn passkeys protect this portal.</p>
        {(loginNotice || errors.length > 0) && (
          <div className="nx-auth-error" role="alert" aria-live="assertive">
            <strong>Sign-in failed.</strong>
            <div>{loginNotice ?? errors[0]}</div>
          </div>
        )}
        <form onSubmit={submit}>
          <label>Email<input autoComplete="username webauthn" type="email" value={form.data.email} onChange={(event) => form.setData('email', event.target.value)} required /></label>
          <label>Password<input autoComplete="current-password" type="password" value={form.data.password} onChange={(event) => form.setData('password', event.target.value)} required /></label>
          <label className="nx-checkbox"><input type="checkbox" checked={form.data.remember} onChange={(event) => form.setData('remember', event.target.checked)} /> Remember this device</label>
          <button className="nx-btn nx-btn-primary nx-auth-submit" disabled={form.processing} type="submit">{form.processing ? 'Checking…' : 'Secure Sign In'}</button>
        </form>
        <button type="button" onClick={signInWithPasskey} disabled={passkeyBusy} className="nx-btn nx-auth-submit mt-3 w-full gap-2"><Fingerprint size={18}/>{passkeyBusy?'Waiting for passkey…':'Sign in with passkey'}</button>
        {passkeyError&&<p className="nx-auth-error">{passkeyError}</p>}
      </section>
    </main>
  );
}
