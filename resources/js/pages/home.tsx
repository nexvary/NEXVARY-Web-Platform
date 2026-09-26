import { Head, Link, router } from '@inertiajs/react';
import { Activity, Bell, Database, FileSearch, Globe2, Home as HomeIcon, Languages, Mail, Radar, ScanSearch, Search, Settings, ShieldCheck, Users } from 'lucide-react';
import { useEffect, useMemo, useState } from 'react';
import '../../css/site-command-v9.css';

const nav = [
  { ar: 'الرئيسية', en: 'Home', href: '/', icon: HomeIcon },
  { ar: 'الخدمات', en: 'Services', href: '/services', icon: Radar },
  { ar: 'التهديدات', en: 'Threats', href: '/safescan', icon: ShieldCheck },
  { ar: 'الرصد والتحليل', en: 'Analytics', href: '/apps', icon: Activity },
  { ar: 'الأصول الرقمية', en: 'Assets', href: '/our-work', icon: Database },
  { ar: 'التقارير', en: 'Reports', href: '/contact', icon: FileSearch },
  { ar: 'الإعدادات', en: 'Settings', href: '/about', icon: Settings },
];

const threats = [
  ['17:24:12', 'محاولة وصول غير مصرح', 'Unauthorized access attempt', 'RU', 'critical'],
  ['17:23:54', 'نشاط فحص للمنافذ', 'Port scanning activity', 'CN', 'warn'],
  ['17:23:28', 'بصمة خبيثة محتملة', 'Possible malicious fingerprint', 'IR', 'warn'],
  ['17:22:41', 'نشاط عابر على API', 'Transient API activity', 'US', 'ok'],
  ['17:22:10', 'محاولة تصعيد صلاحيات', 'Privilege escalation attempt', 'DE', 'critical'],
] as const;

