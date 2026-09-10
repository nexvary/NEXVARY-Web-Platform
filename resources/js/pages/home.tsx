import { Head, Link } from '@inertiajs/react';
import { Activity, Database, FileSearch, Gauge, Home as HomeIcon, Radar, ScanSearch, Settings, ShieldCheck, Users } from 'lucide-react';
import SiteShell from '../components/site-shell';
import '../css/site-command-v9.css';

const nav = [
  { labelAr: 'الرئيسية', labelEn: 'Home', href: '/', icon: HomeIcon, active: true },
  { labelAr: 'مركز العمليات', labelEn: 'Operations', href: '/services', icon: Radar },
  { labelAr: 'التهديدات', labelEn: 'Threats', href: '/safescan', icon: ShieldCheck },
  { labelAr: 'الرصد والتحليل', labelEn: 'Analytics', href: '/apps', icon: Activity },
  { labelAr: 'الأصول الرقمية', labelEn: 'Assets', href: '/our-work', icon: Database },
  { labelAr: 'التقارير', labelEn: 'Reports', href: '/contact', icon: FileSearch },
  { labelAr: 'الإعدادات', labelEn: 'Settings', href: '/about', icon: Settings },
];

export default function Home() {
  const ar = document.documentElement.lang.startsWith('ar');
  const t = (arText: string, enText: string) => (ar ? arText : enText);

  return (
    <SiteShell>
      <Head title={t('NEXVARY — مركز العمليات الأمنية العالمي', 'NEXVARY — Global Security Operations Center')}>
        <meta name="description" content="NEXVARY cybersecurity, counter-surveillance, digital forensics and privacy technology platform." />
        <link rel="canonical" href="https://nexvary.com/" />
      </Head>

      <main className="nx-dashboard-home" dir={ar ? 'rtl' : 'ltr'}>
        <div className="nx-command-shell">
          <aside className="nx-command-sidebar" aria-label={t('التنقل الرئيسي', 'Primary navigation')}>
            <div className="nx-command-brand"><strong>NEXVARY</strong><small>SECURITY BEYOND THE VISIBLE</small></div>
            <nav className="nx-command-nav">
              {nav.map(({ labelAr, labelEn, href, icon: Icon, active }) => (
                <Link key={href} href={href} className={active ? 'active' : ''}><Icon strokeWidth={1.7} />{t(labelAr, labelEn)}</Link>
              ))}
            </nav>
            <div className="nx-sidebar-foot">NEXVARY<br />GLOBAL INTELLIGENCE<br />SAFER TOGETHER</div>
          </aside>

          <section className="nx-command-main">
            <section className="nx-command-overview">
              <div className="nx-command-title">
                <small>{t('مرحبًا بك في', 'WELCOME TO')}</small>
                <h1>{t('مركز العمليات الأمنية العالمي', 'Global Security Operations Center')}</h1>
                <p>{t('مراقبة · تحليل · استجابة · حماية', 'Monitor · Analyze · Respond · Protect')}</p>
              </div>
              <div className="nx-stat"><strong>183</strong><span>{t('دولة مغطاة', 'Countries')}</span></div>
              <div className="nx-stat"><strong>2.4M</strong><span>{t('مصدر بيانات', 'Data sources')}</span></div>
              <div className="nx-stat"><strong>17,382</strong><span>{t('إشارة نشطة', 'Active signals')}</span></div>
              <div className="nx-stat"><strong>99.98%</strong><span>{t('جاهزية', 'Availability')}</span></div>
            </section>

            <section className="nx-map-module">
              <div className="nx-map-tabs" role="tablist" aria-label={t('طبقات العرض', 'Visualization layers')}>
                <button className="active" type="button">{t('خريطة التهديدات العالمية', 'Global Threat Map')}</button>
                <button type="button">{t('التدفق المباشر', 'Live Feed')}</button>
                <button type="button">{t('الهجمات السيبرانية', 'Cyber Attacks')}</button>
                <button type="button">{t('مستوى المخاطر', 'Risk Level')}</button>
              </div>
              <div className="nx-map-workspace">
                <div className="nx-command nx-command-globe" aria-label={t('خريطة نشاط التهديدات العالمية', 'Global threat activity map')}>
                  <div className="nx-world-stage" />
                </div>
              </div>
            </section>

            <section className="nx-command-grid">
              <article className="nx-panel">
                <div className="nx-panel-head"><strong>{t('أحدث التهديدات', 'Latest Threats')}</strong><span>{t('عرض الكل', 'View all')}</span></div>
                <div className="nx-threat-list">
                  <div className="nx-threat-row"><b>17:24:12</b><span><i className="nx-severity-dot critical" />{t('محاولة وصول غير مصرح', 'Unauthorized access attempt')}</span><em>RU</em></div>
                  <div className="nx-threat-row"><b>17:23:54</b><span><i className="nx-severity-dot warn" />{t('نشاط فحص للمنافذ', 'Port scanning activity')}</span><em>CN</em></div>
                  <div className="nx-threat-row"><b>17:23:28</b><span><i className="nx-severity-dot warn" />{t('بصمة خبيثة محتملة', 'Possible malicious fingerprint')}</span><em>IR</em></div>
                  <div className="nx-threat-row"><b>17:22:41</b><span><i className="nx-severity-dot ok" />{t('نشاط عابر على API', 'Transient API activity')}</span><em>US</em></div>
                </div>
              </article>

              <article className="nx-panel">
                <div className="nx-panel-head"><strong>{t('اتجاهات التهديدات', 'Threat Trends')}</strong><span>{t('آخر 24 ساعة', 'Last 24h')}</span></div>
                <div className="nx-mini-chart" aria-label={t('مخطط اتجاهات تجريبي', 'Demo trend chart')}>
                  {[28,42,36,55,68,52,77,61,84,58,71,49,64,82,73,91,66,78,88,70].map((h, i) => <i key={i} style={{ height: `${h}%` }} />)}
                </div>
              </article>

              <article className="nx-panel">
                <div className="nx-panel-head"><strong>{t('حالة الخدمات الأمنية', 'Security Services')}</strong><span>{t('تشغيلي', 'Operational')}</span></div>
                <div className="nx-service-list">
                  <span>{t('جدار الحماية', 'Firewall')}<b>{t('يعمل', 'Online')}</b></span>
                  <span>{t('كشف التسلل', 'Intrusion Detection')}<b>{t('يعمل', 'Online')}</b></span>
                  <span>{t('تحليل السلوك', 'Behavior Analytics')}<b>{t('يعمل', 'Online')}</b></span>
                  <span>{t('مركز البيانات', 'Data Center')}<b>{t('يعمل', 'Online')}</b></span>
                  <span>{t('النسخ الاحتياطي', 'Backup')}<b>{t('يعمل', 'Online')}</b></span>
                </div>
              </article>
            </section>

            <section className="nx-action-strip">
              <Link href="/safescan" className="nx-action-tile"><span className="nx-action-icon"><ScanSearch /></span><span><strong>{t('فحص شامل', 'Comprehensive Scan')}</strong><small>{t('بدء فحص أمني الآن', 'Start a security scan')}</small></span></Link>
              <Link href="/apps" className="nx-action-tile"><span className="nx-action-icon"><FileSearch /></span><span><strong>{t('تحليل ملف', 'Analyze File')}</strong><small>{t('فحص ملف مشتبه به', 'Inspect suspicious file')}</small></span></Link>
              <Link href="/services" className="nx-action-tile"><span className="nx-action-icon"><Activity /></span><span><strong>{t('مراقبة مباشرة', 'Live Monitoring')}</strong><small>{t('عرض النشاط الحالي', 'View current activity')}</small></span></Link>
              <Link href="/our-work" className="nx-action-tile"><span className="nx-action-icon"><Database /></span><span><strong>{t('إدارة الأصول', 'Asset Management')}</strong><small>{t('عرض الأصول الرقمية', 'View digital assets')}</small></span></Link>
              <Link href="/contact" className="nx-action-tile"><span className="nx-action-icon"><FileSearch /></span><span><strong>{t('إنشاء تقرير', 'Create Report')}</strong><small>{t('طلب تقرير مخصص', 'Request a tailored report')}</small></span></Link>
            </section>
          </section>

          <aside className="nx-command-rail">
            <section className="nx-rail-card nx-clock-large">
              <small>{t('توقيت مركز العمليات', 'OPERATIONS CLOCK')}</small>
              <strong>17:24:36</strong>
              <span>Cairo · UTC+3</span>
            </section>
            <section className="nx-rail-card nx-compass-large">
              <div className="nx-compass-face" aria-hidden="true"><i /><b>NX</b></div>
              <div className="nx-compass-label">{t('أنت في الاتجاه الصحيح', "YOU'RE ON THE RIGHT PATH")}</div>
            </section>
            <section className="nx-rail-card">
              <div className="nx-panel-head"><strong>{t('الوضع العام للنظام', 'System Status')}</strong><span><Gauge size={16} /></span></div>
              <div className="nx-system-good">{t('آمن ومستقر', 'Secure & Stable')}</div>
              <div className="nx-rail-metric"><span>{t('معدل المعالجة', 'Processing')}</span><strong>2.4M/s</strong></div>
              <div className="nx-rail-metric"><span>{t('زمن الاستجابة', 'Response')}</span><strong>12 ms</strong></div>
              <div className="nx-rail-metric"><span>{t('سلامة البنية', 'Infrastructure')}</span><strong>100%</strong></div>
              <div className="nx-rail-metric"><span>{t('جلسات نشطة', 'Active sessions')}</span><strong><Users size={14} /> 243</strong></div>
            </section>
          </aside>
        </div>
      </main>
    </SiteShell>
  );
}
