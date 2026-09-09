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

type LonLat = [number, number];

const worldPolygons: LonLat[][] = [
  [[-168,72],[-145,70],[-126,58],[-115,52],[-103,50],[-94,46],[-84,49],[-76,45],[-66,45],[-61,50],[-70,58],[-84,61],[-95,67],[-112,72],[-140,74],[-168,72]],
  [[-82,13],[-78,7],[-74,1],[-71,-9],[-67,-17],[-63,-27],[-58,-36],[-53,-49],[-66,-55],[-72,-44],[-76,-30],[-80,-15],[-82,13]],
  [[-10,36],[-5,43],[8,48],[20,55],[35,58],[48,60],[62,56],[78,57],[95,53],[113,48],[126,45],[138,49],[151,58],[161,63],[169,58],[160,48],[143,43],[130,35],[118,28],[105,20],[94,16],[80,20],[67,24],[54,28],[43,33],[31,35],[18,38],[7,37],[-10,36]],
  [[-17,35],[-8,28],[-3,20],[8,12],[18,5],[28,-2],[33,-12],[30,-24],[23,-34],[14,-35],[5,-30],[-2,-18],[-8,-4],[-12,12],[-17,24],[-17,35]],
  [[112,-11],[120,-17],[131,-20],[141,-28],[151,-33],[153,-39],[145,-44],[132,-42],[121,-35],[114,-26],[112,-11]],
  [[-54,82],[-42,78],[-30,72],[-25,65],[-35,59],[-49,61],[-61,69],[-65,77],[-54,82]],
  [[47,-13],[50,-18],[49,-26],[45,-26],[43,-19],[47,-13]],
  [[130,33],[137,35],[142,41],[145,44],[141,36],[136,32],[130,33]],
];

const threatPoints: LonLat[] = [
  [-74,40.7],[-0.1,51.5],[31.2,30.0],[55.3,25.2],[77.2,28.6],[103.8,1.35],[139.7,35.7],[151.2,-33.8],[-46.6,-23.6]
];

function projectOrthographic(lon: number, lat: number, rotation: number, radius: number, cx: number, cy: number): {x:number;y:number;visible:boolean;z:number} {
  const lambda = (lon + rotation) * Math.PI / 180;
  const phi = lat * Math.PI / 180;
  const cosPhi = Math.cos(phi);
  const z = cosPhi * Math.cos(lambda);
  return {
    x: cx + radius * cosPhi * Math.sin(lambda),
    y: cy - radius * Math.sin(phi),
    visible: z > 0,
    z,
  };
}

function installVisualStyles(): void {
  if (document.getElementById('nx-visual-overhaul-v3')) return;
  const style = document.createElement('style');
  style.id = 'nx-visual-overhaul-v3';
  style.textContent = `
    .nx-world-stage{position:relative;perspective:1100px;isolation:isolate}
    .nx-world-canvas{width:100%;height:100%;display:block;filter:drop-shadow(0 0 22px rgba(44,175,255,.28)) drop-shadow(0 28px 36px rgba(0,0,0,.44))}
    .nx-command-globe{background:radial-gradient(circle at 58% 42%,rgba(17,133,217,.20),transparent 32%),radial-gradient(circle at 50% 75%,rgba(145,83,255,.05),transparent 28%),linear-gradient(180deg,rgba(2,10,20,.995),rgba(1,5,10,.995))}
    .nx-radar-large{overflow:hidden;box-shadow:inset 0 0 60px rgba(33,165,255,.10),0 0 40px rgba(16,100,174,.11)}
    .nx-radar-large::after{content:'';position:absolute;inset:0;border-radius:50%;background:conic-gradient(from 0deg,rgba(90,220,255,.00) 0 76%,rgba(72,202,255,.15) 88%,rgba(126,241,255,.72) 99%,rgba(90,220,255,.00) 100%);animation:nxRadarSweepV3 3.3s linear infinite;filter:drop-shadow(0 0 10px rgba(87,213,255,.55));pointer-events:none}
    .nx-radar-large>i{animation:nxRadarPulseV3 2.4s ease-out infinite;box-shadow:0 0 11px currentColor}
    .nx-radar-large>i:nth-of-type(2){animation-delay:.55s}.nx-radar-large>i:nth-of-type(3){animation-delay:1.1s}.nx-radar-large>i:nth-of-type(4){animation-delay:1.65s}
    .nx-spectrum-chart{position:relative;overflow:hidden}
    .nx-spectrum-chart svg{filter:drop-shadow(0 0 8px rgba(54,169,255,.28))}
    .nx-spectrum-chart::after{content:'FFT · WATERFALL · PEAK HOLD';position:absolute;right:14px;top:10px;color:#698da7;font-size:.48rem;letter-spacing:.13em}
    .nx-waterfall span{transition:opacity .25s ease,filter .25s ease}
    @keyframes nxRadarSweepV3{to{transform:rotate(360deg)}}
    @keyframes nxRadarPulseV3{0%,100%{opacity:.28;transform:scale(.8)}42%{opacity:1;transform:scale(1.55)}70%{opacity:.5;transform:scale(1)}}
    @media (prefers-reduced-motion:reduce){.nx-radar-large::after,.nx-radar-large>i{animation:none!important}}
  `;
  document.head.appendChild(style);
}