export default function Home() {
  const ar = document.documentElement.lang.startsWith('ar');
  const t = (a: string, e: string) => (ar ? a : e);
  const [now, setNow] = useState(new Date());
  useEffect(() => { const id = window.setInterval(() => setNow(new Date()), 1000); return () => window.clearInterval(id); }, []);
  const cairo = useMemo(() => new Intl.DateTimeFormat(ar ? 'ar-EG' : 'en-GB', { timeZone: 'Africa/Cairo', hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false }).format(now), [now, ar]);
  const utc = useMemo(() => new Intl.DateTimeFormat('en-GB', { timeZone: 'UTC', hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false }).format(now), [now]);

  const tabStyle = { minHeight: 44 } as const;

  return (
    <div className="nx-exec" dir={ar ? 'rtl' : 'ltr'}>
      <Head title={t('NEXVARY — مركز العمليات الأمنية العالمي', 'NEXVARY — Global Security Operations Center')} />
      <header className="nx-exec-top" data-testid="site-header">
        <Link href="/" className="nx-exec-brand"><img src="/nexvary-mark.svg" alt="" /><span><b>NEXVARY</b><small>SECURITY BEYOND THE VISIBLE</small></span></Link>
        <label className="nx-exec-search"><Search size={18} /><input aria-label={t('البحث', 'Search')} placeholder={t('البحث في المنصة ...', 'Search the platform ...')} /></label>
        <div className="nx-exec-tools"><button type="button" aria-label={t('التنبيهات', 'Alerts')}><Bell /></button><button type="button" onClick={() => router.post(`/locale/${ar ? 'en' : 'ar'}`)}><Languages />{ar ? 'EN' : 'AR'}</button><span className="nx-user-dot">N</span><span className="nx-user-label"><b>{t('مدير النظام', 'Administrator')}</b><small>NEXVARY</small></span></div>
      </header>

      <div className="nx-exec-shell">
        <aside className="nx-exec-side">
          <nav>{nav.map(({ ar: a, en: e, href, icon: Icon }, i) => <Link key={href} href={href} className={i === 0 ? 'active' : ''}><Icon /><span>{t(a, e)}</span></Link>)}</nav>
          <div className="nx-side-globe"><Globe2 /><b>NEXVARY</b><span>GLOBAL INTELLIGENCE</span><small>SAFER TOGETHER</small></div>
        </aside>

        <main className="nx-exec-main">
          <section className="nx-kpi-head">
            <div className="nx-title-block"><small>{t('مرحبًا بك في', 'WELCOME TO')}</small><h1>{t('مركز العمليات الأمنية العالمي', 'Global Security Operations Center')}</h1><p>{t('مراقبة · تحليل · استجابة · حماية', 'Monitor · Analyze · Respond · Protect')}</p></div>
            <div className="nx-kpi"><Globe2 /><strong>183</strong><span>{t('دولة مغطاة', 'Countries')}</span></div>
            <div className="nx-kpi"><Database /><strong>2.4M</strong><span>{t('مصدر بيانات', 'Data sources')}</span></div>
            <div className="nx-kpi"><ShieldCheck /><strong>17,382</strong><span>{t('إشارة نشطة', 'Signals')}</span></div>
            <div className="nx-kpi"><Users /><strong>99.98%</strong><span>{t('جاهزية', 'Availability')}</span></div>
          </section>

          <section className="nx-visual-grid">
            <div className="nx-map-card">
              <div className="nx-map-tabs"><button style={tabStyle} className="active">{t('خريطة التهديدات العالمية', 'Global Threat Map')}</button><button style={tabStyle}>{t('التدفق المباشر', 'Live Feed')}</button><button style={tabStyle}>{t('الهجمات السيبرانية', 'Cyber Attacks')}</button><button style={tabStyle}>{t('مستوى المخاطر', 'Risk Level')}</button></div>
              <div className="nx-map-host"><div className="nx-command nx-command-globe"><div className="nx-world-stage" /></div></div>
            </div>
            <aside className="nx-right-rail">
              <section className="nx-time-card"><small>{t('توقيت مركز العمليات', 'OPERATIONS CLOCK')}</small><strong>{cairo}</strong><span>Cairo · UTC+3</span><em>UTC {utc}</em></section>
              <section className="nx-compass-card"><div className="nx-compass"><i /><b>NX</b><span className="n">N</span><span className="e">E</span><span className="s">S</span><span className="w">W</span></div><strong>{t('أنت في الاتجاه الصحيح', "YOU'RE ON THE RIGHT PATH")}</strong></section>
              <section className="nx-health-card"><h3>{t('الوضع العام للنظام', 'System Status')}</h3><div className="nx-good"><ShieldCheck />{t('آمن ومستقر', 'Secure & Stable')}</div><dl><div><dt>{t('المعالجة', 'Processing')}</dt><dd>2.4M/s</dd></div><div><dt>{t('زمن الاستجابة', 'Response')}</dt><dd>12 ms</dd></div><div><dt>{t('سلامة البنية', 'Infrastructure')}</dt><dd>100%</dd></div></dl></section>
            </aside>
          </section>

          <section className="nx-data-row">
            <article className="nx-data-panel nx-feed"><header><b>{t('أحدث التهديدات', 'Latest Threats')}</b><span>{t('عرض الكل', 'View all')}</span></header>{threats.map(([time, a, e, cc, level]) => <div className="nx-feed-row" key={time}><time>{time}</time><span><i className={level} />{t(a, e)}</span><b>{cc}</b></div>)}</article>
            <article className="nx-data-panel"><header><b>{t('توزيع التهديدات حسب المنطقة', 'Threats by Region')}</b></header><div className="nx-donut"><i /><div><span>38% {t('آسيا', 'Asia')}</span><span>28% {t('أوروبا', 'Europe')}</span><span>18% {t('أمريكا الشمالية', 'N. America')}</span><span>10% {t('أفريقيا', 'Africa')}</span></div></div></article>
            <article className="nx-data-panel"><header><b>{t('اتجاهات التهديدات · 24 ساعة', 'Threat Trends · 24h')}</b></header><div className="nx-line-chart"><svg viewBox="0 0 320 110" preserveAspectRatio="none"><polyline points="0,82 25,72 45,78 68,48 92,63 115,42 138,70 164,55 190,62 215,32 240,44 266,29 292,48 320,39" /><polyline className="secondary" points="0,92 30,88 60,84 90,90 120,73 150,79 180,70 210,76 240,64 270,72 300,60 320,66" /></svg></div></article>
            <article className="nx-data-panel nx-services"><header><b>{t('الخدمات الأمنية', 'Security Services')}</b></header>{['Firewall','Intrusion Detection','Malware Defense','Behavior Analytics','Data Center','Backup'].map((s) => <span key={s}><i />{s}<b>{t('يعمل', 'Online')}</b></span>)}</article>
          </section>

          <section className="nx-quick-row">
            <Link href="/safescan"><ScanSearch /><span><b>{t('فحص شامل', 'Comprehensive Scan')}</b><small>{t('بدء فحص أمني الآن', 'Start security scan')}</small></span></Link>
            <Link href="/apps"><FileSearch /><span><b>{t('تحليل ملف', 'Analyze File')}</b><small>{t('فحص ملف مشتبه به', 'Inspect suspicious file')}</small></span></Link>
            <Link href="/services"><Activity /><span><b>{t('مراقبة مباشرة', 'Live Monitoring')}</b><small>{t('عرض النشاط الحالي', 'View current activity')}</small></span></Link>
            <Link href="/our-work"><Database /><span><b>{t('إدارة الأصول', 'Asset Management')}</b><small>{t('عرض الأصول الرقمية', 'View digital assets')}</small></span></Link>
            <Link href="/contact"><Mail /><span><b>{t('إنشاء تقرير', 'Create Report')}</b><small>{t('طلب تقرير مخصص', 'Request tailored report')}</small></span></Link>
          </section>
        </main>
      </div>

      <footer className="nx-exec-footer"><b>NEXVARY</b><span>Confidential · Security Beyond The Visible.</span><span>© NEXVARY</span></footer>
    </div>
  );
}
