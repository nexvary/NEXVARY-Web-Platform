function installV6Scene(): void {
  const stage = document.querySelector<HTMLElement>('.nx-world-stage');
  const radar = document.querySelector<HTMLElement>('.nx-radar-large');
  if (stage && !stage.querySelector('.nx-v5-energy-ring')) {
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