function upgradeThreatGlobe(): void {
  const svg = document.querySelector<SVGSVGElement>('.nx-world-svg');
  const stage = svg?.closest<HTMLElement>('.nx-world-stage');
  if (!svg || !stage) return;

  const canvas = document.createElement('canvas');
  canvas.className = 'nx-world-canvas';
  canvas.setAttribute('aria-hidden', 'true');
  svg.replaceWith(canvas);

  const ctx = canvas.getContext('2d');
  if (!ctx) return;

  let rotation = 18;
  let last = performance.now();
  let raf = 0;
  const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  const draw = (now: number) => {
    const rect = stage.getBoundingClientRect();
    const dpr = Math.min(window.devicePixelRatio || 1, 2);
    const w = Math.max(320, rect.width);
    const h = Math.max(320, rect.height);
    if (canvas.width !== Math.floor(w * dpr) || canvas.height !== Math.floor(h * dpr)) {
      canvas.width = Math.floor(w * dpr);
      canvas.height = Math.floor(h * dpr);
      canvas.style.width = `${w}px`;
      canvas.style.height = `${h}px`;
    }
    ctx.setTransform(dpr,0,0,dpr,0,0);
    ctx.clearRect(0,0,w,h);

    const dt = Math.min(40, now - last);
    last = now;
    if (!reduced) rotation = (rotation + dt * 0.0048) % 360;

    const radius = Math.min(w,h) * .39;
    const cx = w * .51;
    const cy = h * .50;

    const glow = ctx.createRadialGradient(cx-radius*.35,cy-radius*.42,radius*.1,cx,cy,radius*1.2);
    glow.addColorStop(0,'rgba(35,145,220,.82)');
    glow.addColorStop(.48,'rgba(5,42,74,.96)');
    glow.addColorStop(1,'rgba(1,6,13,.98)');
    ctx.save();
    ctx.shadowColor='rgba(56,196,255,.45)';
    ctx.shadowBlur=32;
    ctx.beginPath();ctx.arc(cx,cy,radius,0,Math.PI*2);ctx.fillStyle=glow;ctx.fill();
    ctx.shadowBlur=0;
    ctx.strokeStyle='rgba(120,225,255,.88)';ctx.lineWidth=1.4;ctx.stroke();
    ctx.restore();

    ctx.save();
    ctx.beginPath();ctx.arc(cx,cy,radius,0,Math.PI*2);ctx.clip();

    ctx.strokeStyle='rgba(88,190,255,.17)';ctx.lineWidth=.7;
    for (let lat=-60;lat<=60;lat+=30){
      ctx.beginPath();let started=false;
      for(let lon=-180;lon<=180;lon+=3){const p=projectOrthographic(lon,lat,rotation,radius,cx,cy);if(!p.visible){started=false;continue;}if(!started){ctx.moveTo(p.x,p.y);started=true;}else ctx.lineTo(p.x,p.y);}ctx.stroke();
    }
    for (let lon=-180;lon<180;lon+=30){
      ctx.beginPath();let started=false;
      for(let lat=-88;lat<=88;lat+=2){const p=projectOrthographic(lon,lat,rotation,radius,cx,cy);if(!p.visible){started=false;continue;}if(!started){ctx.moveTo(p.x,p.y);started=true;}else ctx.lineTo(p.x,p.y);}ctx.stroke();
    }

    for (const polygon of worldPolygons) {
      ctx.beginPath(); let started=false;
      for (const [lon,lat] of polygon) {
        const p=projectOrthographic(lon,lat,rotation,radius,cx,cy);
        if (!p.visible){started=false;continue;}
        if(!started){ctx.moveTo(p.x,p.y);started=true;} else ctx.lineTo(p.x,p.y);
      }
      if(started){ctx.closePath();ctx.fillStyle='rgba(30,142,205,.60)';ctx.fill();ctx.strokeStyle='rgba(156,232,255,.55)';ctx.lineWidth=.8;ctx.stroke();}
    }

    const routePairs:[[number,number],[number,number]][] = [
      [threatPoints[0],threatPoints[1]],[threatPoints[1],threatPoints[3]],[threatPoints[3],threatPoints[4]],[threatPoints[4],threatPoints[6]],[threatPoints[5],threatPoints[7]],[threatPoints[8],threatPoints[3]]
    ];
    ctx.lineCap='round';
    routePairs.forEach(([a,b],i)=>{
      const pa=projectOrthographic(a[0],a[1],rotation,radius,cx,cy);const pb=projectOrthographic(b[0],b[1],rotation,radius,cx,cy);
      if(!pa.visible||!pb.visible)return;
      const mx=(pa.x+pb.x)/2;const my=(pa.y+pb.y)/2-radius*(.11+(i%3)*.035);
      ctx.beginPath();ctx.moveTo(pa.x,pa.y);ctx.quadraticCurveTo(mx,my,pb.x,pb.y);
      ctx.strokeStyle=i%3===0?'rgba(255,192,85,.72)':i%2===0?'rgba(151,105,255,.65)':'rgba(81,211,255,.72)';
      ctx.lineWidth=1.2;ctx.setLineDash([4,7]);ctx.lineDashOffset=-(now*.018+i*8);ctx.stroke();
    });
    ctx.setLineDash([]);

    threatPoints.forEach(([lon,lat],i)=>{
      const p=projectOrthographic(lon,lat,rotation,radius,cx,cy);if(!p.visible)return;
      const pulse=3.2+Math.sin(now/430+i)*1.3;
      ctx.beginPath();ctx.arc(p.x,p.y,pulse+4,0,Math.PI*2);ctx.fillStyle=i%4===0?'rgba(255,72,96,.13)':'rgba(95,226,255,.10)';ctx.fill();
      ctx.beginPath();ctx.arc(p.x,p.y,pulse,0,Math.PI*2);ctx.fillStyle=i%4===0?'#ff5368':'#7beaff';ctx.shadowColor=ctx.fillStyle;ctx.shadowBlur=12;ctx.fill();ctx.shadowBlur=0;
    });

    const shine=ctx.createRadialGradient(cx-radius*.38,cy-radius*.42,0,cx-radius*.25,cy-radius*.30,radius*.85);
    shine.addColorStop(0,'rgba(174,239,255,.16)');shine.addColorStop(.5,'rgba(63,190,255,.04)');shine.addColorStop(1,'rgba(0,0,0,0)');
    ctx.fillStyle=shine;ctx.fillRect(cx-radius,cy-radius,radius*2,radius*2);
    ctx.restore();

    ctx.beginPath();ctx.arc(cx,cy,radius+1.5,0,Math.PI*2);ctx.strokeStyle='rgba(87,205,255,.36)';ctx.lineWidth=3;ctx.stroke();
    raf=requestAnimationFrame(draw);
  };

  raf=requestAnimationFrame(draw);
  window.addEventListener('beforeunload',()=>cancelAnimationFrame(raf),{once:true});
}

