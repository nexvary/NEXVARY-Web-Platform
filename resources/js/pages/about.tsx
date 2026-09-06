import { Head } from '@inertiajs/react';

const links = [
  ['Website', 'https://nexvary.com/'],
  ['Facebook', 'https://www.facebook.com/share/14p9krEn5ij/'],
  ['Email', 'mailto:info@nexvary.com'],
  ['YouTube', 'https://www.youtube.com/@NexvaryInc'],
  ['X', 'https://x.com/Nexvary'],
];

export default function About() {
  return (
    <>
      <Head title="About NEXVARY" />
      <main className="min-h-screen bg-slate-950 px-6 py-20 text-white" dir="auto">
        <div className="mx-auto max-w-5xl">
          <button className="mb-8 min-h-11 rounded-xl border border-cyan-500/50 px-4" onClick={() => history.back()}>← Back</button>
          <h1 className="text-4xl font-black sm:text-6xl">About NEXVARY</h1>
          <p className="mt-6 max-w-3xl text-slate-300">Cybersecurity, technical counter-surveillance, digital intelligence and privacy-first security technology.</p>
          <div className="mt-10 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            {links.map(([label, href]) => <a key={label} className="rounded-2xl border border-slate-700 bg-slate-900 p-5 hover:border-cyan-400" href={href}>{label}</a>)}
          </div>
        </div>
      </main>
    </>
  );
}
