type ThreatEvent = {
  id: string;
  type: 'attack' | 'scan' | 'infrastructure' | string;
  label: string;
  city: string;
  lat: number;
  lon: number;
  severity: 'high' | 'medium' | 'low' | string;
};

type ThreatFeed = {
  mode: 'live' | 'simulated';
  updated_at: string;
  events: ThreatEvent[];
};

function projectMap(lon: number, lat: number): { x: number; y: number } {
  return {
    x: ((lon + 180) / 360) * 100,
    y: ((90 - lat) / 180) * 100,
  };
}

function mapMarkup(): string {
  return `
    <div class="nx-threat-map-shell" data-threat-map-shell>
      <div class="nx-threat-map-head">
        <div><strong>GLOBAL THREAT ACTIVITY</strong><span>Internet infrastructure · attack telemetry · reconnaissance</span></div>
        <span class="nx-threat-feed-state" data-threat-feed-state><i></i> CONNECTING</span>
      </div>
      <div class="nx-threat-map-canvas" data-threat-map-canvas>
        <svg viewBox="0 0 1200 600" preserveAspectRatio="none" aria-hidden="true">
          <g class="nx-threat-map-grid"><path d="M0 100H1200M0 200H1200M0 300H1200M0 400H1200M0 500H1200M200 0V600M400 0V600M600 0V600M800 0V600M1000 0V600"/></g>
          <g class="nx-threat-map-land">
            <path d="M75 128l55-42 91-22 101 13 55 42-22 36-67 17-34 42-53 8-56 36-50-29-28-52z"/>
            <path d="M282 301l44 14 42 47-14 65-34 101-41-44-18-93 8-66z"/>
            <path d="M505 120l65-24 88 12 46 27 58 5 43 30-8 33-53 12-44 43-58-9-54-42-72-19z"/>
            <path d="M563 257l73 8 55 48 15 76-41 95-54-22-31-72-28-79z"/>
            <path d="M761 187l86-22 95 31 92 40 71 61-23 32-92-18-64 4-61-40-83-34z"/>
            <path d="M981 386l74 12 55 47-22 54-87 3-51-41z"/>
          </g>
          <g class="nx-threat-map-route" data-threat-route-layer></g>
        </svg>
        <div class="nx-threat-map-events" data-threat-events></div>
        <div class="nx-threat-map-legend"><span><i class="attack"></i>Attack</span><span><i class="scan"></i>Recon</span><span><i class="infrastructure"></i>New infrastructure</span></div>
      </div>
      <div class="nx-threat-map-footer">
        <div><span>ACTIVE EVENTS</span><strong data-active-events>0</strong></div>
        <div><span>HIGH PRIORITY</span><strong data-high-events>0</strong></div>
        <div><span>NEW INFRASTRUCTURE</span><strong data-infra-events>0</strong></div>
        <div><span>LAST REFRESH</span><strong data-last-refresh>--:--:--</strong></div>
      </div>
    </div>`;
}

