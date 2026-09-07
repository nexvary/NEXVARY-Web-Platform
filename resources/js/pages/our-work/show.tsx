import { Head } from '@inertiajs/react';
import { Clock3, Download, FileCheck2, LockKeyhole, MessageCircleMore, ShieldAlert, Smartphone, Tag } from 'lucide-react';
import SiteShell from '../../components/site-shell';

type DistributionMode = 'download' | 'showcase' | 'request' | 'internal' | 'coming_soon';

type AppDetail = {
  slug: string; name: string; tagline?: string | null; summary: string; description?: string | null;
  platform: string; category?: string | null; version?: string | null; apk_url?: string | null;
  apk_size?: string | null; sha256?: string | null; icon_url?: string | null; screenshots?: string[];
  features?: string[]; changelog?: string | null; downloads: number; published_at?: string | null;
  distribution_mode: DistributionMode; download_enabled: boolean; can_download: boolean; request_url?: string | null;
  availability_note?: string | null;
};

export default function OurWorkShow({ app }: { app: AppDetail }) {
  const ar = document.documentElement.lang.startsWith('ar');
  const modeLabel: Record<DistributionMode, string> = {
    download: ar ? 'متاح للتنزيل' : 'Available for download',
    showcase: ar ? 'عرض فقط' : 'Showcase only',
    request: ar ? 'متاح عند الطلب' : 'Available on request',
    internal: ar ? 'منتج داخلي / خاص' : 'Private / internal product',
    coming_soon: ar ? 'قريبًا' : 'Coming soon',
  };

  const statusIcon = app.distribution_mode === 'request' ? <MessageCircleMore size={18}/> : app.distribution_mode === 'coming_soon' ? <Clock3 size={18}/> : <LockKeyhole size={18}/>;

  return (
    <SiteShell>
      <Head title={`${app.name} · ${ar ? 'أعمالنا' : 'Our Work'}`} />
      <main className="mx-auto w-full max-w-7xl px-4 py-8 text-white sm:px-8">
        <section className="grid gap-8 rounded-3xl border border-cyan-300/15 bg-slate-950/75 p-6 lg:grid-cols-[1.25fr_.75fr] lg:p-10">
          <div>
            <div className="mb-5 flex items-center gap-4">
              {app.icon_url ? <img src={app.icon_url} alt="" className="h-20 w-20 rounded-2xl border border-cyan-300/20 object-cover"/> : <div className="grid h-20 w-20 place-items-center rounded-2xl border border-cyan-300/20 bg-cyan-300/10 text-3xl font-black text-cyan-200">N</div>}
              <div><div className="text-sm font-bold tracking-widest text-cyan-300">NEXVARY PRODUCT</div><h1 className="text-4xl font-black sm:text-5xl">{app.name}</h1><p className="mt-1 text-slate-400">{app.tagline}</p></div>
            </div>
            <p className="max-w-3xl text-lg leading-8 text-slate-200">{app.summary}</p>
            {app.description && <p className="mt-6 whitespace-pre-line leading-8 text-slate-300">{app.description}</p>}
            <div className="mt-7 flex flex-wrap gap-3">
              {app.can_download && <a href={`/our-work/${app.slug}/download`} className="inline-flex min-h-12 items-center gap-2 rounded-xl bg-cyan-300 px-5 py-3 font-black text-slate-950 hover:bg-cyan-200"><Download size={19}/>{ar ? 'تحميل التطبيق' : 'Download application'}</a>}
              {app.distribution_mode === 'request' && app.request_url && <a href={app.request_url} className="inline-flex min-h-12 items-center gap-2 rounded-xl bg-cyan-300 px-5 py-3 font-black text-slate-950 hover:bg-cyan-200" target="_blank" rel="noreferrer"><MessageCircleMore size={19}/>{ar ? 'طلب الوصول' : 'Request access'}</a>}
              {!app.can_download && app.distribution_mode !== 'request' && <span className="inline-flex min-h-12 items-center gap-2 rounded-xl border border-amber-300/20 bg-amber-300/5 px-4 py-3 text-sm font-bold text-amber-100">{statusIcon}{modeLabel[app.distribution_mode]}</span>}
              <span className="inline-flex min-h-12 items-center gap-2 rounded-xl border border-white/10 px-4 py-3 text-sm text-slate-300"><Smartphone size={17}/>{app.platform}</span>
            </div>
            {app.availability_note && <div className="mt-4 max-w-3xl rounded-xl border border-white/10 bg-white/[.03] p-4 text-sm leading-7 text-slate-300">{app.availability_note}</div>}
          </div>
          <aside className="rounded-2xl border border-white/10 bg-black/20 p-5">
            <h2 className="font-black text-cyan-200">{ar ? 'معلومات الإصدار' : 'Release information'}</h2>
            <dl className="mt-4 space-y-4 text-sm">
              <div className="flex justify-between gap-4 border-b border-white/5 pb-3"><dt className="text-slate-500">Availability</dt><dd>{modeLabel[app.distribution_mode]}</dd></div>
              <div className="flex justify-between gap-4 border-b border-white/5 pb-3"><dt className="text-slate-500">Version</dt><dd>{app.version || '—'}</dd></div>
              {app.can_download && <div className="flex justify-between gap-4 border-b border-white/5 pb-3"><dt className="text-slate-500">Size</dt><dd>{app.apk_size || '—'}</dd></div>}
              {app.can_download && <div className="flex justify-between gap-4 border-b border-white/5 pb-3"><dt className="text-slate-500">Downloads</dt><dd>{app.downloads}</dd></div>}
              <div className="flex justify-between gap-4"><dt className="text-slate-500">Category</dt><dd>{app.category || '—'}</dd></div>
            </dl>
            {app.can_download && app.sha256 && <div className="mt-5 rounded-xl border border-emerald-400/15 bg-emerald-400/5 p-4"><div className="mb-2 flex items-center gap-2 text-sm font-bold text-emerald-300"><FileCheck2 size={17}/>SHA-256</div><code className="break-all text-xs text-slate-400">{app.sha256}</code></div>}
          </aside>
        </section>

        {!!app.screenshots?.length && <section className="mt-8"><h2 className="mb-5 text-2xl font-black">{ar ? 'صور التطبيق' : 'Screenshots'}</h2><div className="grid gap-5 md:grid-cols-2">{app.screenshots.map((src) => <img key={src} src={src} alt={`${app.name} screenshot`} className="w-full rounded-2xl border border-white/10 bg-slate-950 object-cover" loading="lazy"/>)}</div></section>}
        {!!app.features?.length && <section className="mt-8 rounded-3xl border border-white/10 bg-slate-950/60 p-6"><h2 className="mb-5 text-2xl font-black">{ar ? 'المميزات' : 'Features'}</h2><div className="grid gap-3 md:grid-cols-2">{app.features.map((feature) => <div key={feature} className="flex gap-3 rounded-xl border border-white/5 bg-white/[.02] p-4"><Tag size={18} className="mt-1 shrink-0 text-cyan-300"/><span className="leading-7 text-slate-300">{feature}</span></div>)}</div></section>}
        {app.changelog && <section className="mt-8 rounded-3xl border border-white/10 bg-slate-950/60 p-6"><h2 className="mb-4 text-2xl font-black">{ar ? 'سجل التغييرات' : 'Changelog'}</h2><p className="whitespace-pre-line leading-8 text-slate-300">{app.changelog}</p></section>}

        <section className="mt-8 flex gap-3 rounded-2xl border border-amber-400/15 bg-amber-400/5 p-5 text-sm leading-7 text-amber-100"><ShieldAlert className="mt-1 shrink-0" size={20}/><p>{ar ? 'إتاحة صفحة المشروع لا تعني إتاحة ملفه للتنزيل. التنزيل يعمل فقط للمشروعات التي فعّلت NEXVARY لها وضع التنزيل العام. عند توفر SHA-256 قارن البصمة قبل التثبيت.' : 'Publishing a project page does not imply package availability. Downloads work only for projects explicitly enabled for public distribution by NEXVARY. Verify the SHA-256 fingerprint when provided.'}</p></section>
      </main>
    </SiteShell>
  );
}
