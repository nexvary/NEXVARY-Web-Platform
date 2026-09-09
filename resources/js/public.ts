const languageSwitches = document.querySelectorAll<HTMLSelectElement>('[data-language-switch]');

for (const select of languageSwitches) {
  select.addEventListener('change', () => {
    const url = new URL(window.location.href);
    url.searchParams.set('lang', select.value);
    window.location.assign(url.toString());
  });
}

for (const button of document.querySelectorAll<HTMLButtonElement>('[data-back-button]')) {
  button.addEventListener('click', () => {
    try {
      const referrer = document.referrer ? new URL(document.referrer) : null;
      if (window.history.length > 1 && referrer?.origin === window.location.origin) {
        window.history.back();
        return;
      }
    } catch {
      // Use the safe home fallback below.
    }
    window.location.assign('/');
  });
}

function upgradeThreatGlobe(): void {
  const svg = document.querySelector<SVGSVGElement>('.nx-world-svg');
  if (!svg) return;

  svg.setAttribute('data-visual-version', '2');
  svg.innerHTML = `
    <defs>
      <radialGradient id="nxOceanV2" cx="34%" cy="28%" r="78%">
        <stop offset="0" stop-color="#1f7db8" stop-opacity=".72"/>
        <stop offset=".34" stop-color="#0a3158"/>
        <stop offset=".74" stop-color="#031322"/>
        <stop offset="1" stop-color="#01050c"/>
      </radialGradient>
      <linearGradient id="nxLandV2" x1="0" x2="1" y1="0" y2="1">
        <stop offset="0" stop-color="#8be7ff" stop-opacity=".92"/>
        <stop offset=".4" stop-color="#1c9bdd" stop-opacity=".68"/>
        <stop offset="1" stop-color="#0b426c" stop-opacity=".42"/>
      </linearGradient>
      <radialGradient id="nxAtmoV2"><stop offset="70%" stop-color="#4cc9ff" stop-opacity="0"/><stop offset="94%" stop-color="#4cc9ff" stop-opacity=".16"/><stop offset="100%" stop-color="#b3f3ff" stop-opacity=".55"/></radialGradient>
      <filter id="nxGlowV2" x="-100%" y="-100%" width="300%" height="300%"><feGaussianBlur stdDeviation="4" result="b"/><feMerge><feMergeNode in="b"/><feMergeNode in="SourceGraphic"/></feMerge></filter>
      <filter id="nxSoftV2" x="-40%" y="-40%" width="180%" height="180%"><feGaussianBlur stdDeviation="12"/></filter>
      <clipPath id="nxSphereV2"><circle cx="300" cy="300" r="228"/></clipPath>
    </defs>
    <circle cx="300" cy="300" r="239" fill="#148fe0" fill-opacity=".10" filter="url(#nxSoftV2)"/>
    <circle cx="300" cy="300" r="228" fill="url(#nxOceanV2)" stroke="#75dbff" stroke-opacity=".84" stroke-width="1.2"/>
    <g clip-path="url(#nxSphereV2)" class="nx-world-grid">
      <ellipse cx="300" cy="300" rx="228" ry="54"/><ellipse cx="300" cy="300" rx="228" ry="111"/><ellipse cx="300" cy="300" rx="228" ry="170"/>
      <ellipse cx="300" cy="300" rx="60" ry="228"/><ellipse cx="300" cy="300" rx="123" ry="228"/><ellipse cx="300" cy="300" rx="184" ry="228"/>
      <path d="M72 300H528M300 72V528"/>
    </g>
    <g clip-path="url(#nxSphereV2)" fill="url(#nxLandV2)" stroke="#b5efff" stroke-opacity=".46" stroke-width=".8" stroke-linejoin="round">
      <path d="M92 208l18-35 26-22 32-26 47-10 37 12 27 19 16 25-10 20-18 5-13 19-27 4-7 18-26 8-13 23-21-1-7-16-15-7-14-19-18-2z"/>
      <path d="M207 290l21 8 20 22 16 32-2 39-11 37-18 40-18-4-12-26-8-39 3-29-9-31 5-24z"/>
      <path d="M286 173l29-20 40-13 38 2 26-10 43 17 31 27 14 24-7 18-24 3-12 21-31-2-20 16-27-4-15 12-29-7-13-18-25-8-22-22-3-18z"/>
      <path d="M329 276l23-14 31 5 22 22 18 20 12 42-11 39-19 38-29 17-20-10-14-27-6-34-14-22 5-36z"/>
      <path d="M419 263l26-19 29 2 22 15 18-5 28 18 18 25-9 18-23-2-18 11-22-5-18-16-28-3-18-17z"/>
      <path d="M477 407l28-10 26 6 22 18 7 19-19 18-33 3-27-13-12-21z"/>
      <path d="M454 205l9-15 15-5 11 8-4 13-14 6z"/><path d="M132 283l14-6 8 8-4 13-14 2z"/>
    </g>
    <g clip-path="url(#nxSphereV2)" class="nx-world-routes" fill="none" stroke-linecap="round">
      <path d="M145 216 Q291 78 439 222"/><path d="M205 350 Q322 176 486 321"/><path d="M345 221 Q253 243 183 337"/><path d="M383 286 Q436 214 507 240"/><path d="M243 313 Q360 246 474 277"/><path d="M169 245 Q275 185 375 236"/>
    </g>
    <g class="nx-world-nodes" filter="url(#nxGlowV2)">
      <circle cx="145" cy="216" r="4.5"/><circle cx="205" cy="350" r="4"/><circle cx="345" cy="221" r="4.5"/><circle cx="383" cy="286" r="4"/><circle cx="439" cy="222" r="4.5"/><circle cx="486" cy="321" r="4"/><circle class="nx-risk-node" cx="507" cy="240" r="5"/><circle class="nx-risk-node" cx="183" cy="337" r="4.5"/><circle cx="474" cy="277" r="3.5"/><circle cx="243" cy="313" r="3.5"/>
    </g>
    <circle cx="300" cy="300" r="228" fill="url(#nxAtmoV2)" pointer-events="none"/>
    <path d="M110 395 Q300 520 492 396" fill="none" stroke="#36b7ff" stroke-opacity=".13" stroke-width="10" filter="url(#nxSoftV2)"/>
  `;

  const style = document.createElement('style');
  style.textContent = `
    .nx-world-stage{perspective:900px}
    .nx-world-svg[data-visual-version="2"]{transform:rotateX(3deg) rotateY(-7deg);filter:drop-shadow(0 0 30px rgba(38,166,255,.32)) drop-shadow(0 26px 32px rgba(0,0,0,.45));animation:nxGlobeFloat 8s ease-in-out infinite}
    .nx-world-svg[data-visual-version="2"] .nx-world-grid ellipse,.nx-world-svg[data-visual-version="2"] .nx-world-grid path{stroke:#58bfff;stroke-opacity:.18;stroke-width:.8}
    .nx-world-svg[data-visual-version="2"] .nx-world-routes path{stroke:#52c8ff;stroke-opacity:.58;stroke-width:1.35;stroke-dasharray:3 7;animation:nxRouteV2 4.5s linear infinite}
    .nx-world-svg[data-visual-version="2"] .nx-world-routes path:nth-child(2n){stroke:#9d7dff;animation-duration:6.2s}.nx-world-svg[data-visual-version="2"] .nx-world-routes path:nth-child(3n){stroke:#f5c86d}
    .nx-world-svg[data-visual-version="2"] .nx-world-nodes circle{fill:#71e7ff}.nx-world-svg[data-visual-version="2"] .nx-world-nodes .nx-risk-node{fill:#ff4d62}
    .nx-command-globe{background:radial-gradient(circle at 56% 43%,rgba(20,118,189,.27),transparent 30%),radial-gradient(circle at 60% 74%,rgba(61,197,255,.06),transparent 25%),linear-gradient(180deg,rgba(2,11,22,.99),rgba(1,6,12,.99))}
    .nx-radar-large{box-shadow:inset 0 0 55px rgba(33,165,255,.08),0 0 45px rgba(16,100,174,.09)}
    .nx-radar-large::before{box-shadow:0 0 18px rgba(47,156,255,.08)}
    .nx-radar-large .nx-sweep{filter:drop-shadow(0 0 9px rgba(78,197,255,.48));animation-duration:4.2s!important}
    .nx-spectrum-chart svg{filter:drop-shadow(0 0 7px rgba(54,169,255,.24))}
    .nx-spectrum-chart::after{content:'FFT · WATERFALL · PEAK HOLD';position:absolute;right:14px;top:11px;color:#4f7896;font-size:.48rem;letter-spacing:.12em}
    @keyframes nxGlobeFloat{0%,100%{transform:rotateX(3deg) rotateY(-7deg) translateY(0)}50%{transform:rotateX(2deg) rotateY(-3deg) translateY(-5px)}}
    @keyframes nxRouteV2{to{stroke-dashoffset:-55}}
    @media (prefers-reduced-motion:reduce){.nx-world-svg[data-visual-version="2"],.nx-world-svg[data-visual-version="2"] .nx-world-routes path{animation:none!important}}
  `;
  document.head.appendChild(style);
}

