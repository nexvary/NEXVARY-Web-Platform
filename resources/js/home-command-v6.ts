function installV6Scene(): void {
  const stage = document.querySelector<HTMLElement>('.nx-world-stage');
  const radar = document.querySelector<HTMLElement>('.nx-radar-large');
  if (stage && !stage.querySelector('.nx-v5-energy-ring')) {
    const fallbackEarth = document.createElement('div');
    fallbackEarth.className = 'nx-v6-earth-fallback';
    fallbackEarth.setAttribute('aria-hidden', 'true');
    fallbackEarth.innerHTML = `
      <div class="nx-v6-earth-grid"></div>
      <svg class="nx-v6-earth-land" viewBox="0 0 520 520" aria-hidden="true">
        <g transform="translate(260 260)">
          <path d="M-194-99l28-35 50-21 54 7 25 28-17 22-35 7-18 31-42-7-23-17z"/>
          <path d="M-105 2l31 8 23 31-8 43-24 55-24-25-13-49 6-39z"/>
          <path d="M-20-115l33-23 49-4 37 19 48 4 46 31-11 25-39 5-31 31-38-5-29-31-43-13z"/>
          <path d="M-10-30l42 4 34 29 6 48-27 59-34-13-21-45-16-44z"/>
          <path d="M132 70l36 5 28 31-12 31-45 5-28-29z"/>
        </g>
      </svg>
      <span class="nx-v6-node n1"></span><span class="nx-v6-node n2"></span><span class="nx-v6-node n3"></span><span class="nx-v6-node n4"></span>
    `;
    stage.prepend(fallbackEarth);

    const ring = document.createElement('div');
    ring.className = 'nx-v5-energy-ring';
    ring.setAttribute('aria-hidden', 'true');
    stage.appendChild(ring);

    const pedestal = document.createElement('div');
    pedestal.className = 'nx-v5-pedestal';
    pedestal.textContent = 'NEXVARY';
    pedestal.setAttribute('aria-hidden', 'true');
    stage.appendChild(pedestal);
  }

  if (radar) {
    const logo = radar.querySelector<HTMLElement>('.nx-radar-logo');
    if (logo) logo.textContent = 'NX';
    radar.dataset.visualVersion = '6';
  }

  const compass = document.querySelector<HTMLElement>('.nx-v4-compass-core');
  if (compass) compass.textContent = 'NX';

  document.querySelectorAll<HTMLElement>('.nx-command-card,.nx-people-grid article,.nx-summary-grid article').forEach((card, i) => {
    card.dataset.frameTone = i % 2 === 0 ? 'silver' : 'gold';
  });
}

installV6Scene();
