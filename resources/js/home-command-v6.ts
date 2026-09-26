import '../css/threat-map-v7.css';

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
    x: Math.max(0, Math.min(100, ((lon + 180) / 360) * 100)),
    y: Math.max(0, Math.min(100, ((90 - lat) / 180) * 100)),
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
          <g class="nx-threat-map-land" data-country-layer></g>
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

async function renderCountryBoundaries(): Promise<void> {
  const layer = document.querySelector<SVGGElement>('[data-country-layer]');
  if (!layer) return;
  try {
    const response = await fetch('/nexvary-world-110m.geojson', { cache: 'force-cache', credentials: 'same-origin' });
    if (!response.ok) throw new Error('Map geography unavailable');
    const data: unknown = await response.json();
    if (!data || typeof data !== 'object' || !('features' in data) || !Array.isArray(data.features)) return;
    const point = (coordinate: unknown): string | null => {
      if (!Array.isArray(coordinate) || coordinate.length < 2 ||
          typeof coordinate[0] !== 'number' || typeof coordinate[1] !== 'number' ||
          !Number.isFinite(coordinate[0]) || !Number.isFinite(coordinate[1])) return null;
      const lon = Math.max(-180, Math.min(180, coordinate[0]));
      const lat = Math.max(-90, Math.min(90, coordinate[1]));
      return `${(((lon + 180) / 360) * 1200).toFixed(1)} ${(((90 - lat) / 180) * 600).toFixed(1)}`;
    };
    const ringPath = (ring: unknown): string => {
      if (!Array.isArray(ring) || ring.length < 3) return '';
      const points = ring.map(point).filter((p): p is string => p !== null);
      return points.length >= 3 ? `M${points.join('L')}Z` : '';
    };
    const fragment = document.createDocumentFragment();
    for (const feature of data.features.slice(0, 300)) {
      const geometry = feature?.geometry;
      if (!geometry || !Array.isArray(geometry.coordinates)) continue;
      const polygons = geometry.type === 'Polygon' ? [geometry.coordinates]
        : geometry.type === 'MultiPolygon' ? geometry.coordinates : [];
      const d = polygons.flatMap((polygon: unknown) =>
        Array.isArray(polygon) ? polygon.map(ringPath) : []).filter(Boolean).join('');
      if (!d) continue;
      const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
      path.setAttribute('d', d);
      path.setAttribute('fill-rule', 'evenodd');
      fragment.appendChild(path);
    }
    if (fragment.childNodes.length > 100) layer.replaceChildren(fragment);
  } catch {
    // Map interactions and telemetry remain available if geography cannot load.
    layer.dataset.unavailable = 'true';
  }
}

function renderThreatFeed(feed: ThreatFeed): void {
  const canvas = document.querySelector<HTMLElement>('[data-threat-map-canvas]');
  const eventsLayer = document.querySelector<HTMLElement>('[data-threat-events]');
  const routeLayer = document.querySelector<SVGGElement>('[data-threat-route-layer]');
  const state = document.querySelector<HTMLElement>('[data-threat-feed-state]');
  if (!canvas || !eventsLayer || !routeLayer || !state) return;

  eventsLayer.innerHTML = '';
  routeLayer.innerHTML = '';

  const safeEvents = Array.isArray(feed.events)
    ? feed.events.filter((event): event is ThreatEvent =>
      event !== null && typeof event === 'object' &&
      typeof event.lon === 'number' && Number.isFinite(event.lon) &&
      typeof event.lat === 'number' && Number.isFinite(event.lat) &&
      typeof event.label === 'string' && typeof event.city === 'string' &&
      event.lon >= -180 && event.lon <= 180 && event.lat >= -90 && event.lat <= 90,
    ).slice(0, 40)
    : [];
  safeEvents.forEach((event, index) => {
    const point = projectMap(event.lon, event.lat);
    const marker = document.createElement('button');
    marker.type = 'button';
    const type = ['attack', 'scan', 'infrastructure'].includes(event.type) ? event.type : 'scan';
    const severity = ['high', 'medium', 'low'].includes(event.severity) ? event.severity : 'low';
    marker.className = `nx-threat-event nx-event-${type} nx-severity-${severity}`;
    marker.dataset.eventType = type;
    marker.style.left = `${point.x}%`;
    marker.style.top = `${point.y}%`;
    marker.setAttribute('aria-label', `${event.label}, ${event.city}`);
    const pulse = document.createElement('span');
    pulse.className = 'nx-threat-pulse';
    const dot = document.createElement('span');
    dot.className = 'nx-threat-dot';
    const tooltip = document.createElement('span');
    tooltip.className = 'nx-threat-tooltip';
    const label = document.createElement('b');
    label.textContent = event.label;
    const city = document.createElement('small');
    city.textContent = event.city;
    tooltip.append(label, city);
    marker.append(pulse, dot, tooltip);
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
        path.setAttribute('class', `nx-threat-route nx-route-${type}`);
        routeLayer.appendChild(path);
      }
    }
  });

  const high = safeEvents.filter((event) => event.severity === 'high').length;
  const infra = safeEvents.filter((event) => event.type === 'infrastructure').length;
  const activeNode = document.querySelector<HTMLElement>('[data-active-events]');
  const highNode = document.querySelector<HTMLElement>('[data-high-events]');
  const infraNode = document.querySelector<HTMLElement>('[data-infra-events]');
  const refreshNode = document.querySelector<HTMLElement>('[data-last-refresh]');
  if (activeNode) activeNode.textContent = String(safeEvents.length);
  if (highNode) highNode.textContent = String(high);
  if (infraNode) infraNode.textContent = String(infra);
  const refreshedAt = new Date(feed.updated_at);
  if (refreshNode) refreshNode.textContent = Number.isNaN(refreshedAt.getTime())
    ? '--:--:--' : refreshedAt.toLocaleTimeString([], { hour12: false });
  state.innerHTML = feed.mode === 'live' ? '<i></i> LIVE API' : '<i></i> SIMULATED FEED';
  state.dataset.mode = feed.mode;
  applyMapFilter();
}

function applyMapFilter(): void {
  const selected = document.querySelector<HTMLButtonElement>('[data-map-filter][aria-pressed="true"]')?.dataset.mapFilter ?? 'all';
  document.querySelectorAll<HTMLElement>('[data-event-type]').forEach((marker) => {
    marker.hidden = selected !== 'all' && marker.dataset.eventType !== selected;
  });
  document.querySelectorAll<SVGPathElement>('.nx-threat-route').forEach((route) => {
    route.style.display = selected === 'all' || route.classList.contains(`nx-route-${selected}`) ? '' : 'none';
  });
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
    void renderCountryBoundaries();
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
  document.querySelectorAll<HTMLButtonElement>('[data-map-filter]').forEach((button) => {
    button.addEventListener('click', () => {
      document.querySelectorAll<HTMLButtonElement>('[data-map-filter]').forEach((tab) => {
        const active = tab === button;
        tab.classList.toggle('active', active);
        tab.setAttribute('aria-pressed', String(active));
      });
      applyMapFilter();
    });
  });
  const interval = window.setInterval(() => void refreshThreatFeed(), 15000);
  window.addEventListener('beforeunload', () => window.clearInterval(interval), { once: true });
}

installV6Scene();
