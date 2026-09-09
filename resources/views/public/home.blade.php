@extends('public.layout')

@section('title', app()->getLocale() === 'ar' ? 'NEXVARY — الأمن أبعد مما تراه' : 'NEXVARY — Security Beyond the Visible')
@section('description', app()->getLocale() === 'ar' ? 'منصة NEXVARY للأمن السيبراني ومكافحة التجسس الفني والتحليل الجنائي الرقمي وتقنيات الخصوصية والحماية متعددة الطبقات.' : 'NEXVARY unifies cybersecurity, counter-surveillance, digital forensics and privacy technology into one multi-layer security platform.')
@section('canonical', 'https://nexvary.com/')

@section('content')
@php($ar = app()->getLocale() === 'ar')
<main class="nx-command-home">
    <section class="nx-hero nx-hero-premium nx-command-hero">
        <div class="nx-hero-copy">
            <p class="nx-kicker">CYBERSECURITY · TSCM · DIGITAL INTELLIGENCE</p>
            <h1>
                @if($ar)
                    الأمن أبعد من <span>المرئي.</span>
                @else
                    SECURITY BEYOND <span>THE VISIBLE.</span>
                @endif
            </h1>
            <p class="nx-hero-subline">{{ $ar ? 'الأمن السيبراني · مكافحة التجسس الفني · الاستخبارات الرقمية' : 'Cybersecurity · Counter-Surveillance · Digital Intelligence' }}</p>
            <p class="nx-lead">{{ $ar ? 'تحمي NEXVARY الأصول الرقمية والبيئات الحساسة وصنّاع القرار عبر طبقات دفاع تقنية مترابطة، من تقليل سطح الهجوم إلى التحقق والتحليل والاستجابة.' : 'NEXVARY protects digital assets, sensitive environments and critical decision-makers through connected technical defense layers, from exposure reduction to validation, investigation and response.' }}</p>
            <div class="nx-actions">
                <a class="nx-btn nx-btn-primary" href="/contact">{{ $ar ? 'اطلب تقييمًا أمنيًا' : 'Request an assessment' }} <span aria-hidden="true">→</span></a>
                <a class="nx-btn" href="/services">{{ $ar ? 'استكشف القدرات' : 'Explore capabilities' }} <span aria-hidden="true">→</span></a>
            </div>
            <div class="nx-status-strip" aria-label="NEXVARY capability areas">
                <span><i></i>TSCM</span><span><i></i>Cyber Defense</span><span><i></i>Digital Forensics</span><span><i></i>Privacy</span>
            </div>
        </div>

        <div class="nx-command nx-command-globe" aria-label="Simulated global threat intelligence visualization">
            <div class="nx-command-topline"><strong>GLOBAL SECURITY VISUAL</strong><span>SIMULATED TELEMETRY</span></div>

            <div class="nx-world-stage" aria-hidden="true">
                <div class="nx-world-orbit nx-world-orbit-a"></div>
                <div class="nx-world-orbit nx-world-orbit-b"></div>
                <div class="nx-world-orbit nx-world-orbit-c"></div>

                <svg class="nx-world-svg" viewBox="0 0 600 600" role="presentation">
                    <defs>
                        <radialGradient id="nxOcean" cx="34%" cy="28%" r="74%">
                            <stop offset="0" stop-color="#153f66"/>
                            <stop offset=".45" stop-color="#071b32"/>
                            <stop offset="1" stop-color="#010712"/>
                        </radialGradient>
                        <linearGradient id="nxLand" x1="0" x2="1" y1="0" y2="1">
                            <stop offset="0" stop-color="#3ebfff" stop-opacity=".68"/>
                            <stop offset="1" stop-color="#0c4e82" stop-opacity=".35"/>
                        </linearGradient>
                        <filter id="nxGlow" x="-100%" y="-100%" width="300%" height="300%">
                            <feGaussianBlur stdDeviation="4" result="b"/>
                            <feMerge><feMergeNode in="b"/><feMergeNode in="SourceGraphic"/></feMerge>
                        </filter>
                        <clipPath id="nxSphere"><circle cx="300" cy="300" r="228"/></clipPath>
                    </defs>
                    <circle cx="300" cy="300" r="228" fill="url(#nxOcean)" stroke="#52cfff" stroke-opacity=".7" stroke-width="1.4"/>
                    <g clip-path="url(#nxSphere)" class="nx-world-grid">
                        <ellipse cx="300" cy="300" rx="228" ry="72"/>
                        <ellipse cx="300" cy="300" rx="228" ry="136"/>
                        <ellipse cx="300" cy="300" rx="96" ry="228"/>
                        <ellipse cx="300" cy="300" rx="166" ry="228"/>
                        <path d="M72 300H528M300 72V528"/>
                    </g>
                    <g clip-path="url(#nxSphere)" fill="url(#nxLand)" stroke="#7bdcff" stroke-opacity=".5" stroke-width="1.1">
                        <path d="M102 210l37-47 58-31 50 9 33 29-24 29-38 10-21 34-31 10-16 41-27-7-19-32z"/>
                        <path d="M213 287l31 17 22 42-11 62-24 58-22-18-13-68 3-57z"/>
                        <path d="M303 178l41-31 69-5 45 21 30 49-21 30-55 1-27 27-44-2-31-28-30-20z"/>
                        <path d="M334 282l47-14 41 34 9 62-27 68-41 15-30-31-13-62z"/>
                        <path d="M438 302l38-19 49 21 17 35-23 24-51-8-31-26z"/>
                        <path d="M478 423l37-9 34 24-18 29-48 2-25-20z"/>
                    </g>
                    <g class="nx-world-routes" fill="none" stroke-linecap="round">
                        <path d="M154 221 Q300 74 428 224"/>
                        <path d="M207 356 Q329 180 481 326"/>
                        <path d="M345 225 Q247 255 180 340"/>
                        <path d="M385 287 Q432 224 499 242"/>
                    </g>
                    <g class="nx-world-nodes" filter="url(#nxGlow)">
                        <circle cx="155" cy="221" r="5"/><circle cx="207" cy="356" r="4"/>
                        <circle cx="345" cy="225" r="5"/><circle cx="385" cy="287" r="4"/>
                        <circle cx="428" cy="224" r="5"/><circle cx="481" cy="326" r="4"/>
                        <circle class="nx-risk-node" cx="499" cy="242" r="5"/><circle class="nx-risk-node" cx="180" cy="340" r="4"/>
                    </g>
                </svg>
            </div>

            <aside class="nx-threat-card">
                <div class="nx-mini-title"><span>{{ $ar ? 'خريطة التهديد' : 'THREAT MAP' }}</span><b>DEMO</b></div>
                <p>{{ $ar ? 'مؤشرات بصرية تجريبية لواجهة مركز العمليات.' : 'Simulated visual indicators for the command interface.' }}</p>
                <dl>
                    <div><dt><i class="risk-high"></i>{{ $ar ? 'مرتفع' : 'HIGH RISK' }}</dt><dd>12</dd></div>
                    <div><dt><i class="risk-medium"></i>{{ $ar ? 'متوسط' : 'MEDIUM RISK' }}</dt><dd>38</dd></div>
                    <div><dt><i class="risk-low"></i>{{ $ar ? 'منخفض' : 'LOW RISK' }}</dt><dd>156</dd></div>
                </dl>
                <small>UPDATED · SIMULATED</small>
            </aside>

            <div class="nx-globe-caption" aria-hidden="true"><span>DRAG / ORBIT VISUAL</span><span>GLOBAL DEFENSE LAYER</span></div>
        </div>
    </section>

    <section class="nx-live-grid nx-section-tight" aria-label="Security operations visual modules">
        <article class="nx-ops-panel nx-radar-panel">
            <header><div><p>RF ENVIRONMENT RADAR</p><h2>{{ $ar ? 'رصد البيئة اللاسلكية' : 'RF environment awareness' }}</h2></div><span class="nx-demo-pill">SIMULATED</span></header>
            <div class="nx-radar-layout">
                <div class="nx-radar-stats">
                    <div><small>{{ $ar ? 'إشارات مكتشفة' : 'DETECTED SIGNALS' }}</small><strong>23</strong></div>
                    <div><small>{{ $ar ? 'مصادر نشطة' : 'ACTIVE SOURCES' }}</small><strong>7</strong></div>
                    <div class="nx-band-list"><small>BAND MONITORING</small><span>433 MHz</span><span>868 MHz</span><span>2.4 GHz</span><span>5.8 GHz</span><span>GPS</span></div>
                </div>
                <div class="nx-radar nx-radar-large" aria-hidden="true">
                    <div class="nx-radar-bearing bearing-1">0</div><div class="nx-radar-bearing bearing-2">90</div><div class="nx-radar-bearing bearing-3">180</div><div class="nx-radar-bearing bearing-4">270</div>
                    <div class="nx-sweep"></div><i class="p1"></i><i class="p2"></i><i class="p3"></i><i class="p4 nx-alert-dot"></i><div class="nx-radar-logo">N</div>
                </div>
                <div class="nx-signal-readout">
                    <div><small>SIGNAL STRENGTH</small><strong>-42 <em>dBm</em></strong><span class="nx-micro-wave"></span></div>
                    <div><small>FREQUENCY</small><strong>2.462 <em>GHz</em></strong></div>
                    <div><small>THREAT LEVEL</small><strong class="nx-medium">MEDIUM</strong></div>
                    <div><small>LOCATION</small><strong>Dubai, UAE</strong><em>DEMO DATA</em></div>
                </div>
            </div>
            <footer><a href="/services">{{ $ar ? 'استكشف قدرات TSCM' : 'Explore TSCM capability' }} <span>→</span></a></footer>
        </article>

        <article class="nx-ops-panel nx-spectrum-panel">
            <header><div><p>REAL-TIME RF SPECTRUM ANALYSIS</p><h2>2.462 <span>GHz</span></h2></div><span class="nx-status-live"><i></i>DEMO</span></header>
            <div class="nx-spectrum-chart" aria-hidden="true">
                <div class="nx-spectrum-gridlines"></div>
                <svg viewBox="0 0 720 220" preserveAspectRatio="none">
                    <defs><linearGradient id="nxSpecFill" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#36a9ff" stop-opacity=".5"/><stop offset="1" stop-color="#36a9ff" stop-opacity="0"/></linearGradient></defs>
                    <path class="nx-spec-fill" d="M0 194 L18 191 36 189 52 181 67 190 84 184 96 169 108 187 123 184 137 160 151 184 168 177 183 141 198 183 214 176 229 185 246 172 259 178 274 153 286 182 301 170 315 94 327 180 342 173 357 186 373 164 388 177 402 130 418 182 433 171 447 190 462 184 478 176 493 190 507 181 522 153 536 185 551 177 566 190 581 167 597 186 612 177 627 148 641 184 656 174 671 189 686 178 702 186 720 176 L720 220 L0 220 Z"/>
                    <path class="nx-spec-line" d="M0 194 L18 191 36 189 52 181 67 190 84 184 96 169 108 187 123 184 137 160 151 184 168 177 183 141 198 183 214 176 229 185 246 172 259 178 274 153 286 182 301 170 315 94 327 180 342 173 357 186 373 164 388 177 402 130 418 182 433 171 447 190 462 184 478 176 493 190 507 181 522 153 536 185 551 177 566 190 581 167 597 186 612 177 627 148 641 184 656 174 671 189 686 178 702 186 720 176"/>
                    <line class="nx-detected-line" x1="315" y1="55" x2="315" y2="205"/><circle class="nx-detected-dot" cx="315" cy="94" r="5"/>
                </svg>
                <div class="nx-freq-axis"><span>2.40</span><span>2.45</span><span>2.50</span><span>2.55</span><span>2.60 GHz</span></div>
            </div>
            <div class="nx-waterfall" aria-hidden="true">
                @foreach([18,24,31,22,36,48,29,18,53,72,41,26,38,82,95,78,46,31,22,55,43,28,62,74,45,35,24,53,68,44,29,18] as $heat)
                    <span style="--heat: {{ $heat }}%"></span>
                @endforeach
            </div>
            <footer><span>{{ $ar ? 'لوحة تحليل تجريبية — لا تمثل قياسًا حيًا' : 'Demonstration visualization — not a live measurement' }}</span><a href="/services">FULL RF ANALYSIS →</a></footer>
        </article>
    </section>

    <section class="nx-section nx-capability-section">
        <div class="nx-section-heading-row"><div class="nx-section-title"><p>ONE SECURITY PARTNER</p><h2>{{ $ar ? 'طبقات دفاع متعددة.' : 'Multiple defense layers.' }}</h2></div><a class="nx-section-link" href="/services">{{ $ar ? 'عرض كل الخدمات' : 'View all services' }} →</a></div>
        <div class="nx-command-cards">
            @foreach([
                ['01','CYBERSECURITY',['Network Security','Endpoint Security','Vulnerability Assessment','Security Hardening','Incident Response']],
                ['02','COUNTER-SURVEILLANCE / TSCM',['RF Detection','Spectrum Analysis','Electronic Surveillance Detection','Office & Meeting Room Inspection','Vehicle Inspection']],
                ['03','EXECUTIVE TECHNOLOGY PROTECTION',['Executive Device Security','Secure Communications','Travel Security Configuration','Digital Privacy']],
                ['04','DIGITAL FORENSICS',['Device Forensics','Incident Investigation','Evidence Preservation','Digital Investigation']],
                ['05','PHYSICAL SECURITY TECHNOLOGY',['CCTV','Access Control','Alarm Systems','Secure Networking','Sensors & IoT Security']]
            ] as $service)
                <article class="nx-command-card"><div class="nx-card-top"><span>{{ $service[0] }}</span><div class="nx-line-icon" aria-hidden="true">◇</div></div><h3>{{ $service[1] }}</h3><ul>@foreach($service[2] as $point)<li>{{ $point }}</li>@endforeach</ul><a href="/services" aria-label="{{ $service[1] }}">→</a></article>
            @endforeach
        </div>
    </section>

    <section class="nx-section nx-people-section">
        <div class="nx-people-copy"><p>PROTECTING CRITICAL DECISIONS</p><h2>{{ $ar ? 'نحمي الأشخاص والبيئات التي لا تحتمل المخاطرة.' : 'Protecting the people and environments that cannot afford compromise.' }}</h2><span>{{ $ar ? 'حماية تقنية هادئة ومدروسة للمكاتب وغرف الاجتماعات والمركبات والسفر والإقامة.' : 'Discreet technical protection for offices, boardrooms, vehicles, travel and private environments.' }}</span><a class="nx-btn" href="/about">{{ $ar ? 'اعرف المزيد' : 'Learn more' }} →</a></div>
        <div class="nx-people-grid">
            @foreach([['⌂','Executive Offices'],['◎','Boardrooms'],['▦','Corporate Headquarters'],['◇','Executive Vehicles'],['▥','Hotels & Travel'],['⌁','Private Residences']] as $place)
                <div><strong aria-hidden="true">{{ $place[0] }}</strong><span>{{ $place[1] }}</span></div>
            @endforeach
        </div>
    </section>

    <section class="nx-section nx-security-summary">
        <div class="nx-summary-copy"><p>NEXVARY SECURITY OPERATIONS</p><h2>{{ $ar ? 'صورة تشغيلية موحدة عبر الطبقات الرقمية والمادية.' : 'A unified operational view across digital and physical layers.' }}</h2><span>{{ $ar ? 'المؤشرات أدناه تصميم توضيحي للواجهة وليست حالة مراقبة حية.' : 'The indicators below demonstrate the interface and are not live monitoring data.' }}</span></div>
        <div class="nx-summary-grid">
            <article><small>THREAT STATUS</small><strong class="nx-low">LOW RISK</strong><span class="nx-spark spark-a"></span></article>
            <article><small>RF ENVIRONMENT</small><strong>8</strong><em>ACTIVE SIGNALS</em><span class="nx-bars-mini"></span></article>
            <article><small>NETWORK EXPOSURE</small><strong>17</strong><em>OPEN FINDINGS</em><div class="nx-gauge"><b>35</b></div></article>
            <article><small>ENDPOINT STATUS</small><strong>243</strong><em>PROTECTED</em><div class="nx-ring"><b>96%</b></div></article>
            <article><small>INCIDENT READINESS</small><strong class="nx-low">HIGH</strong><div class="nx-mini-radar"></div></article>
        </div>
    </section>

    <section class="nx-section nx-seo-section">
        <div class="nx-ssr-copy">
            @if($ar)
                <p>تعمل NEXVARY على ربط طبقات الحماية التي غالبًا ما تُدار منفصلة: أمن الشبكات والأجهزة، كشف التعرض التقني، فحص البيئات الحساسة، إدارة الخصوصية، والتحليل الجنائي الرقمي عند الحاجة. الفكرة ليست إضافة أدوات أكثر فحسب، بل بناء مسار واضح يبدأ من تقليل سطح الهجوم وينتهي بالتحقق والتوثيق واتخاذ قرار قابل للدفاع عنه.</p>
                <p>يعتمد الموقع على عرض الخدمات والأدوات المتاحة بصورة شفافة، مع فصل واضح بين الخصائص التوعوية والوظائف التي تحتاج مختصين أو تحققًا مهنيًا. يمكنك الانتقال إلى صفحة الخدمات لمعرفة مجالات الحماية، أو إلى التطبيقات لمراجعة الأدوات المتاحة، أو إلى أعمالنا للاطلاع على المشاريع المنشورة وحالة توفر كل مشروع.</p>
            @else
                <p>NEXVARY connects security layers that are often managed separately: endpoint and network defense, technical exposure reduction, counter-surveillance awareness, privacy engineering, and digital-forensics workflows when evidence must be preserved and reviewed. The objective is not simply to add more tools. It is to create a clear path from reducing attack surface, through validation and investigation, to reporting that can support an operational decision.</p>
                <p>The platform presents services and applications with a deliberate distinction between awareness features, defensive software, and work that requires professional validation. Visitors can review the security service portfolio, explore privacy-first applications, inspect published projects in Our Work, or contact NEXVARY for a specific technical requirement.</p>
            @endif
        </div>
    </section>
</main>
@endsection