function upgradeRfPanel(): void {
  const radar = document.querySelector<HTMLElement>('.nx-radar-large');
  if (radar && !radar.querySelector('.nx-rf-rings-v3')) {
    const rings = document.createElement('div');
    rings.className='nx-rf-rings-v3';
    rings.setAttribute('aria-hidden','true');
    rings.style.cssText='position:absolute;inset:8%;border:1px solid rgba(74,184,255,.17);border-radius:50%;box-shadow:0 0 0 30px rgba(32,140,220,.026),0 0 0 60px rgba(32,140,220,.018);pointer-events:none';
    radar.appendChild(rings);
  }

  const chart = document.querySelector<HTMLElement>('.nx-spectrum-chart');
  const line = chart?.querySelector<SVGPathElement>('.nx-spec-line');
  const fill = chart?.querySelector<SVGPathElement>('.nx-spec-fill');
  const marker = chart?.querySelector<SVGLineElement>('.nx-detected-line');
  const dot = chart?.querySelector<SVGCircleElement>('.nx-detected-dot');
  const waterfall = document.querySelector<HTMLElement>('.nx-waterfall');
  if (!chart || !line || !fill) return;

  const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  const points = 55;
  const buildPath = (t:number) => {
    const coords:string[]=[];
    let peakX=0,peakY=220;
    for(let i=0;i<points;i++){
      const x=(720/(points-1))*i;
      const noise=Math.sin(i*1.73+t*.0021)*7+Math.sin(i*.49+t*.0037)*5;
      const peak1=Math.exp(-Math.pow((i-24)/2.1,2))*82;
      const peak2=Math.exp(-Math.pow((i-37)/2.7,2))*52;
      const jitter=reduced?0:Math.sin(t*.006+i)*3;
      const y=190-noise-peak1-peak2-jitter;
      if(y<peakY){peakY=y;peakX=x;}
      coords.push(`${i===0?'M':'L'}${x.toFixed(1)} ${y.toFixed(1)}`);
    }
    return {line:coords.join(' '),fill:`${coords.join(' ')} L720 220 L0 220 Z`,peakX,peakY};
  };

  let raf=0;
  const animate=(t:number)=>{
    const p=buildPath(t);
    line.setAttribute('d',p.line);fill.setAttribute('d',p.fill);
    marker?.setAttribute('x1',p.peakX.toFixed(1));marker?.setAttribute('x2',p.peakX.toFixed(1));
    dot?.setAttribute('cx',p.peakX.toFixed(1));dot?.setAttribute('cy',p.peakY.toFixed(1));
    if(waterfall){
      Array.from(waterfall.children).forEach((el,index)=>{
        const span=el as HTMLElement;
        const v=.35+.65*Math.max(0,Math.sin(t*.0025+index*.72));
        span.style.opacity=v.toFixed(2);span.style.filter=`brightness(${(.7+v*.85).toFixed(2)})`;
      });
    }
    raf=requestAnimationFrame(animate);
  };
  raf=requestAnimationFrame(animate);
  window.addEventListener('beforeunload',()=>cancelAnimationFrame(raf),{once:true});
}

installVisualStyles();
upgradeThreatGlobe();
upgradeRfPanel();
