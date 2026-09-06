import { Head, useForm } from '@inertiajs/react';
import type { FormEvent } from 'react';

export default function Login() {
  const form = useForm({ email: '', password: '', remember: false });

  const submit = (event: FormEvent) => {
    event.preventDefault();
    form.post(location.pathname, { onFinish: () => form.reset('password') });
  };

  return (
    <main className="nx-auth-page">
      <Head title="Secure Access — NEXVARY"><meta name="robots" content="noindex,nofollow,noarchive" /></Head>
      <section className="nx-auth-card">
        <div className="nx-about-emblem nx-auth-emblem" aria-hidden="true"><span>N</span></div>
        <p className="nx-kicker">PRIVATE ADMIN ACCESS</p>
        <h1>NEXVARY Secure Portal</h1>
        <p className="nx-auth-note">Authentication, verified email, rate limiting and multi-factor controls protect this portal.</p>
        <form onSubmit={submit}>
          <label>Email<input autoComplete="username" type="email" value={form.data.email} onChange={(event) => form.setData('email', event.target.value)} required /></label>
          <label>Password<input autoComplete="current-password" type="password" value={form.data.password} onChange={(event) => form.setData('password', event.target.value)} required /></label>
          <label className="nx-checkbox"><input type="checkbox" checked={form.data.remember} onChange={(event) => form.setData('remember', event.target.checked)} /> Remember this device</label>
          {Object.values(form.errors).map((error) => <p className="nx-auth-error" key={error}>{error}</p>)}
          <button className="nx-btn nx-btn-primary nx-auth-submit" disabled={form.processing} type="submit">{form.processing ? 'Checking…' : 'Secure Sign In'}</button>
        </form>
      </section>
    </main>
  );
}
