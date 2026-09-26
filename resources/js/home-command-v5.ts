function installV5CinematicLayers(): void {
  const globe = document.querySelector<HTMLElement>('.nx-command-globe');
  const stage = globe?.querySelector<HTMLElement>('.nx-world-stage');
  if (!globe || !stage || globe.dataset.v5Ready === '1') return;
  globe.dataset.v5Ready = '1';

  const frame = document.createElement('div');
  frame.className = 'nx-v5-globe-frame';
  frame.innerHTML = `
    <span class="nx-v5-ring nx-v5-ring-outer" aria-hidden="true"></span>
    <span class="nx-v5-ring nx-v5-ring-inner" aria-hidden="true"></span>
    <span class="nx-v5-ring nx-v5-ring-energy" aria-hidden="true"></span>
    <div class="nx-v5-pedestal" aria-hidden="true"><span>NEXVARY</span></div>
  `;
  stage.appendChild(frame);

  const overlays = document.createElement('div');
  overlays.className = 'nx-v5-threat-overlays';
  overlays.innerHTML = `
    <div class="nx-v5-threat-chip chip-na"><b>N. AMERICA</b><span>THREATS 1,284</span><em>▲ +12%</em></div>
    <div class="nx-v5-threat-chip chip-eu"><b>EUROPE</b><span>THREATS 892</span><em>▲ +7%</em></div>
    <div class="nx-v5-threat-chip chip-as"><b>ASIA</b><span>THREATS 1,756</span><em>▲ +18%</em></div>
    <div class="nx-v5-threat-chip chip-af"><b>AFRICA</b><span>THREATS 621</span><em>▲ +9%</em></div>
  `;
  globe.appendChild(overlays);

  const radarLogo = document.querySelector<HTMLElement>('.nx-radar-logo');
  if (radarLogo) radarLogo.innerHTML = '<img src="/nexvary-mark.svg" alt="" aria-hidden="true">';

  const compassCore = document.querySelector<HTMLElement>('.nx-v4-compass-core');
  if (compassCore) compassCore.innerHTML = '<img src="/nexvary-mark.svg" alt="" aria-hidden="true">';

  document.querySelectorAll<HTMLElement>('.nx-command-card,.nx-people-grid > *, .nx-summary-grid article').forEach((el, index) => {
    el.classList.add(index % 2 === 0 ? 'nx-v5-frame-silver' : 'nx-v5-frame-gold');
  });
}

installV5CinematicLayers();