function upgradeRfPanel(): void {
  const radar = document.querySelector<HTMLElement>('.nx-radar-large');
  if (radar && !radar.querySelector('.nx-rf-rings-v2')) {
    const rings = document.createElement('div');
    rings.className = 'nx-rf-rings-v2';
    rings.setAttribute('aria-hidden', 'true');
    rings.style.cssText = 'position:absolute;inset:7%;border:1px solid rgba(74,184,255,.14);border-radius:50%;box-shadow:0 0 0 28px rgba(32,140,220,.025),0 0 0 56px rgba(32,140,220,.018);pointer-events:none';
    radar.appendChild(rings);
  }

  const chart = document.querySelector<HTMLElement>('.nx-spectrum-chart');
  if (chart && !chart.querySelector('.nx-peak-hold-v2')) {
    chart.style.position = 'relative';
    const peak = document.createElement('div');
    peak.className = 'nx-peak-hold-v2';
    peak.setAttribute('aria-hidden', 'true');
    peak.style.cssText = 'position:absolute;left:43.5%;top:18%;bottom:17%;width:1px;background:linear-gradient(#ffcf67,rgba(255,207,103,.15));box-shadow:0 0 9px rgba(255,207,103,.55);pointer-events:none';
    chart.appendChild(peak);
  }
}

upgradeThreatGlobe();
upgradeRfPanel();
