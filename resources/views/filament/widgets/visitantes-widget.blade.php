<x-filament-widgets::widget>
    <x-filament::section heading="Visitantes por ubicación" icon="heroicon-o-globe-alt">

        {{-- Aviso si hay visitas sin geolocalizar --}}
        @if ($totalVisitas > 0 && $totalGeo < $totalVisitas)
            <div class="mb-4 flex items-start gap-3 rounded-lg border border-amber-500/30 bg-amber-500/10 px-4 py-3 text-sm text-amber-400">
                <svg class="mt-0.5 h-4 w-4 shrink-0" width="16" height="16" style="width:16px;height:16px;flex-shrink:0;margin-top:0.125rem" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20A10 10 0 0012 2z"/>
                </svg>
                <span>
                    <strong>{{ $totalVisitas - $totalGeo }}</strong> de <strong>{{ $totalVisitas }}</strong> visitas sin geolocalizar
                    @if ($totalGeo === 0)
                        — en <strong>local</strong> las IPs son <code class="bg-black/30 px-1 rounded">127.0.0.1</code> y no se pueden resolver. Los datos reales aparecerán en <strong>producción</strong>.
                    @else
                        — el worker de colas puede estar detenido (<code class="bg-black/30 px-1 rounded">php artisan queue:work</code>).
                    @endif
                </span>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- MAPA --}}
            <div class="lg:col-span-2">
                <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">
                    Mapa de visitantes
                </h3>
                <div class="rounded-xl overflow-hidden bg-gray-900 border border-gray-700" style="min-height:220px;">
                    <svg id="world-map-svg" width="100%" viewBox="0 0 960 500" style="display:block;"></svg>
                </div>
            </div>

            {{-- TOP PAÍSES --}}
            <div>
                <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">
                    Top países
                </h3>
                @if ($topPaises->isEmpty())
                    <div class="flex flex-col items-center justify-center py-10 text-center text-gray-500">
                        <svg class="w-10 h-10 mb-3 opacity-30" width="40" height="40" style="width:2.5rem;height:2.5rem;margin-bottom:0.75rem;opacity:0.3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064"/>
                        </svg>
                        <p class="text-sm">Sin datos de países aún.</p>
                        <p class="text-xs mt-1 opacity-60">Aparecerán con visitas desde producción.</p>
                    </div>
                @else
                    <div class="space-y-2">
                        @foreach ($topPaises as $pais)
                            @php
                                $pct  = $maxVisitas > 0 ? round(($pais->total / $maxVisitas) * 100) : 0;
                                $flag = $pais->country_code
                                    ? implode('', array_map(
                                        fn($c) => mb_chr(ord($c) - ord('A') + 0x1F1E6),
                                        str_split(strtoupper($pais->country_code))
                                      ))
                                    : '🌐';
                            @endphp
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200 flex items-center gap-2">
                                        <span class="text-base leading-none">{{ $flag }}</span>
                                        <span class="truncate max-w-[130px]">{{ $pais->country ?? 'Desconocido' }}</span>
                                    </span>
                                    <span class="text-sm font-bold text-gray-900 dark:text-white shrink-0 ml-2">
                                        {{ number_format($pais->total) }}
                                    </span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5">
                                    <div class="h-1.5 rounded-full bg-amber-500 transition-all duration-500"
                                        style="width: {{ $pct }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

    </x-filament::section>
</x-filament-widgets::widget>

@script
<script>
(function () {
    const visitedCodes = @json(
        $topPaises->pluck('country_code')->filter()->map(fn($c) => strtoupper($c))->values()
    );

    const alpha2ToNumeric = {
        "AF":"004","AL":"008","DZ":"012","AR":"032","AU":"036","AT":"040","BE":"056","BR":"076",
        "CA":"124","CL":"152","CN":"156","CO":"170","CZ":"203","DK":"208","EG":"818","FI":"246",
        "FR":"250","DE":"276","GR":"300","GT":"320","HN":"340","HK":"344","HU":"348","IN":"356",
        "ID":"360","IR":"364","IQ":"368","IE":"372","IL":"376","IT":"380","JP":"392","JO":"400",
        "KE":"404","KR":"410","MX":"484","MA":"504","NL":"528","NZ":"554","NG":"566","NO":"578",
        "PK":"586","PA":"591","PE":"604","PH":"608","PL":"616","PT":"620","RO":"642","RU":"643",
        "SA":"682","ZA":"710","ES":"724","SE":"752","CH":"756","TH":"764","TR":"792","UA":"804",
        "GB":"826","US":"840","UY":"858","VE":"862","VN":"704","BO":"068","EC":"218","PY":"600",
        "CR":"188","CU":"192","DO":"214","SV":"222","NI":"558","UG":"800","TZ":"834","ET":"231",
        "GH":"288","SN":"686","CM":"120","CI":"384","MZ":"508","AO":"024","ZW":"716","ZM":"894",
        "MW":"454","BW":"072","NA":"516","RW":"646","SO":"706","LY":"434","TN":"788","SD":"729",
        "NE":"562","ML":"466","BF":"854","TD":"148","MR":"478","BY":"112","MD":"498","RS":"688",
        "HR":"191","SK":"703","SI":"705","BA":"070","MK":"807","LT":"440","LV":"428","EE":"233",
        "IS":"352","LU":"442","MT":"470","CY":"196","AM":"051","GE":"268","AZ":"031","KZ":"398",
        "UZ":"860","TM":"795","KG":"417","TJ":"762","MN":"496","MM":"104","KH":"116","LA":"418",
        "NP":"524","BD":"050","LK":"144","SG":"702","MY":"458","BN":"096","TW":"158","QA":"634",
        "AE":"784","KW":"414","BH":"048","OM":"512","YE":"887","SY":"760","LB":"422","PS":"275",
        "PR":"630",
    };

    const visitedNumeric = new Set(
        visitedCodes.map(c => alpha2ToNumeric[c]).filter(Boolean)
    );

    function loadScript(src) {
        return new Promise((resolve, reject) => {
            if (document.querySelector(`script[src="${src}"]`)) { resolve(); return; }
            const s = document.createElement('script');
            s.src = src; s.onload = resolve; s.onerror = reject;
            document.head.appendChild(s);
        });
    }

    Promise.all([
        loadScript('https://cdn.jsdelivr.net/npm/d3@7/dist/d3.min.js'),
        loadScript('https://cdn.jsdelivr.net/npm/topojson-client@3/dist/topojson-client.min.js'),
    ]).then(() => {
        const svg    = d3.select("#world-map-svg");
        const width  = 960, height = 500;
        const proj   = d3.geoNaturalEarth1().scale(153).translate([width/2, height/2]);
        const path   = d3.geoPath().projection(proj);

        svg.append("rect").attr("width", width).attr("height", height).attr("fill", "#111827");

        return fetch("https://cdn.jsdelivr.net/npm/world-atlas@2/countries-110m.json")
            .then(r => r.json())
            .then(world => {
                const countries = topojson.feature(world, world.objects.countries);
                svg.selectAll("path")
                    .data(countries.features)
                    .join("path")
                    .attr("d", path)
                    .attr("fill", d => visitedNumeric.has(String(d.id)) ? "#f59e0b" : "#1f2937")
                    .attr("stroke", "#374151")
                    .attr("stroke-width", 0.4);
            });
    }).catch(() => {
        const el = document.getElementById('world-map-svg');
        if (el) el.innerHTML = '<text x="480" y="250" text-anchor="middle" fill="#6b7280" font-size="14">Mapa no disponible sin conexión</text>';
    });
})();
</script>
@endscript
