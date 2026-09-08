import { Head, Link } from '@inertiajs/react';
import { Download, ExternalLink, PackageOpen, ShieldCheck } from 'lucide-react';
import SiteShell from '../components/site-shell';

type PublishedApp = {
  id: number;
  slug: string;
  name: string;
  tagline?: string | null;
  summary: string;
  platform: string;
  category?: string | null;
  version?: string | null;
  apk_url?: string | null;
  apk_size?: string | null;
  sha256?: string | null;
  icon_url?: string | null;
  downloads: number;
  distribution_mode: 'download' | 'request' | 'private';
  download_enabled: boolean;
  request_url?: string | null;
  availability_note?: string | null;
};

type Props = { apps: PublishedApp[] };

export default function Apps({ apps }: Props) {
  const ar = document.documentElement.lang.startsWith('ar');

  return (
    <SiteShell>
      <Head title={ar ? 'مركز تطبيقات NEXVARY' : 'NEXVARY App Center'}>
        <meta name="description" content="Official NEXVARY application catalog with verified versions and controlled distribution." />
        <link rel="canonical" href="https://nexvary.com/apps" />
      </Head>
      <main className="nx-section nx-page-body">
        <div className="nx-section-title">
          <p>NEXVARY APP CENTER</p>
          <h1>{ar ? 'التطبيقات والإصدارات الرسمية' : 'Official applications and releases'}</h1>
          <p className="mt-3 max-w-3xl text-slate-400">{ar ? 'يظهر هنا فقط ما تم اعتماده ونشره من لوحة NEXVARY App Center.' : 'Only applications approved and published through the NEXVARY App Center appear here.'}</p>
        </div>

        {apps.length === 0 ? (
          <section className="nx-card mt-8 text-center"><PackageOpen className="mx-auto mb-3 text-cyan-300"/><h2>{ar ? 'لا توجد إصدارات منشورة حاليًا' : 'No published releases yet'}</h2></section>
        ) : (
          <div className="nx-grid mt-8">
            {apps.map((app) => (
              <article className="nx-card nx-app-card" key={app.id}>
                <div className="flex items-start justify-between gap-4">
                  <div>{app.icon_url ? <img src={app.icon_url} alt="" className="h-14 w-14 rounded-2xl object-cover"/> : <ShieldCheck className="text-cyan-300" size={42}/>}</div>
                  <div className="nx-card-tag">{app.platform}{app.version ? ` · v${app.version}` : ''}</div>
                </div>
                <h2>{app.name}</h2>
                {app.tagline && <p className="font-semibold text-cyan-100">{app.tagline}</p>}
                <p>{app.summary}</p>
                {app.apk_size && <p className="text-xs text-slate-500">{ar ? 'الحجم' : 'Size'}: {app.apk_size}</p>}
                {app.sha256 && <details className="mt-2 text-xs text-slate-500"><summary>SHA-256</summary><code className="mt-2 block break-all">{app.sha256}</code></details>}
                <div className="mt-5 flex flex-wrap gap-3">
                  {app.distribution_mode === 'download' && app.download_enabled && app.apk_url && (
                    <a className="nx-card-link inline-flex items-center gap-2" href={app.apk_url} rel="noopener noreferrer"><Download size={16}/>{ar ? 'تحميل الإصدار' : 'Download release'}</a>
                  )}
                  {app.distribution_mode === 'request' && app.request_url && (
                    <a className="nx-card-link inline-flex items-center gap-2" href={app.request_url} rel="noopener noreferrer"><ExternalLink size={16}/>{ar ? 'طلب الوصول' : 'Request access'}</a>
                  )}
                  {app.slug === 'safescan' && <Link className="nx-card-link" href="/safescan">{ar ? 'فتح SafeScan' : 'Open SafeScan'} →</Link>}
                </div>
                {app.availability_note && <p className="mt-3 text-xs text-amber-200">{app.availability_note}</p>}
              </article>
            ))}
          </div>
        )}
      </main>
    </SiteShell>
  );
}
