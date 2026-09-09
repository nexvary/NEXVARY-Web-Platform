function installCommandWidgets(): void {
  const globe = document.querySelector<HTMLElement>('.nx-command-globe');
  if (!globe || globe.querySelector('.nx-v4-side-stack')) return;

  const stack = document.createElement('div');
  stack.className = 'nx-v4-side-stack';
  stack.innerHTML = `
    <section class="nx-v4-card nx-v4-countdown" aria-label="Threat intelligence refresh clock">
      <small>THREATS DON’T WAIT</small>
      <strong data-nx-countdown>00 : 12 : 47 : 56</strong>
      <em>NEXT INTELLIGENCE REFRESH · SIMULATED</em>
    </section>
    <section class="nx-v4-card nx-v4-compass" aria-label="NEXVARY direction compass">
      <div class="nx-v4-compass-wheel" aria-hidden="true"><div class="nx-v4-compass-core">NX</div></div>
      <div class="nx-v4-compass-copy"><b>YOU’RE ON THE RIGHT PATH</b><span>Trust · Expertise · Real Protection</span></div>
    </section>`;
  globe.appendChild(stack);

  const trust = document.createElement('div');
  trust.className = 'nx-v4-trust-strip';
  trust.innerHTML = '<span>24/7 MONITORING</span><span>GLOBAL SECURITY</span><span>AI-ASSISTED ANALYSIS</span>';
  globe.appendChild(trust);

  const timer = stack.querySelector<HTMLElement>('[data-nx-countdown]');
  if (!timer) return;
  let remaining = 12 * 3600 + 47 * 60 + 56;
  const render = () => {
    remaining = remaining <= 0 ? 12 * 3600 + 47 * 60 + 56 : remaining - 1;
    const hours = Math.floor(remaining / 3600);
    const minutes = Math.floor((remaining % 3600) / 60);
    const seconds = remaining % 60;
    timer.textContent = `00 : ${String(hours).padStart(2,'0')} : ${String(minutes).padStart(2,'0')} : ${String(seconds).padStart(2,'0')}`;
  };
  render();
  const id = window.setInterval(render, 1000);
  window.addEventListener('beforeunload', () => window.clearInterval(id), { once: true });
}

function energizeRadar(): void {
  const radar = document.querySelector<HTMLElement>('.nx-radar-large');
  if (!radar) return;
  radar.style.setProperty('--nx-radar-green', '#35f58b');
}

installCommandWidgets();
energizeRadar();
