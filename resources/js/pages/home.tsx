import { Head } from '@inertiajs/react';
import SiteShell from '../components/site-shell';
export default function Home() {
  const ar = document.documentElement.lang.startsWith('ar');
  return <SiteShell><Head title="Nexvary Inc"/><main id="main-content" className="nc-section"><img src="/assets/corporate/seal-nav.webp" width="96" height="96" alt=""/><h1>NEXVARY INC</h1><p>{ar ? 'نحو عالم رقمي أكثر أمانًا وذكاءً.' : 'Engineering a safer, smarter digital world.'}</p><a href={ar ? '/about?lang=ar' : '/about'}>{ar ? 'عن نيكسفاري' : 'About Nexvary'}</a></main></SiteShell>;
}
