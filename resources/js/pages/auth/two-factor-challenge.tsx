import { Head, useForm } from '@inertiajs/react';
import type { FormEvent } from 'react';
import { useState } from 'react';

export default function TwoFactorChallenge() {
  const [recovery, setRecovery] = useState(false);
  const form = useForm({ code: '', recovery_code: '' });

  const submit = (event: FormEvent) => {
    event.preventDefault();
    form.post(location.pathname);
  };

  return (
    <main className="nx-auth-page">
      <Head title="Two-Factor Challenge — NEXVARY"><meta name="robots" content="noindex,nofollow,noarchive" /></Head>
      <section className="nx-auth-card">
        <p className="nx-kicker">MULTI-FACTOR AUTHENTICATION</p>
        <h1>Verify your identity</h1>
        <p className="nx-auth-note">Use your authenticator code or a single-use recovery code.</p>
        <form onSubmit={submit}>
          {recovery ? (
            <label>Recovery code<input autoComplete="one-time-code" value={form.data.recovery_code} onChange={(event) => form.setData('recovery_code', event.target.value)} required /></label>
          ) : (
            <label>Authentication code<input autoComplete="one-time-code" inputMode="numeric" value={form.data.code} onChange={(event) => form.setData('code', event.target.value)} required /></label>
          )}
          {Object.values(form.errors).map((error) => <p className="nx-auth-error" key={error}>{error}</p>)}
          <button className="nx-btn nx-btn-primary nx-auth-submit" disabled={form.processing} type="submit">Verify</button>
          <button className="nx-auth-switch" type="button" onClick={() => { setRecovery((value) => !value); form.clearErrors(); }}>{recovery ? 'Use authenticator code' : 'Use recovery code'}</button>
        </form>
      </section>
    </main>
  );
}
