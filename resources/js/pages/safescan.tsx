import { Head } from '@inertiajs/react';
import SecurityIcon from '../components/security-icon';
import SiteShell from '../components/site-shell';

export default function SafeScan() {
  const ar = document.documentElement.lang.startsWith('ar');

  return (
    <SiteShell>
      <Head title="SafeScan Zero-Storage">
        <meta name="description" content="SafeScan performs primary browser-side inspection without uploading file contents to NEXVARY." />
        <link rel="canonical" href="https://nexvary.com/safescan" />
      </Head>
      <main>
        <section className="nx-hero nx-safescan-hero">
          <div className="nx-hero-copy">
            <p className="nx-kicker">ZERO-STORAGE · LOCAL-FIRST</p>
            <div className="nx-product-heading"><SecurityIcon name="shield" /><h1>SafeScan</h1></div>
            <p className="nx-lead">{ar ? 'الفحص الأساسي يتم داخل المتصفح. لا يتم رفع محتوى الملف إلى خادم NEXVARY، ويمكن استخدام بصمة SHA-256 فقط للتحقق الاختياري من السمعة.' : 'Primary inspection runs in the browser. File contents are not uploaded to NEXVARY; only a SHA-256 hash may be used for an optional reputation lookup.'}</p>
            <div className="nx-trust-row"><span>Browser-side</span><span>SHA-256</span><span>No file retention</span><span>Privacy-first</span></div>
          </div>
          <div className="nx-command">
            <div className="nx-radar"><span className="nx-sweep"/><div className="nx-core">S</div></div>
            <div className="nx-command-meta"><span><b>LOCAL</b>browser-side inspection</span><span><b>HASH</b>SHA-256 reputation option</span><span><b>PRIVATE</b>no file-content retention</span></div>
          </div>
        </section>

        <section className="nx-section nx-scan-explainer">
          <div className="nx-section-title"><p>PRIVACY MODEL</p><h2>{ar ? 'ما الذي يحدث لملفك؟' : 'What happens to your file?'}</h2></div>
          <div className="nx-grid">
            <article className="nx-card"><div className="nx-icon">01</div><h3>{ar ? 'قراءة محلية' : 'Local read'}</h3><p>{ar ? 'يقرأ المتصفح خصائص الملف محليًا لإجراء التحليل الأساسي.' : 'The browser reads file characteristics locally for primary inspection.'}</p></article>
            <article className="nx-card"><div className="nx-icon">02</div><h3>SHA-256</h3><p>{ar ? 'يمكن حساب بصمة تشفير للملف دون إرسال محتواه.' : 'A cryptographic fingerprint can be calculated without sending file contents.'}</p></article>
            <article className="nx-card"><div className="nx-icon">03</div><h3>{ar ? 'تحقق اختياري' : 'Optional lookup'}</h3><p>{ar ? 'إذا تم تفعيل التحقق من السمعة، ترسل البصمة فقط إلى خدمة السمعة.' : 'If reputation lookup is enabled, only the hash is sent to the reputation service.'}</p></article>
          </div>
        </section>
      </main>
    </SiteShell>
  );
}