function renderThreatFeed(feed: ThreatFeed): void {
  const canvas = document.querySelector<HTMLElement>('[data-threat-map-canvas]');
  const eventsLayer = document.querySelector<HTMLElement>('[data-threat-events]');
  const routeLayer = document.querySelector<SVGGElement>('[data-threat-route-layer]');
  const state = document.querySelector<HTMLElement>('[data-threat-feed-state]');
  if (!canvas || !eventsLayer || !routeLayer || !state) return;

  eventsLayer.innerHTML = '';
  routeLayer.innerHTML = '';

  const safeEvents = Array.isArray(feed.events) ? feed.events.slice(0, 40) : [];
  safeEvents.forEach((event, index) => {
    if (!Number.isFinite(event.lon) || !Number.isFinite(event.lat)) return;
    const point = projectMap(event.lon, event.lat);
    const marker = document.createElement('button');
    marker.type = 'button';
    marker.className = `nx-threat-event nx-event-${event.type} nx-severity-${event.severity}`;
    marker.style.left = `${point.x}%`;
    marker.style.top = `${point.y}%`;
    marker.setAttribute('aria-label', `${event.label}, ${event.city}`);
    marker.innerHTML = `<span class="nx-threat-pulse"></span><span class="nx-threat-dot"></span><span class="nx-threat-tooltip"><b>${event.label}</b><small>${event.city}</small></span>`;
    eventsLayer.appendChild(marker);

    if (index > 0 && index % 2 === 1) {
      const previous = safeEvents[index - 1];
      if (Number.isFinite(previous.lon) && Number.isFinite(previous.lat)) {
        const a = projectMap(previous.lon, previous.lat);
        const b = point;
        const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
        const x1 = a.x * 12, y1 = a.y * 6, x2 = b.x * 12, y2 = b.y * 6;
        const cx = (x1 + x2) / 2, cy = Math.min(y1, y2) - 55;
        path.setAttribute('d', `M${x1.toFixed(1)} ${y1.toFixed(1)} Q${cx.toFixed(1)} ${cy.toFixed(1)} ${x2.toFixed(1)} ${y2.toFixed(1)}`);
        path.setAttribute('class', `nx-threat-route nx-route-${event.type}`);
        routeLayer.appendChild(path);
      }
    }
  });

  const high = safeEvents.filter((event) => event.severity === 'high').length;
  const infra = safeEvents.filter((event) => event.type === 'infrastructure').length;
  document.querySelector<HTMLElement>('[data-active-events]')!.textContent = String(safeEvents.length);
  document.querySelector<HTMLElement>('[data-high-events]')!.textContent = String(high);
  document.querySelector<HTMLElement>('[data-infra-events]')!.textContent = String(infra);
  document.querySelector<HTMLElement>('[data-last-refresh]')!.textContent = new Date(feed.updated_at).toLocaleTimeString([], { hour12: false });
  state.innerHTML = feed.mode === 'live' ? '<i></i> LIVE API' : '<i></i> SIMULATED FEED';
  state.dataset.mode = feed.mode;
}

async function refreshThreatFeed(): Promise<void> {
  try {
    const response = await fetch('/api/threat-feed', { headers: { Accept: 'application/json' }, cache: 'no-store' });
    if (!response.ok) throw new Error(`Threat feed ${response.status}`);
    renderThreatFeed(await response.json() as ThreatFeed);
  } catch {
    const state = document.querySelector<HTMLElement>('[data-threat-feed-state]');
    if (state) state.innerHTML = '<i></i> FEED OFFLINE';
  }
}

function installV6Scene(): void {
  const command = document.querySelector<HTMLElement>('.nx-command-globe');
  const stage = document.querySelector<HTMLElement>('.nx-world-stage');
  const threatCard = document.querySelector<HTMLElement>('.nx-threat-card');
  const caption = document.querySelector<HTMLElement>('.nx-globe-caption');
  const radar = document.querySelector<HTMLElement>('.nx-radar-large');

  if (command && stage) {
    command.classList.add('nx-command-threat-map');
    const topline = command.querySelector<HTMLElement>('.nx-command-topline');
    if (topline) topline.style.display = 'none';
    stage.className = 'nx-threat-map-stage';
    stage.innerHTML = mapMarkup();
    threatCard?.remove();
    caption?.remove();
  }

  if (radar) {
    const logo = radar.querySelector<HTMLElement>('.nx-radar-logo');
    if (logo) logo.textContent = 'NX';
    radar.dataset.visualVersion = '7-map';
  }

  const compass = document.querySelector<HTMLElement>('.nx-v4-compass-core');
  if (compass) compass.textContent = 'NX';

  document.querySelectorAll<HTMLElement>('.nx-command-card,.nx-people-grid article,.nx-summary-grid article').forEach((card, i) => {
    card.dataset.frameTone = i % 2 === 0 ? 'silver' : 'gold';
  });

  void refreshThreatFeed();
  const interval = window.setInterval(() => void refreshThreatFeed(), 15000);
  window.addEventListener('beforeunload', () => window.clearInterval(interval), { once: true });
}

installV6Scene();
