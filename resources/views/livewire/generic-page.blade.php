<div>
    @if ($isPreview)
        <div class="fixed top-0 inset-x-0 z-[9999] flex items-center justify-center gap-3 bg-amber-400 text-black text-sm font-bold py-2 px-4 shadow-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
            MODO VISTA PREVIA — Esta página puede no estar publicada
        </div>
        <div class="h-9"></div>{{-- spacer para que el contenido no quede tapado por el banner --}}
    @endif

    @if ($page && $page->builder_content)

        @php
            $hasHero = collect($page->builder_content)->firstWhere('type', 'hero') !== null;
        @endphp

        {{-- Compact page header if no hero block --}}
        @if (!$hasHero)
            <div class="relative pt-36 pb-14 overflow-hidden bg-[var(--bg_section)]">
                <div class="absolute inset-0 bg-gradient-to-br from-[var(--bg_primary)] via-[#1c1c1c] to-[var(--bg_primary)]"></div>
                <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center pt-10">
                    <h1 class="text-3xl sm:text-5xl md:text-7xl font-['Anton'] text-[var(--text_primary)] uppercase tracking-widest drop-shadow-2xl">
                        {{ $page->title }}
                    </h1>
                    <div class="h-1 w-20 bg-[var(--accent)] mx-auto mt-6 rounded"></div>
                </div>
            </div>
        @endif

        @foreach ($page->builder_content as $block)
            {{-- ═══════ HERO ═══════ --}}
            @if ($block['type'] === 'hero')
                <section class="relative h-screen flex items-center justify-center overflow-hidden">
                    <div class="absolute inset-0 z-0">
                        @if (!empty($block['data']['hero_image']))
                            <img src="{{ asset('storage/' . $block['data']['hero_image']) }}"
                                 class="w-full h-full object-cover"
                                 onerror="this.src='https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&q=80'">
                        @else
                            <video autoplay loop muted playsinline class="w-full h-full object-cover">
                                @if (!empty($block['data']['hero_video']))
                                    <source src="{{ asset('storage/' . $block['data']['hero_video']) }}"
                                        type="video/mp4">
                                @else
                                    <source src="{{ asset('videos/hero.mp4') }}" type="video/mp4">
                                @endif
                            </video>
                        @endif
                        <div class="absolute inset-0 bg-[var(--bg_primary)]/40 mix-blend-multiply"></div>
                        <div class="absolute inset-0 bg-gradient-to-t from-[var(--bg_primary)] via-transparent to-transparent">
                        </div>
                    </div>
                    <div class="relative z-10 text-center px-4 max-w-4xl mx-auto mt-20">
                        <h1
                            class="text-4xl sm:text-6xl md:text-8xl font-['Anton'] text-[var(--text_primary)] uppercase tracking-widest mb-6 drop-shadow-2xl opacity-0 animate-fade-in-up">
                            {{ $block['data']['hero_heading'] ?? $page->title }}
                        </h1>
                        <p
                            class="text-xl md:text-2xl text-[var(--text_secondary)] font-medium mb-10 opacity-0 animate-fade-in-up delay-[300ms]">
                            {{ $block['data']['hero_subheading'] ?? '' }}
                        </p>
                    </div>
                </section>

                {{-- ═══════ BBQ SECTION ═══════ --}}
            @elseif($block['type'] === 'bbq_section')
                <section class="relative py-28 overflow-hidden" id="bbq" style="background-color: #0f0f0f;">
                    @if (!empty($block['data']['background_image']))
                        <div class="absolute inset-0">
                            <img src="{{ asset('storage/' . $block['data']['background_image']) }}"
                                class="w-full h-full object-cover opacity-20"
                                onerror="this.parentElement.style.display='none'">
                            <div class="absolute inset-0 bg-gradient-to-b from-[var(--bg_primary)]/80 via-black/50 to-[var(--bg_primary)]/90"></div>
                        </div>
                    @else
                        <div class="absolute inset-0 bg-gradient-to-br from-[#1a0800] via-[#0f0f0f] to-[var(--bg_primary)]"></div>
                    @endif
                    <div class="absolute inset-0 opacity-5"
                        style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23E52B2B\' fill-opacity=\'0.4\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');">
                    </div>

                    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="text-center mb-16" x-data="{ shown: false }" x-intersect.once="shown = true"
                            :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'"
                            class="transition-all duration-1000 transform">
                            <div class="inline-flex items-center gap-3 mb-4">
                                <div class="h-px w-12 bg-[var(--accent)]"></div>
                                <span class="text-[var(--accent)] uppercase tracking-[0.3em] text-sm font-bold">BBQ &amp;
                                    Smoke</span>
                                <div class="h-px w-12 bg-[var(--accent)]"></div>
                            </div>
                            <h2
                                class="text-4xl md:text-6xl font-['Anton'] text-[var(--text_primary)] uppercase tracking-widest mb-4 drop-shadow-2xl">
                                {{ $block['data']['heading'] ?? '' }}
                            </h2>
                            <p class="text-[var(--accent)] text-xl font-semibold italic mb-4">
                                {{ $block['data']['subheading'] ?? '' }}</p>
                            <div class="h-1 w-24 bg-[var(--accent)] mx-auto rounded mb-6"></div>
                            @if (!empty($block['data']['description']))
                                <p class="text-[var(--text_secondary)] max-w-2xl mx-auto text-lg whitespace-pre-line">
                                    {{ $block['data']['description'] }}</p>
                            @endif
                        </div>

                        @if (!empty($block['data']['items']))
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                                @foreach ($block['data']['items'] as $i => $item)
                                    <div x-data="{ shown: false }" x-intersect.once="shown = true"
                                        :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'"
                                        class="transition-all duration-700 group relative bg-[var(--bg_card)] rounded-2xl overflow-hidden border border-[var(--border)] hover:border-[var(--accent)] shadow-2xl hover:-translate-y-2 hover:shadow-[0_0_30px_rgba(229,43,43,0.2)]"
                                        style="transition-delay: {{ $i * 100 }}ms">
                                        <div class="relative h-52 overflow-hidden">
                                            <img src="{{ !empty($item['image']) ? (Str::startsWith($item['image'], 'http') ? $item['image'] : asset('storage/' . $item['image'])) : 'https://images.unsplash.com/photo-1544025162-8111f440536d?auto=format&fit=crop&q=80' }}"
                                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                            <div
                                                class="absolute inset-0 bg-gradient-to-t from-[var(--bg_card)] to-transparent">
                                            </div>
                                            @if (!empty($item['badge']))
                                                <span
                                                    class="absolute top-3 right-3 bg-[var(--accent)] text-[var(--text_primary)] text-xs font-bold uppercase px-3 py-1 rounded-full shadow-lg">
                                                    {{ $item['badge'] }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="p-5">
                                            <h3 class="text-[var(--text_primary)] font-['Anton'] tracking-wider text-xl mb-1 uppercase">
                                                {{ $item['name'] ?? '' }}</h3>
                                            @if (!empty($item['description']))
                                                <p class="text-[var(--text_muted)] text-sm">{{ $item['description'] }}</p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-16">
                                <p class="text-6xl mb-4">🔥</p>
                                <p class="text-[var(--text_muted)] italic text-lg">Muy pronto más ahumados increíbles.</p>
                            </div>
                        @endif
                    </div>
                </section>

                {{-- ═══════ TAQUERÍA SECTION ═══════ --}}
            @elseif($block['type'] === 'taqueria_section')
                <section class="relative py-28 overflow-hidden" id="taqueria">
                    @if (!empty($block['data']['background_image']))
                        <div class="absolute inset-0">
                            <img src="{{ asset('storage/' . $block['data']['background_image']) }}"
                                class="w-full h-full object-cover opacity-25">
                            <div
                                class="absolute inset-0 bg-gradient-to-b from-[#1a0e00]/90 via-[#0f0800]/80 to-[var(--bg_primary)]/95">
                            </div>
                        </div>
                    @else
                        <div class="absolute inset-0 bg-gradient-to-br from-[#1a0e00] via-[#120900] to-[var(--bg_primary)]"></div>
                    @endif

                    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-8 mb-16"
                            x-data="{ shown: false }" x-intersect.once="shown = true"
                            :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'"
                            class="transition-all duration-1000 transform">
                            <div>
                                <div class="inline-flex items-center gap-3 mb-4">
                                    <div class="h-px w-12 bg-[#f97316]"></div>
                                    <span class="text-[#f97316] uppercase tracking-[0.3em] text-sm font-bold">🌙
                                        Nocturno</span>
                                    <div class="h-px w-12 bg-[#f97316]"></div>
                                </div>
                                <h2
                                    class="text-4xl md:text-6xl font-['Anton'] text-[var(--text_primary)] uppercase tracking-widest mb-3 drop-shadow-2xl">
                                    {{ $block['data']['heading'] ?? siteName() }}
                                </h2>
                                <p class="text-[#f97316] text-xl font-semibold italic">
                                    {{ $block['data']['subheading'] ?? 'De noche, somos taquería.' }}</p>
                                @if (!empty($block['data']['description']))
                                    <p class="text-[var(--text_secondary)] mt-4 max-w-xl text-base whitespace-pre-line">
                                        {{ $block['data']['description'] }}</p>
                                @endif
                            </div>
                            <div class="shrink-0">
                                <div
                                    class="border-2 border-[#f97316] rounded-2xl px-8 py-5 text-center bg-[#f97316]/10 backdrop-blur-sm shadow-[0_0_30px_rgba(249,115,22,0.15)]">
                                    <span
                                        class="block text-[#f97316] font-bold uppercase tracking-widest text-xs mb-1">Horario</span>
                                    <span class="block text-[var(--text_primary)] font-['Anton'] text-xl tracking-wide">
                                        {{ $block['data']['schedule'] ?? 'Lun – Sáb | 7pm – 12am' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        @if (!empty($block['data']['tacos']))
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                                @foreach ($block['data']['tacos'] as $i => $taco)
                                    <div x-data="{ shown: false }" x-intersect.once="shown = true"
                                        :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'"
                                        class="transition-all duration-700 group relative bg-[#1a1000] rounded-2xl overflow-hidden border border-[#2a1e00] hover:border-[#f97316] shadow-2xl hover:-translate-y-2 hover:shadow-[0_0_30px_rgba(249,115,22,0.2)]"
                                        style="transition-delay: {{ $i * 100 }}ms">
                                        <div class="relative h-52 overflow-hidden">
                                            <img src="{{ !empty($taco['image']) ? (Str::startsWith($taco['image'], 'http') ? $taco['image'] : asset('storage/' . $taco['image'])) : 'https://images.unsplash.com/photo-1565299585323-38d6b0865b47?auto=format&fit=crop&q=80' }}"
                                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 brightness-90 group-hover:brightness-110">
                                            <div
                                                class="absolute inset-0 bg-gradient-to-t from-[#1a1000] to-transparent">
                                            </div>
                                        </div>
                                        <div class="p-5">
                                            <h3 class="text-[var(--text_primary)] font-['Anton'] tracking-wider text-xl mb-1 uppercase">
                                                {{ $taco['name'] ?? '' }}</h3>
                                            @if (!empty($taco['description']))
                                                <p class="text-orange-200/60 text-sm mb-3">{{ $taco['description'] }}
                                                </p>
                                            @endif
                                            @if (!empty($taco['price']))
                                                <span
                                                    class="inline-block bg-[#f97316]/20 border border-[#f97316]/40 text-[#f97316] text-sm font-bold px-3 py-1 rounded-full">
                                                    {{ $taco['price'] }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-16">
                                <p class="text-6xl mb-4">🌮</p>
                                <p class="text-[var(--text_muted)] italic text-lg">Muy pronto nuestro menú nocturno.</p>
                            </div>
                        @endif
                    </div>
                </section>

                {{-- ═══════ AHUMADO SECTION (video + mosaico) ═══════ --}}
            @elseif($block['type'] === 'ahumado_section')
                <section class="py-24 bg-[var(--bg_primary)] relative overflow-hidden" id="ahumados">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="text-center mb-16" x-data="{ shown: false }" x-intersect.once="shown = true"
                            class="transition-all duration-1000 transform"
                            :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'">
                            <h2 class="text-4xl md:text-5xl font-['Anton'] text-[var(--text_primary)] uppercase tracking-widest mb-4">
                                {{ $block['data']['heading'] ?? '' }}
                            </h2>
                            <div class="h-1 w-24 bg-[#18833b] mx-auto rounded mb-6"></div>
                            <p class="text-[var(--text_secondary)] max-w-3xl mx-auto text-lg whitespace-pre-line">
                                {{ $block['data']['description'] ?? '' }}</p>
                        </div>

                        <div x-data="{ shown: false }" x-intersect.once="shown = true"
                            class="relative rounded-xl overflow-hidden shadow-2xl border-2 border-[var(--border)] aspect-video bg-black mb-10 transition-all duration-1000 transform"
                            :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'">
                            @if (!empty($block['data']['video_url']))
                                <div class="video-container absolute inset-0 w-full h-full">
                                    {!! $block['data']['video_url'] !!}
                                </div>
                                <style>
                                    .video-container iframe {
                                        width: 100% !important;
                                        height: 100% !important;
                                        position: absolute;
                                        top: 0;
                                        left: 0;
                                    }
                                </style>
                            @endif
                        </div>

                        @php
                            $mosaic = $block['data']['mosaic_items'] ?? [];
                        @endphp
                        <div x-data="{ shown: false }" x-intersect.once="shown = true"
                            class="grid grid-cols-2 md:grid-cols-3 gap-4 transition-all duration-1000 transform"
                            :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'">
                            @foreach($mosaic as $index => $item)
                                @php
                                    $img = $item['image'] ?? '';
                                    $borderColors = ['border-[var(--border)]', 'border-[#E52B2B]', 'border-[#18833b]'];
                                    $border = $borderColors[$index % 3] ?? 'border-[var(--border)]';
                                @endphp
                                @if($img)
                                    <div>
<img src="{{ Str::startsWith($img, 'http') ? $img : asset('storage/' . $img) }}"
                                             class="w-full aspect-video md:aspect-square object-cover rounded-xl border-2 {{ $border }} shadow-xl opacity-90 hover:opacity-100 hover:scale-[1.02] transition-all duration-300"
                                             onerror="this.closest('div').style.display='none'">
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </section>

                {{-- ═══════ CONTACT / MAP ═══════ --}}
            @elseif($block['type'] === 'contact_map_section')
                @php $__si = siteInfo(); @endphp
                <section class="py-20 bg-[var(--bg_section)]" id="location">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="flex flex-col lg:flex-row gap-8 lg:gap-12 items-start lg:items-center">
                            <div class="w-full lg:w-1/3">
                                <h2 class="text-3xl sm:text-4xl font-['Anton'] text-[var(--text_primary)] uppercase tracking-widest mb-6 border-l-4 border-[var(--accent)] pl-4">
                                    {{ $block['data']['heading'] ?? 'Encuéntranos' }}
                                </h2>
                                @if (!empty($block['data']['description']))
                                <p class="text-[var(--text_secondary)] mb-8 text-lg">{{ $block['data']['description'] }}</p>
                                @endif
                                <ul class="space-y-6">
                                    @if ($__si?->address)
                                    <li class="flex items-start gap-4 text-[var(--text_secondary)]">
                                        <svg class="w-6 h-6 shrink-0 text-[var(--accent)]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <div>
                                            <strong class="block text-[var(--text_primary)] mb-1 uppercase tracking-wider font-bold">Dirección:</strong>
                                            {{ $__si->address }}
                                        </div>
                                    </li>
                                    @endif
                                    @if ($__si?->phone)
                                    <li class="flex items-start gap-4 text-[var(--text_secondary)]">
                                        <svg class="w-6 h-6 shrink-0 text-[var(--accent)]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                        <div>
                                            <strong class="block text-[var(--text_primary)] mb-1 uppercase tracking-wider font-bold">Llámanos:</strong>
                                            {{ $__si->phone }}
                                        </div>
                                    </li>
                                    @endif
                                    @if ($__si?->whatsapp)
                                    <li class="flex items-start gap-4 text-[var(--text_secondary)]">
                                        <svg class="w-6 h-6 shrink-0 text-[var(--accent_green)]" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 0 0-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z" />
                                        </svg>
                                        <div>
                                            <strong class="block text-[var(--text_primary)] mb-1 uppercase tracking-wider font-bold">WhatsApp:</strong>
                                            <a href="https://wa.me/{{ $__si->whatsapp }}" target="_blank" class="text-[var(--accent_green)] hover:underline">
                                                {{ $__si->whatsapp }}
                                            </a>
                                        </div>
                                    </li>
                                    @endif
                                </ul>
                            </div>
                            <div class="w-full lg:w-2/3 h-64 sm:h-80 lg:h-96 rounded-xl overflow-hidden shadow-2xl border-4 border-[var(--border)]">
                                @if ($__si?->map_embed_url)
                                    {!! $__si->map_embed_url !!}
                                @else
                                    <div class="w-full h-full bg-[var(--bg_card)] flex items-center justify-center text-[var(--text_muted)]">
                                        (Mapa no configurado — agrega el iframe en Configuración General)
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </section>

                {{-- ═══════ ABOUT SECTION ═══════ --}}
            @elseif($block['type'] === 'about_section')
                <section class="py-24 bg-[var(--bg_section)] relative overflow-hidden" id="about">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                        <div class="flex flex-col lg:flex-row items-center gap-16">
                            <div class="lg:w-1/2">
                                <h2 class="text-4xl md:text-5xl font-['Anton'] text-[var(--text_primary)] uppercase tracking-widest mb-6">
                                    {{ $block['data']['heading'] ?? 'Nuestra Historia' }}
                                </h2>
                                <div class="prose prose-invert prose-lg text-[var(--text_secondary)]">
                                    <p class="mb-6 whitespace-pre-line">{{ $block['data']['description'] ?? '' }}</p>
                                </div>
                                @php $features = $block['data']['features'] ?? []; @endphp
                                @if(count($features) > 0)
                                <div class="mt-8 flex gap-4 flex-wrap">
                                    @foreach($features as $f)
                                    <div class="bg-black p-4 text-center rounded border border-[var(--border)] min-w-[120px]">
                                        @if(!empty($f['icon']))
                                        <div class="mb-2 text-[var(--accent)]">
                                            @if($f['icon'] === 'custom-wave')
                                            <svg class="w-8 h-8 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2 16c1.5-2 3.5-2 5 0s3.5 2 5 0 3.5-2 5 0 3.5 2 5 0M2 10c1.5-2 3.5-2 5 0s3.5 2 5 0 3.5-2 5 0 3.5 2 5 0"/>
                                            </svg>
                                            @elseif($f['icon'] === 'custom-chef')
                                            <svg class="w-8 h-8 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.5 12.5a3.5 3.5 0 117 0c0 .34-.03.67-.09 1H18a2 2 0 012 2v1a1 1 0 01-1 1H5a1 1 0 01-1-1v-1a2 2 0 012-2h1.09c-.06-.33-.09-.66-.09-1z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 20.5h10M12 8V5"/>
                                            </svg>
                                            @elseif(\Illuminate\Support\Str::startsWith($f['icon'], 'heroicon-'))
                                            @svg($f['icon'], 'w-8 h-8 mx-auto')
                                            @else
                                            <span class="text-3xl">{{ $f['icon'] }}</span>
                                            @endif
                                        </div>
                                        @endif
                                        <span class="text-xs uppercase tracking-wider font-bold text-[var(--text_muted)]">{{ $f['title'] ?? '' }}</span>
                                    </div>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                            <div class="lg:w-1/2 relative">
                                <div
                                    class="absolute -inset-4 bg-[var(--accent)] rounded-xl transform rotate-3 opacity-20 blur-lg">
                                </div>
                                <img src="{{ !empty($block['data']['image']) ? asset('storage/' . $block['data']['image']) : 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&q=80' }}"
                                    class="relative rounded-xl shadow-[0_0_30px_rgba(0,0,0,0.8)] border border-[var(--border)] filter contrast-125 w-full object-cover aspect-square"
                                    onerror="this.src='https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&q=80'">
                            </div>
                        </div>
                    </div>
                </section>

                {{-- ═══════ FEATURED PRODUCTS ═══════ --}}
            @elseif($block['type'] === 'featured_products')
                @if ($featuredProducts->count() > 0)
                    <section class="py-20 bg-[var(--bg_primary)] scroll-mt-24" id="destacados">
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                            <div class="text-center mb-16">
                                <h2
                                    class="text-4xl md:text-5xl font-['Anton'] text-[var(--text_primary)] uppercase tracking-widest mb-4 inline-block border-b-4 border-[#18833b] pb-2">
                                    {{ $block['data']['heading'] ?? 'Nuestras Especialidades' }}
                                </h2>
                                <p class="text-[var(--text_secondary)]">Los cortes y platillos favoritos de la casa.</p>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                                @foreach ($featuredProducts as $product)
                                    <div
                                        class="group bg-gradient-to-b from-[var(--bg_card)] to-[#1a1a1a] rounded-xl overflow-hidden shadow-2xl border border-[var(--border)] hover:border-[var(--border_hover)] transition-all duration-300 transform hover:-translate-y-2">
                                        <div class="relative h-64 overflow-hidden">
                                            <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=800&q=80' }}"
                                                alt="{{ $product->name }}"
                                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                                                onerror="this.src='https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=800&q=80'">
                                        </div>
                                        <div class="p-6">
                                            <div
                                                class="text-xs text-[var(--accent_green)] uppercase tracking-wider font-bold mb-2">
                                                {{ $product->category?->name ?? 'Especial' }}
                                            </div>
                                            <h3 class="text-2xl font-['Anton'] tracking-wider text-[var(--text_primary)] mb-3">
                                                {{ $product->name }}</h3>
                                            <p class="text-[var(--text_secondary)] text-sm">
                                                {{ $product->description ?? 'Una deliciosa preparación al estilo BBQ tradicional.' }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </section>
                @endif

                {{-- ═══════ REVIEWS ═══════ --}}
            @elseif($block['type'] === 'reviews_section')
                <section class="py-20 bg-black relative" id="reviews">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="text-center mb-16">
                            <h2 class="text-4xl md:text-5xl font-['Anton'] text-[var(--text_primary)] uppercase tracking-widest mb-4">
                                {{ $block['data']['heading'] ?? 'Lo Que Dicen' }}
                            </h2>
                            <div class="h-1 w-24 bg-[#18833b] mx-auto rounded"></div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                            @foreach ($reviews as $review)
                                <div x-data="{ shown: false }" x-intersect.once="shown = true"
                                    class="bg-[var(--bg_primary)] p-8 rounded-xl border border-[var(--border)] relative transition-all duration-1000 transform"
                                    :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'">
                                    <span
                                        class="text-6xl text-[var(--accent)] font-serif absolute top-4 right-4 opacity-20">"</span>
                                    <div class="flex items-center gap-1 mb-4 text-[var(--accent_green)]">
                                        @for ($i = 0; $i < $review->rating; $i++)
                                            <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        @endfor
                                    </div>
                                    <p class="text-[var(--text_secondary)] italic mb-6">"{{ $review->comment }}"</p>
                                    <div class="font-bold text-[var(--text_primary)] uppercase tracking-wider text-sm">
                                        {{ $review->customer_name }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>

                {{-- ═══════ PROMOTIONS CAROUSEL ═══════ --}}
            @elseif($block['type'] === 'promotions_carousel')
                <section class="py-20 bg-black relative" id="promotions">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="text-center mb-12">
                            <h2
                                class="text-4xl md:text-5xl font-['Anton'] text-[var(--text_primary)] uppercase tracking-widest mb-4 inline-block border-b-4 border-[#18833b] pb-2">
                                {{ $block['data']['heading'] ?? 'Promociones' }}
                            </h2>
                        </div>
                        <div x-data="{
                            activeSlide: 0,
                            slides: {{ count($block['data']['items'] ?? []) }},
                            next() { this.activeSlide = this.activeSlide === this.slides - 1 ? 0 : this.activeSlide + 1 },
                            prev() { this.activeSlide = this.activeSlide === 0 ? this.slides - 1 : this.activeSlide - 1 },
                            autoPlayInterval: null,
                            startAutoPlay() { this.autoPlayInterval = setInterval(() => this.next(), 4000); },
                            stopAutoPlay() { clearInterval(this.autoPlayInterval); }
                        }" x-init="if (slides > 1) startAutoPlay()" @mouseenter="stopAutoPlay"
                            @mouseleave="if(slides > 1) startAutoPlay()"
                            class="relative w-full max-w-5xl mx-auto group pb-10">
                            @if (count($block['data']['items'] ?? []) > 0)
                                <div
                                    class="overflow-hidden rounded-2xl border-2 border-[var(--border)] shadow-2xl relative bg-[var(--bg_primary)]">
                                    <div class="flex transition-transform duration-700 ease-out"
                                        :style="'transform: translateX(-' + (activeSlide * 100) + '%)'">
                                        @foreach ($block['data']['items'] ?? [] as $item)
                                            <div class="w-full shrink-0 relative aspect-[21/9] cursor-pointer">
                                                @if (!empty($item['link']))
                                                    <a href="{{ $item['link'] }}" class="absolute inset-0 z-20"></a>
                                                @endif
                                                <img src="{{ Str::startsWith($item['image'], 'http') ? $item['image'] : asset('storage/' . $item['image']) }}"
                                                    class="w-full h-full object-cover"
                                                    onerror="this.src='https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&q=80'">
                                                @if (!empty($item['title']))
                                                    <div
                                                        class="absolute inset-0 bg-gradient-to-t from-[var(--bg_primary)]/90 via-black/40 to-transparent flex items-end p-8">
                                                        <h3
                                                            class="text-3xl font-bold font-['Anton'] tracking-wider text-[var(--text_primary)] uppercase drop-shadow-lg">
                                                            {{ $item['title'] }}</h3>
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                @if (count($block['data']['items']) > 1)
                                    <button @click="prev"
                                        class="absolute left-2 sm:left-4 top-1/2 -translate-y-1/2 w-10 h-10 sm:w-12 sm:h-12 bg-black/60 hover:bg-[var(--accent)] text-[var(--text_primary)] rounded-full flex items-center justify-center transition-all backdrop-blur z-30 shadow-xl border border-[var(--border_light)] sm:opacity-0 sm:group-hover:opacity-100 opacity-80">
                                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M15 19l-7-7 7-7"></path>
                                        </svg>
                                    </button>
                                    <button @click="next"
                                        class="absolute right-2 sm:right-4 top-1/2 -translate-y-1/2 w-10 h-10 sm:w-12 sm:h-12 bg-black/60 hover:bg-[var(--accent)] text-[var(--text_primary)] rounded-full flex items-center justify-center transition-all backdrop-blur z-30 shadow-xl border border-[var(--border_light)] sm:opacity-0 sm:group-hover:opacity-100 opacity-80">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </button>
                                    <div class="absolute pt-4 text-center w-full flex justify-center space-x-3">
                                        <template x-for="i in slides">
                                            <button @click="activeSlide = i - 1"
                                                class="w-2 h-2 rounded-sm transition-all duration-300 shadow-md"
                                                :class="activeSlide === i - 1 ? 'bg-[var(--accent)] w-3 h-3' :
                                                    'bg-gray-600 hover:bg-gray-400'"></button>
                                        </template>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </section>
            @endif

            {{-- ═══════ FORMULARIO DE CONTACTO ═══════ --}}
            @if ($block['type'] === 'contact_form')
                <section class="py-24 bg-[var(--bg_primary)]" id="contacto-form">

                    <div class="relative max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
                        {{-- Header --}}
                        <div class="text-center mb-12" x-data="{ shown: false }" x-intersect.once="shown = true"
                            class="transition-all duration-1000 transform"
                            :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'">
                            <h2
                                class="text-4xl md:text-5xl font-['Anton'] text-[var(--text_primary)] uppercase tracking-widest mb-4 drop-shadow-xl">
                                {{ $block['data']['heading'] }}
                            </h2>
                            <div class="h-1 w-20 bg-[var(--accent)] mx-auto rounded mb-4"></div>
                            @if (!empty($block['data']['description']))
                                <p class="text-[var(--text_secondary)] text-lg max-w-xl mx-auto">{{ $block['data']['description'] }}
                                </p>
                            @endif
                        </div>

                        {{-- Form Card --}}
                        <div class="bg-[var(--bg_primary)] border border-[var(--border)] rounded-2xl p-8 md:p-10 shadow-2xl">
                            @livewire('contact-form', ['blockData' => $block['data'], 'pageSlug' => $page->slug ?? ''], key('form-' . $loop->index))
                        </div>
                    </div>
                </section>
            @endif

            {{-- ═══════ BANNER CTA ═══════ --}}
            @if ($block['type'] === 'cta_banner')
                @php
                    $bg   = $block['data']['bg_color']   ?? '#E52B2B';
                    $tc   = $block['data']['text_color'] ?? '#ffffff';
                @endphp
                <section class="py-20 px-4" style="background-color: {{ $bg }};">
                    <div class="max-w-4xl mx-auto text-center" x-data="{ shown: false }" x-intersect.once="shown = true"
                        :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
                        class="transition-all duration-700">
                        <h2 class="text-4xl md:text-5xl font-['Anton'] uppercase tracking-widest mb-4"
                            style="color: {{ $tc }}">
                            {{ $block['data']['heading'] ?? '' }}
                        </h2>
                        @if (!empty($block['data']['text']))
                            <p class="text-lg mb-8 opacity-90" style="color: {{ $tc }}">
                                {{ $block['data']['text'] }}
                            </p>
                        @endif
                        @if (!empty($block['data']['button_label']) && !empty($block['data']['button_url']))
                            <a href="{{ $block['data']['button_url'] }}"
                                @if(!empty($block['data']['button_new_tab'])) target="_blank" rel="noopener" @endif
                                class="inline-block font-bold uppercase tracking-widest px-10 py-4 rounded-full border-2 transition-all duration-300 hover:scale-105"
                                style="color: {{ $bg }}; background-color: {{ $tc }}; border-color: {{ $tc }};">
                                {{ $block['data']['button_label'] }}
                            </a>
                        @endif
                    </div>
                </section>
            @endif

            {{-- ═══════ FAQ ═══════ --}}
            @if ($block['type'] === 'faq')
                <section class="py-24 bg-[var(--bg_primary)]">
                    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
                        @if (!empty($block['data']['heading']))
                            <div class="text-center mb-12">
                                <h2 class="text-4xl md:text-5xl font-['Anton'] text-[var(--text_primary)] uppercase tracking-widest mb-4">
                                    {{ $block['data']['heading'] }}
                                </h2>
                                <div class="h-1 w-20 bg-[var(--accent)] mx-auto rounded"></div>
                            </div>
                        @endif
                        @if (!empty($block['data']['intro']))
                            <p class="text-[var(--text_secondary)] text-center mb-10">{{ $block['data']['intro'] }}</p>
                        @endif
                        <div class="space-y-3" x-data="{ open: null }">
                            @foreach ($block['data']['items'] ?? [] as $i => $item)
                                <div class="border border-[var(--border_light)] rounded-xl overflow-hidden">
                                    <button @click="open === {{ $i }} ? open = null : open = {{ $i }}"
                                        class="w-full flex items-center justify-between px-6 py-4 text-left text-[var(--text_primary)] font-semibold bg-[var(--bg_card)] hover:bg-[var(--bg_card)] transition-colors">
                                        <span>{{ $item['question'] ?? '' }}</span>
                                        <svg class="w-5 h-5 text-[var(--accent)] shrink-0 transition-transform duration-300"
                                            :class="open === {{ $i }} ? 'rotate-180' : ''"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </button>
                                    <div x-show="open === {{ $i }}" x-collapse class="px-6 py-4 bg-[var(--bg_primary)] text-[var(--text_secondary)]">
                                        {{ $item['answer'] ?? '' }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>
            @endif

            {{-- ═══════ PRICING ═══════ --}}
            @if ($block['type'] === 'pricing')
                <section class="py-24 bg-[var(--bg_section)]">
                    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                        @if (!empty($block['data']['heading']))
                            <div class="text-center mb-12">
                                <h2 class="text-4xl md:text-5xl font-['Anton'] text-[var(--text_primary)] uppercase tracking-widest mb-4">
                                    {{ $block['data']['heading'] }}
                                </h2>
                                <div class="h-1 w-20 bg-[var(--accent)] mx-auto rounded"></div>
                            </div>
                        @endif
                        @if (!empty($block['data']['intro']))
                            <p class="text-[var(--text_secondary)] text-center mb-12">{{ $block['data']['intro'] }}</p>
                        @endif
                        <div class="grid grid-cols-1 md:grid-cols-{{ count($block['data']['plans'] ?? []) > 2 ? '3' : '2' }} gap-8">
                            @foreach ($block['data']['plans'] ?? [] as $plan)
                                @php $hl = !empty($plan['highlighted']); $accent = $plan['accent_color'] ?? '#E52B2B'; @endphp
                                <div class="relative flex flex-col rounded-2xl border p-6 sm:p-8 transition-transform hover:-translate-y-1 duration-300
                                    {{ $hl ? 'border-[#E52B2B] bg-[var(--bg_card)] shadow-2xl sm:scale-105' : 'border-[var(--border_light)] bg-[var(--bg_card)]' }}">
                                    @if ($hl)
                                        <span class="absolute -top-3 left-1/2 -translate-x-1/2 text-xs font-bold uppercase tracking-widest px-4 py-1 rounded-full text-[var(--text_primary)]" style="background:{{ $accent }}">
                                            Recomendado
                                        </span>
                                    @endif
                                    <h3 class="text-xl font-bold text-[var(--text_primary)] mb-2">{{ $plan['name'] ?? '' }}</h3>
                                    <div class="text-4xl font-['Anton'] mb-4" style="color:{{ $accent }}">{{ $plan['price'] ?? '' }}</div>
                                    @if (!empty($plan['description']))
                                        <p class="text-[var(--text_secondary)] text-sm mb-6">{{ $plan['description'] }}</p>
                                    @endif
                                    @if (!empty($plan['features']))
                                        <ul class="space-y-2 mb-8 flex-1">
                                            @foreach (explode("\n", $plan['features']) as $feat)
                                                @if (trim($feat))
                                                    <li class="flex items-start gap-2 text-[var(--text_secondary)] text-sm">
                                                        <svg class="w-4 h-4 mt-0.5 shrink-0" style="color:{{ $accent }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                        </svg>
                                                        {{ trim($feat) }}
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    @endif
                                    @if (!empty($plan['button_label']))
                                        <a href="{{ $plan['button_url'] ?? '#' }}"
                                            class="block text-center font-bold uppercase tracking-widest py-3 px-6 rounded-full transition-all duration-300 hover:opacity-90"
                                            style="background:{{ $accent }}; color:#fff;">
                                            {{ $plan['button_label'] }}
                                        </a>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>
            @endif

            {{-- ═══════ TEAM ═══════ --}}
            @if ($block['type'] === 'team')
                <section class="py-24 bg-[var(--bg_primary)]">
                    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                        @if (!empty($block['data']['heading']))
                            <div class="text-center mb-12">
                                <h2 class="text-4xl md:text-5xl font-['Anton'] text-[var(--text_primary)] uppercase tracking-widest mb-4">
                                    {{ $block['data']['heading'] }}
                                </h2>
                                <div class="h-1 w-20 bg-[var(--accent)] mx-auto rounded"></div>
                            </div>
                        @endif
                        @if (!empty($block['data']['intro']))
                            <p class="text-[var(--text_secondary)] text-center mb-12">{{ $block['data']['intro'] }}</p>
                        @endif
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                            @foreach ($block['data']['members'] ?? [] as $member)
                                <div class="bg-[var(--bg_card)] border border-[var(--border)] rounded-2xl overflow-hidden hover:border-[var(--accent)] transition-all duration-300 group">
                                    @if (!empty($member['photo']))
                                        <div class="aspect-square overflow-hidden">
                                            <img src="{{ asset('storage/' . $member['photo']) }}"
                                                alt="{{ $member['name'] ?? '' }}"
                                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                                onerror="this.style.display='none'">
                                        </div>
                                    @else
                                        <div class="aspect-square bg-[var(--bg_card)] flex items-center justify-center">
                                            <svg class="w-20 h-20 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                        </div>
                                    @endif
                                    <div class="p-6">
                                        <h3 class="text-[var(--text_primary)] font-bold text-lg">{{ $member['name'] ?? '' }}</h3>
                                        <p class="text-[var(--accent)] text-sm font-semibold uppercase tracking-wider mb-3">{{ $member['role'] ?? '' }}</p>
                                        @if (!empty($member['bio']))
                                            <p class="text-[var(--text_secondary)] text-sm mb-4">{{ $member['bio'] }}</p>
                                        @endif
                                        <div class="flex gap-3">
                                            @foreach (['linkedin' => 'M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6zM2 9h4v12H2z M4 6a2 2 0 100-4 2 2 0 000 4z', 'twitter' => 'M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z', 'instagram' => 'M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37zM17.5 6.5h.01 M7 2h10a5 5 0 015 5v10a5 5 0 01-5 5H7a5 5 0 01-5-5V7a5 5 0 015-5z'] as $net => $path)
                                                @if (!empty($member[$net]))
                                                    <a href="{{ $member[$net] }}" target="_blank" rel="noopener"
                                                        class="text-[var(--text_muted)] hover:text-[var(--text_primary)] transition-colors">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $path }}"/>
                                                        </svg>
                                                    </a>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>
            @endif

            {{-- ═══════ FEATURES ═══════ --}}
            @if ($block['type'] === 'features')
                @php $cols = $block['data']['columns'] ?? 3; @endphp
                <section class="py-24 bg-[var(--bg_section)]">
                    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                        @if (!empty($block['data']['heading']))
                            <div class="text-center mb-12">
                                <h2 class="text-4xl md:text-5xl font-['Anton'] text-[var(--text_primary)] uppercase tracking-widest mb-4">
                                    {{ $block['data']['heading'] }}
                                </h2>
                                <div class="h-1 w-20 bg-[var(--accent)] mx-auto rounded"></div>
                            </div>
                        @endif
                        @if (!empty($block['data']['intro']))
                            <p class="text-[var(--text_secondary)] text-center mb-12">{{ $block['data']['intro'] }}</p>
                        @endif
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-{{ $cols }} gap-8">
                            @foreach ($block['data']['items'] ?? [] as $feat)
                                @php $ic = $feat['icon_color'] ?? '#E52B2B'; @endphp
                                <div class="bg-[var(--bg_primary)] border border-[var(--border)] rounded-2xl p-6 hover:border-[var(--accent)] transition-all duration-300"
                                    x-data="{ shown: false }" x-intersect.once="shown = true"
                                    :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
                                    class="transition-all duration-500">
                                    @if (!empty($feat['icon']))
                                        <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-4" style="background: {{ $ic }}22">
                                            <x-dynamic-component :component="$feat['icon']" class="w-6 h-6" style="color: {{ $ic }}" />
                                        </div>
                                    @endif
                                    <h3 class="text-[var(--text_primary)] font-bold text-lg mb-2">{{ $feat['title'] ?? '' }}</h3>
                                    <p class="text-[var(--text_secondary)] text-sm leading-relaxed">{{ $feat['description'] ?? '' }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>
            @endif

            {{-- ═══════ TESTIMONIALS ═══════ --}}
            @if ($block['type'] === 'testimonials')
                <section class="py-24 bg-[var(--bg_primary)]">
                    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                        @if (!empty($block['data']['heading']))
                            <div class="text-center mb-12">
                                <h2 class="text-4xl md:text-5xl font-['Anton'] text-[var(--text_primary)] uppercase tracking-widest mb-4">
                                    {{ $block['data']['heading'] }}
                                </h2>
                                <div class="h-1 w-20 bg-[var(--accent)] mx-auto rounded"></div>
                            </div>
                        @endif
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($block['data']['items'] ?? [] as $t)
                                <div class="bg-[var(--bg_card)] border border-[var(--border)] rounded-2xl p-6 flex flex-col gap-4 hover:border-[var(--accent)] transition-all duration-300">
                                    <div class="flex gap-0.5">
                                        @for ($s = 0; $s < ($t['stars'] ?? 5); $s++)
                                            <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        @endfor
                                    </div>
                                    <p class="text-[var(--text_secondary)] italic flex-1">"{{ $t['quote'] ?? '' }}"</p>
                                    <div class="flex items-center gap-3 mt-auto pt-4 border-t border-[var(--border)]">
                                        @if (!empty($t['photo']))
                                            <img src="{{ asset('storage/' . $t['photo']) }}" alt="{{ $t['author'] ?? '' }}"
                                                 class="w-10 h-10 rounded-full object-cover"
                                                 onerror="this.style.display='none'">
                                        @else
                                            <div class="w-10 h-10 rounded-full bg-[var(--accent)] flex items-center justify-center text-[var(--text_primary)] font-bold text-sm">
                                                {{ strtoupper(substr($t['author'] ?? '?', 0, 1)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <p class="text-[var(--text_primary)] font-semibold text-sm">{{ $t['author'] ?? '' }}</p>
                                            @if (!empty($t['role']))
                                                <p class="text-[var(--text_muted)] text-xs">{{ $t['role'] }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>
            @endif

            {{-- ═══════ GALERÍA ═══════ --}}
            @if ($block['type'] === 'gallery')
                @php
                    $gcols    = $block['data']['columns']  ?? 3;
                    $lightbox = !empty($block['data']['lightbox']);
                    $gIdx     = $loop->index;
                @endphp
                <section class="py-24 bg-[var(--bg_section)]">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        @if (!empty($block['data']['heading']))
                            <div class="text-center mb-12">
                                <h2 class="text-4xl md:text-5xl font-['Anton'] text-[var(--text_primary)] uppercase tracking-widest mb-4">
                                    {{ $block['data']['heading'] }}
                                </h2>
                                <div class="h-1 w-20 bg-[var(--accent)] mx-auto rounded"></div>
                            </div>
                        @endif
                        @if ($lightbox)
                            <div x-data="{ lightboxOpen: false, lightboxSrc: '' }">
                                <div class="grid grid-cols-2 md:grid-cols-{{ $gcols }} gap-3">
                                    @foreach ($block['data']['images'] ?? [] as $img)
                                        <div class="overflow-hidden rounded-xl cursor-pointer group"
                                            @click="lightboxSrc = '{{ asset('storage/' . $img['src']) }}'; lightboxOpen = true">
                                            <img src="{{ asset('storage/' . $img['src']) }}"
                                                alt="{{ $img['alt'] ?? $img['caption'] ?? '' }}"
                                                class="w-full aspect-square object-cover group-hover:scale-105 transition-transform duration-500"
                                                onerror="this.src='https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&q=80'">
                                            @if (!empty($img['caption']))
                                                <p class="text-center text-[var(--text_muted)] text-xs mt-1">{{ $img['caption'] }}</p>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                                {{-- Lightbox --}}
                                <div x-show="lightboxOpen" x-cloak
                                    class="fixed inset-0 z-50 bg-black/90 flex items-center justify-center p-4"
                                    @click.self="lightboxOpen = false" @keydown.escape.window="lightboxOpen = false">
                                    <button @click="lightboxOpen = false" class="absolute top-4 right-4 text-[var(--text_primary)]/70 hover:text-[var(--text_primary)]">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                    <img :src="lightboxSrc" class="max-h-[90vh] max-w-full rounded-xl shadow-2xl">
                                </div>
                            </div>
                        @else
                            <div class="grid grid-cols-2 md:grid-cols-{{ $gcols }} gap-3">
                                @foreach ($block['data']['images'] ?? [] as $img)
                                    <div class="overflow-hidden rounded-xl group">
                                        <img src="{{ asset('storage/' . $img['src']) }}"
                                            alt="{{ $img['alt'] ?? $img['caption'] ?? '' }}"
                                            class="w-full aspect-square object-cover group-hover:scale-105 transition-transform duration-500"
                                            onerror="this.src='https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&q=80'">
                                        @if (!empty($img['caption']))
                                            <p class="text-center text-[var(--text_muted)] text-xs mt-1">{{ $img['caption'] }}</p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </section>
            @endif

            {{-- ═══════ VIDEO EMBED ═══════ --}}
            @if ($block['type'] === 'video_embed')
                @php
                    $rawUrl  = $block['data']['video_url'] ?? '';
                    // Convertir URL normal de YouTube/Vimeo a embed
                    if (str_contains($rawUrl, 'youtube.com/watch?v=')) {
                        $vid    = parse_url($rawUrl, PHP_URL_QUERY);
                        parse_str($vid, $qp);
                        $embedUrl = 'https://www.youtube.com/embed/' . ($qp['v'] ?? '');
                    } elseif (str_contains($rawUrl, 'youtu.be/')) {
                        $embedUrl = 'https://www.youtube.com/embed/' . basename(parse_url($rawUrl, PHP_URL_PATH));
                    } elseif (str_contains($rawUrl, 'vimeo.com/')) {
                        $embedUrl = 'https://player.vimeo.com/video/' . basename(parse_url($rawUrl, PHP_URL_PATH));
                    } else {
                        $embedUrl = $rawUrl;
                    }
                    if (!empty($block['data']['autoplay'])) {
                        $embedUrl .= (str_contains($embedUrl, '?') ? '&' : '?') . 'autoplay=1&mute=1';
                    }
                @endphp
                <section class="py-24 bg-[var(--bg_primary)]">
                    <div class="{{ !empty($block['data']['full_width']) ? 'max-w-6xl' : 'max-w-3xl' }} mx-auto px-4 sm:px-6 lg:px-8">
                        @if (!empty($block['data']['heading']))
                            <div class="text-center mb-10">
                                <h2 class="text-4xl md:text-5xl font-['Anton'] text-[var(--text_primary)] uppercase tracking-widest mb-4">
                                    {{ $block['data']['heading'] }}
                                </h2>
                                <div class="h-1 w-20 bg-[var(--accent)] mx-auto rounded"></div>
                            </div>
                        @endif
                        @if (!empty($block['data']['description']))
                            <p class="text-[var(--text_secondary)] text-center mb-8">{{ $block['data']['description'] }}</p>
                        @endif
                        <div class="relative rounded-2xl overflow-hidden shadow-2xl" style="aspect-ratio:16/9;">
                            <iframe src="{{ $embedUrl }}"
                                class="absolute inset-0 w-full h-full"
                                frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen>
                            </iframe>
                        </div>
                    </div>
                </section>
            @endif

            {{-- ═══════ STATS ═══════ --}}
            @if ($block['type'] === 'stats')
                @php $statBg = $block['data']['bg_color'] ?? '#1c1c1c'; @endphp
                <section class="py-20" style="background-color: {{ $statBg }}">
                    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                        @if (!empty($block['data']['heading']))
                            <div class="text-center mb-12">
                                <h2 class="text-4xl md:text-5xl font-['Anton'] text-[var(--text_primary)] uppercase tracking-widest mb-4">
                                    {{ $block['data']['heading'] }}
                                </h2>
                                <div class="h-1 w-20 bg-[var(--accent)] mx-auto rounded"></div>
                            </div>
                        @endif
                        <div class="grid grid-cols-2 md:grid-cols-{{ min(4, count($block['data']['items'] ?? [])) }} gap-8 text-center">
                            @foreach ($block['data']['items'] ?? [] as $stat)
                                @php $vc = $stat['value_color'] ?? '#E52B2B'; @endphp
                                <div x-data="{ shown: false }" x-intersect.once="shown = true"
                                    :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
                                    class="transition-all duration-700">
                                    @if (!empty($stat['icon']))
                                        <div class="flex justify-center mb-3">
                                            <x-dynamic-component :component="$stat['icon']" class="w-8 h-8" style="color: {{ $vc }}" />
                                        </div>
                                    @endif
                                    <div class="text-5xl font-['Anton'] mb-2" style="color: {{ $vc }}">
                                        {{ $stat['value'] ?? '' }}
                                    </div>
                                    <div class="text-[var(--text_secondary)] font-medium uppercase tracking-wider text-sm">
                                        {{ $stat['label'] ?? '' }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>
            @endif

            {{-- ═══════ RICH TEXT ═══════ --}}
            @if ($block['type'] === 'rich_text')
                @php $mw = $block['data']['max_width'] ?? 'max-w-4xl'; @endphp
                <section class="py-24 bg-[var(--bg_primary)]">
                    <div class="{{ $mw }} mx-auto px-4 sm:px-6 lg:px-8">
                        @if (!empty($block['data']['heading']))
                            <div class="text-center mb-10">
                                <h2 class="text-4xl md:text-5xl font-['Anton'] text-[var(--text_primary)] uppercase tracking-widest mb-4">
                                    {{ $block['data']['heading'] }}
                                </h2>
                                <div class="h-1 w-20 bg-[var(--accent)] mx-auto rounded"></div>
                            </div>
                        @endif
                        <div class="privacy-content text-[var(--text_secondary)]">
                            {!! $block['data']['content'] ?? '' !!}
                        </div>
                    </div>
                </section>
            @endif

            {{-- ═══════ SOCIAL FEED ═══════ --}}
            @if ($block['type'] === 'social_feed')
                @php
                    $sfPosts = $block['data']['posts'] ?? [];

                    // Helper: extrae el ID de YouTube de cualquier URL
                    $ytId = function(string $url): ?string {
                        if (preg_match('/(?:youtube\.com\/(?:watch\?v=|shorts\/)|youtu\.be\/)([a-zA-Z0-9_\-]{11})/', $url, $m)) {
                            return $m[1];
                        }
                        return null;
                    };

                    // Helper: extrae shortcode de Instagram
                    $igCode = function(string $url): ?string {
                        if (preg_match('#instagram\.com/(?:p|reel|tv)/([A-Za-z0-9_\-]+)#', $url, $m)) {
                            return $m[1];
                        }
                        return null;
                    };

                    // Helper: detecta si es un video de TikTok con ID numérico
                    $ttId = function(string $url): ?string {
                        if (preg_match('#tiktok\.com/@[^/]+/video/(\d+)#', $url, $m)) {
                            return $m[1];
                        }
                        return null;
                    };

                    $platformColors = [
                        'tiktok'    => '#010101',
                        'instagram' => '#C13584',
                        'youtube'   => '#FF0000',
                        'facebook'  => '#1877F2',
                    ];
                    $platformLabels = [
                        'tiktok'    => 'TikTok',
                        'instagram' => 'Instagram',
                        'youtube'   => 'YouTube',
                        'facebook'  => 'Facebook',
                    ];
                    // Detecta si hay algún embed de TikTok o Instagram para cargar sus scripts
                    $hasTikTok    = collect($sfPosts)->where('platform', 'tiktok')->isNotEmpty();
                    $hasInstagram = collect($sfPosts)->where('platform', 'instagram')->isNotEmpty();
                    $hasFacebook  = collect($sfPosts)->where('platform', 'facebook')->isNotEmpty();
                @endphp

                <section class="py-20 bg-[var(--bg_section)]" id="social-feed">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

                        {{-- Header --}}
                        @if (!empty($block['data']['heading']))
                            <div class="text-center mb-4">
                                <h2 class="text-4xl md:text-5xl font-['Anton'] text-[var(--text_primary)] uppercase tracking-widest mb-4">
                                    {{ $block['data']['heading'] }}
                                </h2>
                                <div class="h-1 w-24 bg-[var(--accent)] mx-auto rounded"></div>
                            </div>
                        @endif
                        @if (!empty($block['data']['description']))
                            <p class="text-[var(--text_secondary)] text-center mb-10 max-w-2xl mx-auto">{{ $block['data']['description'] }}</p>
                        @else
                            <div class="mb-10"></div>
                        @endif

                        {{-- Grid de posts --}}
                        @if (count($sfPosts) > 0)
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach ($sfPosts as $post)
                                    @php
                                        $platform = $post['platform'] ?? 'youtube';
                                        $url      = $post['url'] ?? '';
                                        $caption  = $post['caption'] ?? '';
                                        $color    = $platformColors[$platform] ?? '#333';
                                        $label    = $platformLabels[$platform] ?? $platform;
                                    @endphp

                                    <div class="flex flex-col bg-[var(--bg_card)] rounded-2xl overflow-hidden border border-[var(--border)] hover:border-[var(--border_hover)] transition-all duration-300 shadow-xl group" style="height:600px;">

                                        {{-- Badge de plataforma --}}
                                        <div class="flex items-center gap-2 px-4 py-3 border-b border-[var(--border)]">
                                            @if ($platform === 'tiktok')
                                                <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1V9.01a6.33 6.33 0 00-.79-.05 6.34 6.34 0 00-6.34 6.34 6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.33-6.34V8.75a8.2 8.2 0 004.79 1.52V6.79a4.85 4.85 0 01-1.02-.1z"/>
                                                </svg>
                                            @elseif ($platform === 'instagram')
                                                <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
                                                </svg>
                                            @elseif ($platform === 'youtube')
                                                <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                                                </svg>
                                            @elseif ($platform === 'facebook')
                                                <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                                </svg>
                                            @endif
                                            <span class="text-xs font-bold uppercase tracking-widest" style="color: {{ $color }}">{{ $label }}</span>
                                            <a href="{{ $url }}" target="_blank" rel="noopener"
                                                class="ml-auto text-gray-600 hover:text-[var(--text_secondary)] transition-colors">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                                </svg>
                                            </a>
                                        </div>

                                        {{-- Embed por plataforma --}}
                                        <div class="flex-1 overflow-hidden w-full">

                                            @if ($platform === 'tiktok')
                                                @php $ttVideoId = $ttId($url); @endphp
                                                @if ($ttVideoId)
                                                    <div class="flex justify-center items-start bg-black h-full overflow-y-auto py-2">
                                                        <blockquote
                                                            class="tiktok-embed"
                                                            cite="{{ $url }}"
                                                            data-video-id="{{ $ttVideoId }}"
                                                            style="max-width:325px; min-width:280px;">
                                                            <section>
                                                                <a href="{{ $url }}" target="_blank" rel="noopener">Ver en TikTok</a>
                                                            </section>
                                                        </blockquote>
                                                    </div>
                                                @else
                                                    <a href="{{ $url }}" target="_blank" rel="noopener"
                                                        class="flex items-center justify-center h-full text-[var(--text_secondary)] hover:text-[var(--text_primary)] transition-colors text-sm">
                                                        Ver en TikTok
                                                    </a>
                                                @endif

                                            @elseif ($platform === 'instagram')
                                                @php $igShortcode = $igCode($url); @endphp
                                                @if ($igShortcode)
                                                    <div class="flex justify-center items-start bg-black h-full overflow-y-auto py-2">
                                                        <blockquote
                                                            class="instagram-media"
                                                            data-instgrm-permalink="{{ rtrim($url, '/') }}/?utm_source=ig_embed"
                                                            data-instgrm-version="14"
                                                            style="background:#FFF; border:0; margin:0; max-width:330px; min-width:280px; width:100%;">
                                                            <div style="padding:16px;">
                                                                <a href="{{ $url }}" target="_blank" rel="noopener" style="color:#000; font-family:Arial,sans-serif; font-size:14px;">
                                                                    Ver esta publicación en Instagram
                                                                </a>
                                                            </div>
                                                        </blockquote>
                                                    </div>
                                                @else
                                                    <a href="{{ $url }}" target="_blank" rel="noopener"
                                                        class="flex items-center justify-center h-full text-[var(--text_secondary)] hover:text-[var(--text_primary)] transition-colors text-sm">
                                                        Ver en Instagram
                                                    </a>
                                                @endif

                                            @elseif ($platform === 'youtube')
                                                @php $vid = $ytId($url); @endphp
                                                @if ($vid)
                                                    <a href="{{ $url }}" target="_blank" rel="noopener"
                                                        class="relative flex w-full h-full overflow-hidden group/yt"
                                                        style="background:#000;">
                                                        <img
                                                            src="https://i.ytimg.com/vi/{{ $vid }}/hqdefault.jpg"
                                                            alt="YouTube thumbnail"
                                                            class="w-full h-full object-cover opacity-80 group-hover/yt:opacity-60 transition-opacity duration-300">
                                                        <div class="absolute inset-0 flex items-center justify-center">
                                                            <div class="w-14 h-14 rounded-full bg-[#FF0000] flex items-center justify-center shadow-2xl group-hover/yt:scale-110 transition-transform duration-300">
                                                                <svg class="w-6 h-6 text-[var(--text_primary)] ml-1" fill="currentColor" viewBox="0 0 24 24">
                                                                    <path d="M8 5v14l11-7z"/>
                                                                </svg>
                                                            </div>
                                                        </div>
                                                    </a>
                                                @else
                                                    <a href="{{ $url }}" target="_blank" rel="noopener"
                                                        class="flex items-center justify-center h-full text-[var(--text_secondary)] hover:text-[var(--text_primary)] transition-colors text-sm">
                                                        Ver en YouTube
                                                    </a>
                                                @endif

                                            @elseif ($platform === 'facebook')
                                                {{-- Facebook SDK embed: funciona para posts, videos y Reels --}}
                                                <div class="flex justify-center items-start bg-[#f0f2f5] h-full overflow-y-auto py-2">
                                                    <div
                                                        class="fb-post"
                                                        data-href="{{ $url }}"
                                                        data-width="330"
                                                        data-show-text="true">
                                                    </div>
                                                </div>
                                            @endif

                                        </div>

                                        {{-- Caption --}}
                                        @if (!empty($caption))
                                            <div class="px-4 py-3 border-t border-[var(--border)] shrink-0">
                                                <p class="text-[var(--text_secondary)] text-sm leading-relaxed line-clamp-2">{{ $caption }}</p>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-16 text-[var(--text_muted)]">
                                <p class="text-5xl mb-4">📱</p>
                                <p class="italic">Próximamente publicaciones de redes sociales.</p>
                            </div>
                        @endif

                        {{-- Scripts embed --}}
                        @if ($hasTikTok)
                            <script async src="https://www.tiktok.com/embed.js"></script>
                        @endif
                        @if ($hasInstagram)
                            <script async src="//www.instagram.com/embed.js"></script>
                        @endif
                        @if ($hasFacebook)
                            <div id="fb-root"></div>
                            <script async defer crossorigin="anonymous" src="https://connect.facebook.net/es_LA/sdk.js#xfbml=1&version=v19.0"></script>
                        @endif

                    </div>
                </section>
            @endif

        @endforeach
    @else
        {{-- Estado vacío --}}
        <div class="min-h-screen bg-[var(--bg_primary)] flex items-center justify-center text-[var(--text_primary)] pt-20">
            <div class="text-center">
                <p class="text-8xl mb-6">🔥</p>
                <h2 class="text-4xl font-['Anton'] uppercase tracking-widest mb-4">Próximamente</h2>
                <p class="text-[var(--text_muted)] text-lg">Estamos preparando algo increíble. Vuelve pronto.</p>
                <a href="/"
                    class="inline-block mt-8 bg-[var(--accent)] hover:bg-[var(--button_hover)] text-[var(--text_primary)] px-8 py-3 rounded font-bold uppercase tracking-widest transition-all">
                    Volver al inicio
                </a>
            </div>
        </div>
    @endif

    {{-- ═══════ MODAL POR PÁGINA ═══════ --}}
    @if (!$isPreview && $page && !empty($page->modal_enabled) && !empty($page->modal_title))
        @php
            $modalDelay = (int)($page->modal_delay ?? 0) * 1000;
            $modalSlug  = $page->slug ?? 'page';
            $carouselItems = $page->modal_carousel_items ?? [];
            $videoUrl = $page->modal_video_url ?? '';
            $hasCarousel = !empty($page->modal_show_carousel) && count($carouselItems) > 0;
            $hasVideo = !empty($page->modal_show_video) && !empty($videoUrl);
            $isIntrusive = !empty($page->modal_intrusive);

            if ($hasVideo && str_contains($videoUrl, 'youtube.com/watch?v=')) {
                $vid = parse_url($videoUrl, PHP_URL_QUERY);
                parse_str($vid, $qp);
                $embedUrl = 'https://www.youtube.com/embed/' . ($qp['v'] ?? '');
            } elseif ($hasVideo && str_contains($videoUrl, 'youtu.be/')) {
                $embedUrl = 'https://www.youtube.com/embed/' . basename(parse_url($videoUrl, PHP_URL_PATH));
            } elseif ($hasVideo && str_contains($videoUrl, 'vimeo.com/')) {
                $embedUrl = 'https://player.vimeo.com/video/' . basename(parse_url($videoUrl, PHP_URL_PATH));
            } else {
                $embedUrl = $videoUrl;
            }
            if ($hasVideo && !empty($page->modal_video_autoplay)) {
                $embedUrl .= (str_contains($embedUrl, '?') ? '&' : '?') . 'autoplay=1&mute=1';
            }
        @endphp
        <div
            x-data="{
                open: false,
                init() {
                    @if ($isIntrusive)
                        setTimeout(() => { this.open = true; }, {{ $modalDelay }});
                    @else
                        const key = 'modal_shown_{{ $modalSlug }}';
                        if (!sessionStorage.getItem(key)) {
                            setTimeout(() => { this.open = true; }, {{ $modalDelay }});
                        }
                    @endif
                },
                close() {
                    @if (!$isIntrusive)
                        sessionStorage.setItem('modal_shown_{{ $modalSlug }}', '1');
                    @endif
                    this.open = false;
                }
            }"
            x-init="init()"
            x-show="open"
            x-cloak
            class="fixed inset-0 z-[9990] flex items-center justify-center p-4"
            style="background: rgba(0,0,0,0.75);"
            @click.self="close()"
            @keydown.escape.window="close()">

            <div
                x-show="open"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                :class="{
                    'max-w-[90vw]': {{ $hasVideo || $hasCarousel ? 'true' : 'false' }},
                    'max-w-2xl': {{ !$hasVideo && !$hasCarousel ? 'true' : 'false' }}
                }"
                class="relative bg-[var(--bg_primary)] border border-[var(--border_light)] rounded-2xl shadow-2xl w-full p-8">

                {{-- Botón cerrar --}}
                <button @click="close()"
                    class="absolute top-4 right-4 text-[var(--text_muted)] hover:text-[var(--text_primary)] transition-colors z-10">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>

                {{-- Barra roja decorativa --}}
                <div class="h-1 w-16 bg-[var(--accent)] rounded mb-4"></div>

                {{-- Título --}}
                <h3 class="text-2xl font-['Anton'] text-[var(--text_primary)] uppercase tracking-widest mb-4">
                    {{ $page->modal_title }}
                </h3>

                {{-- Video embebido --}}
                @if ($hasVideo)
                    <div class="relative rounded-xl overflow-hidden mb-4" style="aspect-ratio: 16/9;">
                        <iframe src="{{ $embedUrl }}"
                            class="absolute inset-0 w-full h-full"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen>
                        </iframe>
                    </div>
                @endif

                {{-- Carrusel de imágenes --}}
                @if ($hasCarousel && !$hasVideo)
                    <div x-data="{
                        activeSlide: 0,
                        slides: {{ count($carouselItems) }},
                        autoplay: null,
                        init() {
                            this.autoplay = setInterval(() => { this.next() }, 5000);
                        },
                        next() { this.activeSlide = this.activeSlide === this.slides - 1 ? 0 : this.activeSlide + 1 },
                        prev() { this.activeSlide = this.activeSlide === 0 ? this.slides - 1 : this.activeSlide - 1; },
                        goTo(i) { this.activeSlide = i; },
                    }" x-init="init()" class="relative mb-4"
                    @mouseenter="autoplay && (clearInterval(autoplay), autoplay = null)"
                    @mouseleave="!autoplay && (autoplay = setInterval(() => next(), 5000))">
                        <div class="relative overflow-hidden rounded-xl border border-[var(--border_light)] bg-gray-800">
                            <div class="flex transition-transform duration-700 ease-out"
                                :style="'transform: translateX(-' + (activeSlide * 100) + '%)'">
                                @foreach ($carouselItems as $item)
                                    <div class="w-full shrink-0 relative">
                                        @if (!empty($item['link']))
                                            <a href="{{ $item['link'] }}" target="_blank" class="absolute inset-0 z-10"></a>
                                        @endif
                                        <div class="relative" style="min-height: min(50vw, 350px) !important;">
<img src="{{ Str::startsWith($item['image'], 'http') ? $item['image'] : asset('storage/' . $item['image']) }}"
                                                 alt="{{ $item['caption'] ?? '' }}"
                                                 class="w-full object-contain max-h-[400px] md:max-h-[600px] lg:max-h-[700px]"
                                                 onerror="this.src='https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&q=80'">
                                            @if (!empty($item['caption']))
                                                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-[var(--bg_primary)]/90 to-transparent p-2 sm:p-3">
                                                    <p class="text-[var(--text_primary)] text-xs sm:text-sm text-center">{{ $item['caption'] }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @if (count($carouselItems) > 1)
                            <button @click="prev"
                                class="absolute left-1 top-1/2 -translate-y-1/2 w-7 h-7 sm:w-9 sm:h-9 bg-black/70 hover:bg-[var(--accent)] text-[var(--text_primary)] rounded-full flex items-center justify-center transition-all z-10">
                                <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                            </button>
                            <button @click="next"
                                class="absolute right-1 top-1/2 -translate-y-1/2 w-7 h-7 sm:w-9 sm:h-9 bg-black/70 hover:bg-[var(--accent)] text-[var(--text_primary)] rounded-full flex items-center justify-center transition-all z-10">
                                <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>
                            <div class="absolute bottom-12 left-1/2 -translate-x-1/2 flex items-center gap-1 z-20">
                                <template x-for="i in slides">
                                    <button @click="goTo(i - 1)"
                                        class="rounded-sm transition-all duration-300"
                                        :class="activeSlide === i - 1 ? 'w-5 !bg-[var(--accent)]' : 'w-1 bg-gray-400 hover:bg-gray-300'"
                                        style="min-height: 4px !important;">
                                    </button>
                                </template>
                            </div>
                        @endif
                    </div>
                @endif

                {{-- Cuerpo --}}
                @if (!empty($page->modal_body) && !$hasVideo)
                    <div class="text-[var(--text_secondary)] text-sm leading-relaxed mb-6 prose prose-invert prose-sm max-w-none">
                        {!! $page->modal_body !!}
                    </div>
                @endif

                {{-- Botón CTA opcional --}}
                @if (!empty($page->modal_button_label))
                    <div class="flex gap-3 mt-2">
                        @if (!empty($page->modal_button_url))
                            <a href="{{ $page->modal_button_url }}"
                                target="_blank"
                                class="inline-block bg-[var(--accent)] hover:bg-[var(--button_hover)] text-[var(--text_primary)] font-bold uppercase tracking-widest text-sm px-6 py-3 rounded transition-all shadow-lg">
                                {{ $page->modal_button_label }}
                            </a>
                        @endif
                        <button @click="close()"
                            class="text-sm text-[var(--text_muted)] hover:text-[var(--text_secondary)] transition-colors underline underline-offset-2">
                            Cerrar
                        </button>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
