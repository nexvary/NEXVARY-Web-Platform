import { Head, Link } from '@inertiajs/react';

export default function SafeScan() {
  const ar = document.documentElement.lang.startsWith('ar');
  return (
    <>
      <Head title="SafeScan Zero-Storage" />
      <main className="nx-page">
        <header className="nx-topbar">
          <Link className="nx-brand" href="/"><span className="nx-brand-mark">N</span><span>NEXVARY</span></Link>
          <nav className="nx-nav"><Link href="/">{ar ? 'الرئيسية' : 'Home'}</Link><Link href="/apps">{ar ? 'التطبيقات' : 'Apps'}</Link><Link href="/about">{ar ? 'عنّا' : 'About'}</Link></nav>
        </header>
        <section className="nx-hero" style={{minHeight:'620px'}}>
          <div className="nx-hero-copy">
            <p className="nx-kicker">ZER0-STORAGE · LOCAL-FIRST</p>
            <h1 style={{fontSize:'clamp(3rem,6vw,5.8rem)'}}>SafeScan</h1>
            <p className="nx-lead">{ar ? 'الفحص الأساسي يتم داخل المتصفح. لا نرفع محتوى الملف إلى خادم NEXVARY، ويمكن إرسال بصمة SHA-256 فقط للتحقق الاختياري من السمعة.' : 'Primary inspection runs in the browser. File contents are not uploaded to NEXVARY; only a SHA-256 hash may be used for an optional reputation lookup.'}</p>
            <div className="nx-actions"><Link className="nx-btn nx-btn-primary" href="/apps">{ar ? 'العودة للتطبيقات' : 'Back to apps'}</Link></div>
          </div>
          <div className="nx-command">
            <div className="nx-radar"><span className="nx-sweep"/><div className="nx-core">S</div></div>
            <div className="nx-command-meta"><span><b>LOCAL</b> browser-side inspection</span><span><b>HASH</b> SHA-256 reputation option</span><span><b>PRIVATE</b> no file-content retention</span></div>
          </div>
        </section>
      </main>
    </>
  );
}
