import { Head, Link } from '@inertiajs/react';

export default function Home() {
  return (
    <>
      <Head title="Security Beyond the Visible" />
      <main className="min-h-screen bg-slate-950 text-white">
        <section className="mx-auto grid min-h-screen max-w-7xl place-items-center px-6 py-24 text-center">
          <div>
            <p className="mb-4 tracking-[0.35em] text-cyan-300">NEXVARY SECURITY PLATFORM</p>
            <h1 className="text-5xl font-black tracking-tight sm:text-7xl">Security Beyond the Visible.</h1>
            <p className="mx-auto mt-6 max-w-3xl text-lg text-slate-300">Laravel 13 foundation with React 19, Inertia 3, TypeScript and a security-first release pipeline.</p>
            <div className="mt-10 flex flex-wrap justify-center gap-3">
              <Link className="rounded-xl border border-cyan-400/50 px-5 py-3" href="/about">About NEXVARY</Link>
            </div>
          </div>
        </section>
      </main>
    </>
  );
}
