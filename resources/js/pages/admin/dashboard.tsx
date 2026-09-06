import { Head } from '@inertiajs/react';

export default function Dashboard() {
  return (
    <>
      <Head title="Secure Control" />
      <main className="min-h-screen bg-slate-950 p-8 text-white">
        <div className="mx-auto max-w-7xl">
          <p className="text-sm tracking-[0.3em] text-cyan-300">PRIVATE ADMINISTRATION</p>
          <h1 className="mt-3 text-4xl font-black">NEXVARY Command Center</h1>
        </div>
      </main>
    </>
  );
}
