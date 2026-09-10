import '../css/command-center-v5.css';
import './home-command-v5';
import '../css/command-center-v6.css';
import './home-command-v6';

function installCommandWidgets(): void {
  const command = document.querySelector<HTMLElement>('.nx-command-globe');
  if (!command || command.querySelector('.nx-v4-side-stack')) return;

  const stack = document.createElement('div');
  stack.className = 'nx-v4-side-stack nx-command-hud-stack';
  stack.innerHTML = `
    <section class="nx-v4-card nx-v4-countdown nx-command-clock" aria-label="NEXVARY operations clock">
      <small>SECURITY OPERATIONS CLOCK</small>
      <strong data-nx-clock>--:--:--</strong>
      <div class="nx-clock-meta"><span>CAIRO · UTC+3</span><span data-nx-utc>UTC --:--:--</span></div>
      <div class="nx-refresh-meter"><i data-nx-refresh-bar></i></div>
      <em data-nx-refresh>THREAT FEED REFRESH · 15s</em>
    </section>
    <section class="nx-v4-card nx-v4-compass nx-command-compass" aria-label="NEXVARY direction compass">
      <div class="nx-v4-compass-wheel" aria-hidden="true">
        <span class="nx-compass-n">N</span><span class="nx-compass-e">E</span><span class="nx-compass-s">S</span><span class="nx-compass-w">W</span>
        <div class="nx-compass-needle"></div>
        <div class="nx-v4-compass-core"><img src="/nexvary-mark.svg" alt="" aria-hidden="true"></div>
      </div>
      <div class="nx-v4-compass-copy"><b>YOU’RE ON THE RIGHT PATH</b><span>Trust · Expertise · Real Protection</span></div>
    </section>`;
  command.appendChild(stack);

  const trust = document.createElement('div');
  trust.className = 'nx-v4-trust-strip nx-command-trust-strip';
  trust.innerHTML = '<span>24/7 MONITORING</span><span>GLOBAL SECURITY</span><span>AI-ASSISTED ANALYSIS</span>';
  command.appendChild(trust);

  const clock = stack.querySelector<HTMLElement>('[data-nx-clock]');
  const utc = stack.querySelector<HTMLElement>('[data-nx-utc]');
  const refresh = stack.querySelector<HTMLElement>('[data-nx-refresh]');
  const bar = stack.querySelector<HTMLElement>('[data-nx-refresh-bar]');
  let refreshRemaining = 15;

  const render = () => {
    const now = new Date();
    if (clock) {
      clock.textContent = new Intl.DateTimeFormat('en-GB', {
        timeZone: 'Africa/Cairo', hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false,
      }).format(now);
    }
    if (utc) {
      utc.textContent = `UTC ${new Intl.DateTimeFormat('en-GB', {
        timeZone: 'UTC', hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false,
      }).format(now)}`;
    }
    refreshRemaining = refreshRemaining <= 1 ? 15 : refreshRemaining - 1;
    if (refresh) refresh.textContent = `THREAT FEED REFRESH · ${refreshRemaining}s`;
    if (bar) bar.style.width = `${((15 - refreshRemaining) / 15) * 100}%`;
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
