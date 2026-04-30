@php
/* ─── helpers ─────────────────────────────────────────── */
// Barra de texto skeleton (línea punteada horizontal)
$line = fn(string $w = 'w-full', string $h = 'h-2') => "<div class=\"{$h} {$w} rounded-sm border border-dashed border-gray-600\"></div>";
// Bloque imagen/contenedor padre (borde continuo)
$box  = fn(string $w, string $h, string $extra = '') => "<div class=\"{$w} {$h} rounded border border-gray-500 {$extra}\"></div>";
// Bloque hijo pequeño (borde punteado)
$child = fn(string $w, string $h, string $extra = '') => "<div class=\"{$w} {$h} rounded border border-dashed border-gray-600 {$extra}\"></div>";
// Botón simulado (padre, borde continuo)
$btn  = fn(string $w = 'w-20', string $extra = '') => "<div class=\"{$w} h-6 rounded border border-gray-500 flex items-center justify-center {$extra}\"><div class='h-1.5 w-3/4 rounded-sm border border-dashed border-gray-600'></div></div>";
// Avatar circular (hijo, punteado)
$avatar = fn(string $size = 'w-8 h-8') => "<div class=\"{$size} rounded-full border border-dashed border-gray-600 flex-shrink-0\"></div>";

// Nombre del bloque centrado dentro de un contenedor
$label = fn(string $name, string $color = '#9ca3af') => "<div class='absolute inset-0 flex items-center justify-center pointer-events-none'><span style='font-size:8px;color:white;font-weight:600;text-transform:uppercase;letter-spacing:.06em;text-shadow:0 1px 3px rgba(0,0,0,.9);'>{$name}</span></div>";
@endphp

<div class="space-y-1.5 text-[0px]">

    @if (empty($blocks))
        <div class="flex flex-col items-center justify-center py-10 text-center text-gray-500 text-sm">
            <span class="text-3xl mb-2">📭</span>
            <p class="text-xs">Sin bloques todavía.</p>
            <p class="text-xs text-gray-600 mt-1">Agrega bloques para ver el esqueleto aquí.</p>
        </div>
    @else

    {{-- NAV (padre: borde continuo) --}}
    <div class="flex items-center gap-2 rounded border border-gray-500 px-3 py-2">
        <div class="w-5 h-5 rounded-sm border border-gray-500 flex-shrink-0"></div>
        <div class="flex-1 flex gap-2">
            <div class="h-2 w-8 rounded-sm border border-dashed border-gray-600"></div>
            <div class="h-2 w-7 rounded-sm border border-dashed border-gray-600"></div>
            <div class="h-2 w-10 rounded-sm border border-dashed border-gray-600"></div>
        </div>
        <div class="h-2 w-12 rounded-sm border border-dashed border-gray-600"></div>
    </div>

    {{-- BLOQUES --}}
    @foreach ($blocks as $block)
    @php
        $type = $block['type'] ?? 'unknown';
        $data = $block['data'] ?? [];

        $itemCount = 0;
        foreach ($data as $v) {
            if (is_array($v) && !empty($v) && is_array(array_values($v)[0])) {
                $itemCount = max($itemCount, count($v));
            }
        }
        $cols3 = min($itemCount, 3);
        $cols2 = min($itemCount, 2);

        $labels = [
            'hero'                => 'Hero / Portada',
            'featured_products'   => 'Productos Destacados',
            'about_section'       => 'Nosotros / Historia',
            'ahumado_section'     => 'Proceso / Técnica',
            'bbq_section'         => 'Categoría Principal',
            'taqueria_section'    => 'Categoría Secundaria',
            'reviews_section'     => 'Reseñas (BD)',
            'contact_map_section' => 'Ubicación y Contacto',
            'promotions_carousel' => 'Carrusel de Imágenes',
            'contact_form'        => 'Formulario de Contacto',
            'cta_banner'          => 'Banner CTA',
            'faq'                 => 'FAQ',
            'pricing'             => 'Precios / Planes',
            'team'                => 'Equipo / Staff',
            'features'            => 'Características',
            'testimonials'        => 'Testimonios',
            'gallery'             => 'Galería',
            'video_embed'         => 'Video Embed',
            'stats'               => 'Estadísticas',
            'rich_text'           => 'Rich Text',
            'social_feed'         => 'Muro de Redes',
        ];
        $blockLabel = $labels[$type] ?? $type;
    @endphp

    {{-- Contenedor padre: borde continuo --}}
    <div class="relative rounded border border-gray-500 overflow-hidden">

        @switch($type)

        {{-- ══ HERO ════════════════════════════════════════════ --}}
        @case('hero')
            <div class="relative p-2 flex items-center gap-2 min-h-[56px]">
                <div class="flex-1 space-y-1.5">
                    <div class="h-3 w-3/4 rounded-sm border border-dashed border-gray-600"></div>
                    <div class="h-2 w-1/2 rounded-sm border border-dashed border-gray-600"></div>
                    <div class="h-5 w-14 rounded border border-gray-500"></div>
                </div>
                {{-- imagen lateral --}}
                <div class="w-10 h-12 rounded border border-dashed border-gray-600 flex-shrink-0"></div>
                {{-- label centrado --}}
                {!! $label($blockLabel) !!}
            </div>
        @break

        {{-- ══ FEATURED PRODUCTS ═══════════════════════════════ --}}
        @case('featured_products')
            <div class="relative p-2 space-y-1.5 min-h-[56px]">
                <div class="h-2 w-1/3 rounded-sm border border-dashed border-gray-600 mx-auto"></div>
                <div class="grid grid-cols-3 gap-1">
                    @for ($i = 0; $i < 3; $i++)
                    {{-- card hijo: punteado --}}
                    <div class="rounded border border-dashed border-gray-600 overflow-hidden">
                        <div class="h-7 border-b border-dashed border-gray-600"></div>
                        <div class="p-1 space-y-1">
                            <div class="h-1.5 rounded-sm border border-dashed border-gray-600"></div>
                            <div class="h-1.5 w-2/3 rounded-sm border border-dashed border-gray-600"></div>
                        </div>
                    </div>
                    @endfor
                </div>
                {!! $label($blockLabel) !!}
            </div>
        @break

        {{-- ══ ABOUT ════════════════════════════════════════════ --}}
        @case('about_section')
            <div class="relative p-2 flex gap-2 min-h-[56px]">
                <div class="flex-1 space-y-1.5">
                    <div class="h-2.5 w-2/3 rounded-sm border border-dashed border-gray-600"></div>
                    <div class="h-1.5 w-full rounded-sm border border-dashed border-gray-600"></div>
                    <div class="h-1.5 w-4/5 rounded-sm border border-dashed border-gray-600"></div>
                    <div class="h-1.5 w-3/5 rounded-sm border border-dashed border-gray-600"></div>
                    <div class="h-5 w-14 rounded border border-gray-500 mt-1"></div>
                </div>
                <div class="w-14 h-14 rounded border border-dashed border-gray-600 flex-shrink-0"></div>
                {!! $label($blockLabel) !!}
            </div>
        @break

        {{-- ══ AHUMADO ══════════════════════════════════════════ --}}
        @case('ahumado_section')
            <div class="relative p-2 space-y-1.5 min-h-[56px]">
                <div class="h-2 w-2/3 rounded-sm border border-dashed border-gray-600 mx-auto"></div>
                {{-- video placeholder --}}
                <div class="h-10 rounded border border-gray-500 flex items-center justify-center">
                    <div class="w-5 h-5 rounded-full border border-dashed border-gray-600 flex items-center justify-center">
                        <div class="w-0 h-0 border-y-[3px] border-y-transparent border-l-[6px] border-l-gray-500 ml-0.5"></div>
                    </div>
                </div>
                {{-- mosaico --}}
                <div class="grid grid-cols-3 gap-0.5">
                    @for ($i = 0; $i < 3; $i++)
                    <div class="h-5 rounded-sm border border-dashed border-gray-600"></div>
                    @endfor
                </div>
                {!! $label($blockLabel) !!}
            </div>
        @break

        {{-- ══ BBQ ══════════════════════════════════════════════ --}}
        @case('bbq_section')
            <div class="relative p-2 space-y-1.5 min-h-[56px]">
                <div class="h-5 rounded border border-gray-500 flex items-center px-2 gap-2">
                    <div class="h-1.5 w-1/3 rounded-sm border border-dashed border-gray-600"></div>
                    <div class="h-1.5 w-1/4 rounded-sm border border-dashed border-gray-600"></div>
                </div>
                <div class="grid grid-cols-{{ max($cols2,2) }} gap-1">
                    @for ($i = 0; $i < max($cols2,2); $i++)
                    <div class="rounded border border-dashed border-gray-600 overflow-hidden">
                        <div class="h-7 border-b border-dashed border-gray-600"></div>
                        <div class="p-1 space-y-1">
                            <div class="h-1.5 rounded-sm border border-dashed border-gray-600"></div>
                            <div class="h-1 w-3/4 rounded-sm border border-dashed border-gray-600"></div>
                        </div>
                    </div>
                    @endfor
                </div>
                {!! $label($blockLabel) !!}
            </div>
        @break

        {{-- ══ TAQUERÍA ═════════════════════════════════════════ --}}
        @case('taqueria_section')
            <div class="relative p-2 space-y-1.5 min-h-[56px]">
                <div class="h-4 rounded border border-gray-500 flex items-center px-2">
                    <div class="h-1.5 w-2/5 rounded-sm border border-dashed border-gray-600"></div>
                </div>
                <div class="grid grid-cols-{{ max($cols2,2) }} gap-1">
                    @for ($i = 0; $i < max($cols2,2); $i++)
                    <div class="rounded border border-dashed border-gray-600 overflow-hidden">
                        <div class="h-6 border-b border-dashed border-gray-600"></div>
                        <div class="p-1 space-y-0.5">
                            <div class="h-1.5 rounded-sm border border-dashed border-gray-600"></div>
                            <div class="h-1 w-1/2 rounded-sm border border-dashed border-gray-600"></div>
                        </div>
                    </div>
                    @endfor
                </div>
                {!! $label($blockLabel) !!}
            </div>
        @break

        {{-- ══ REVIEWS ══════════════════════════════════════════ --}}
        @case('reviews_section')
            <div class="relative p-2 space-y-1.5 min-h-[56px]">
                <div class="h-2 w-1/2 rounded-sm border border-dashed border-gray-600 mx-auto"></div>
                <div class="flex gap-1">
                    @for ($i = 0; $i < 3; $i++)
                    <div class="flex-1 rounded border border-dashed border-gray-600 p-1.5 space-y-1">
                        <div class="flex gap-0.5">
                            @for ($s = 0; $s < 5; $s++)
                            <div class="w-1.5 h-1.5 rounded-sm border border-dashed border-gray-600"></div>
                            @endfor
                        </div>
                        <div class="h-1.5 rounded-sm border border-dashed border-gray-600"></div>
                        <div class="h-1.5 w-4/5 rounded-sm border border-dashed border-gray-600"></div>
                        <div class="flex items-center gap-1 mt-1">
                            <div class="w-3.5 h-3.5 rounded-full border border-dashed border-gray-600 flex-shrink-0"></div>
                            <div class="h-1.5 w-2/3 rounded-sm border border-dashed border-gray-600"></div>
                        </div>
                    </div>
                    @endfor
                </div>
                {!! $label($blockLabel) !!}
            </div>
        @break

        {{-- ══ CONTACT MAP ══════════════════════════════════════ --}}
        @case('contact_map_section')
            <div class="relative p-2 flex gap-2 min-h-[56px]">
                <div class="flex-1 space-y-1.5">
                    <div class="h-2 w-2/3 rounded-sm border border-dashed border-gray-600"></div>
                    <div class="h-1.5 rounded-sm border border-dashed border-gray-600"></div>
                    <div class="h-1.5 w-4/5 rounded-sm border border-dashed border-gray-600"></div>
                    <div class="h-5 w-16 rounded border border-gray-500 mt-1"></div>
                </div>
                {{-- mapa simulado --}}
                <div class="w-20 h-14 rounded border border-gray-500 flex-shrink-0 relative">
                    <div class="absolute inset-0 grid grid-cols-3 grid-rows-3 gap-px p-1">
                        @for ($i = 0; $i < 9; $i++)
                        <div class="border border-dashed border-gray-700 rounded-sm"></div>
                        @endfor
                    </div>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="w-2 h-2 rounded-full border border-gray-400"></div>
                    </div>
                </div>
                {!! $label($blockLabel) !!}
            </div>
        @break

        {{-- ══ PROMOTIONS CAROUSEL ═════════════════════════════ --}}
        @case('promotions_carousel')
            <div class="relative p-2 space-y-1.5 min-h-[48px]">
                <div class="h-2 w-2/5 rounded-sm border border-dashed border-gray-600 mx-auto"></div>
                <div class="flex gap-1 overflow-hidden">
                    <div class="flex-shrink-0 w-3/4 h-9 rounded border border-gray-500"></div>
                    <div class="flex-shrink-0 w-3/4 h-9 rounded border border-dashed border-gray-600 -ml-4 opacity-50"></div>
                </div>
                <div class="flex gap-1 justify-center">
                    <div class="w-3 h-1 rounded-full border border-gray-500"></div>
                    <div class="w-1.5 h-1 rounded-full border border-dashed border-gray-600"></div>
                    <div class="w-1.5 h-1 rounded-full border border-dashed border-gray-600"></div>
                </div>
                {!! $label($blockLabel) !!}
            </div>
        @break

        {{-- ══ CONTACT FORM ═════════════════════════════════════ --}}
        @case('contact_form')
            <div class="relative p-2 space-y-1.5 min-h-[56px]">
                <div class="h-2 w-2/5 rounded-sm border border-dashed border-gray-600"></div>
                @for ($i = 0; $i < 3; $i++)
                <div class="h-5 rounded border border-dashed border-gray-600"></div>
                @endfor
                <div class="h-8 rounded border border-dashed border-gray-600"></div>
                <div class="h-6 w-20 rounded border border-gray-500"></div>
                {!! $label($blockLabel) !!}
            </div>
        @break

        {{-- ══ CTA BANNER ═══════════════════════════════════════ --}}
        @case('cta_banner')
            <div class="relative p-3 flex items-center justify-between gap-2 min-h-[48px]">
                <div class="flex-1 space-y-1.5">
                    <div class="h-2.5 w-2/3 rounded-sm border border-dashed border-gray-600"></div>
                    <div class="h-1.5 w-4/5 rounded-sm border border-dashed border-gray-600"></div>
                </div>
                <div class="h-6 w-16 rounded border border-gray-500 flex-shrink-0"></div>
                {!! $label($blockLabel) !!}
            </div>
        @break

        {{-- ══ FAQ ═══════════════════════════════════════════════ --}}
        @case('faq')
            <div class="relative p-2 space-y-1.5 min-h-[48px]">
                <div class="h-2 w-2/5 rounded-sm border border-dashed border-gray-600"></div>
                @for ($i = 0; $i < max($itemCount,3); $i++)
                <div class="rounded border border-dashed border-gray-600 px-2 py-1.5 flex items-center gap-2">
                    <div class="flex-1 h-1.5 rounded-sm border border-dashed border-gray-600"></div>
                    <div class="w-2 h-2 rounded-sm border border-dashed border-gray-600 flex-shrink-0"></div>
                </div>
                @endfor
                {!! $label($blockLabel) !!}
            </div>
        @break

        {{-- ══ PRICING ═══════════════════════════════════════════ --}}
        @case('pricing')
            <div class="relative p-2 space-y-1.5 min-h-[64px]">
                <div class="h-2 w-2/5 rounded-sm border border-dashed border-gray-600 mx-auto"></div>
                <div class="grid grid-cols-{{ max($cols3,3) }} gap-1">
                    @for ($i = 0; $i < max($cols3,3); $i++)
                    {{-- plan central destacado: borde continuo, los otros punteados --}}
                    <div class="rounded border {{ $i === 1 ? 'border-gray-500' : 'border-dashed border-gray-600' }} p-1.5 space-y-1">
                        <div class="h-1.5 w-3/4 rounded-sm border border-dashed border-gray-600 mx-auto"></div>
                        <div class="h-3 w-2/3 rounded-sm border {{ $i === 1 ? 'border-gray-400' : 'border-dashed border-gray-600' }} mx-auto"></div>
                        @for ($f = 0; $f < 3; $f++)
                        <div class="h-1 rounded-sm border border-dashed border-gray-600"></div>
                        @endfor
                        <div class="h-4 rounded border {{ $i === 1 ? 'border-gray-400' : 'border-dashed border-gray-600' }} mt-1"></div>
                    </div>
                    @endfor
                </div>
                {!! $label($blockLabel) !!}
            </div>
        @break

        {{-- ══ TEAM ══════════════════════════════════════════════ --}}
        @case('team')
            <div class="relative p-2 space-y-1.5 min-h-[56px]">
                <div class="h-2 w-2/5 rounded-sm border border-dashed border-gray-600 mx-auto"></div>
                <div class="grid grid-cols-{{ max($cols3,3) }} gap-1">
                    @for ($i = 0; $i < max($cols3,3); $i++)
                    <div class="flex flex-col items-center gap-1 p-1">
                        <div class="w-8 h-8 rounded-full border border-dashed border-gray-600"></div>
                        <div class="h-1.5 w-3/4 rounded-sm border border-dashed border-gray-600"></div>
                        <div class="h-1 w-1/2 rounded-sm border border-dashed border-gray-600"></div>
                    </div>
                    @endfor
                </div>
                {!! $label($blockLabel) !!}
            </div>
        @break

        {{-- ══ FEATURES ══════════════════════════════════════════ --}}
        @case('features')
            <div class="relative p-2 space-y-1.5 min-h-[56px]">
                <div class="h-2 w-1/2 rounded-sm border border-dashed border-gray-600 mx-auto"></div>
                <div class="grid grid-cols-3 gap-1">
                    @for ($i = 0; $i < max($itemCount,3); $i++)
                    <div class="flex flex-col items-center gap-1 p-1.5 rounded border border-dashed border-gray-600">
                        <div class="w-5 h-5 rounded-full border border-dashed border-gray-600"></div>
                        <div class="h-1.5 rounded-sm border border-dashed border-gray-600 w-full"></div>
                        <div class="h-1 rounded-sm border border-dashed border-gray-600 w-4/5"></div>
                    </div>
                    @endfor
                </div>
                {!! $label($blockLabel) !!}
            </div>
        @break

        {{-- ══ TESTIMONIALS ══════════════════════════════════════ --}}
        @case('testimonials')
            <div class="relative p-2 space-y-1.5 min-h-[56px]">
                <div class="h-2 w-2/5 rounded-sm border border-dashed border-gray-600 mx-auto"></div>
                <div class="flex gap-1">
                    @for ($i = 0; $i < max($cols3,3); $i++)
                    <div class="flex-1 rounded border border-dashed border-gray-600 p-1.5 space-y-1">
                        <div class="h-1.5 rounded-sm border border-dashed border-gray-600"></div>
                        <div class="h-1.5 w-3/4 rounded-sm border border-dashed border-gray-600"></div>
                        <div class="h-1.5 w-4/5 rounded-sm border border-dashed border-gray-600"></div>
                        <div class="flex items-center gap-1 mt-1">
                            <div class="w-3.5 h-3.5 rounded-full border border-dashed border-gray-600"></div>
                            <div class="h-1.5 w-1/2 rounded-sm border border-dashed border-gray-600"></div>
                        </div>
                    </div>
                    @endfor
                </div>
                {!! $label($blockLabel) !!}
            </div>
        @break

        {{-- ══ GALLERY ════════════════════════════════════════════ --}}
        @case('gallery')
            <div class="relative p-2 space-y-1.5 min-h-[48px]">
                <div class="h-2 w-1/3 rounded-sm border border-dashed border-gray-600 mx-auto"></div>
                <div class="grid grid-cols-3 gap-0.5">
                    @for ($i = 0; $i < max($itemCount,6); $i++)
                    <div class="h-7 rounded-sm border border-dashed border-gray-600"></div>
                    @endfor
                </div>
                {!! $label($blockLabel) !!}
            </div>
        @break

        {{-- ══ VIDEO EMBED ════════════════════════════════════════ --}}
        @case('video_embed')
            <div class="relative p-2 space-y-1.5 min-h-[56px]">
                <div class="h-2 w-2/5 rounded-sm border border-dashed border-gray-600 mx-auto"></div>
                {{-- iframe placeholder: borde continuo (es el contenedor principal del bloque) --}}
                <div class="h-14 rounded border border-gray-500 flex items-center justify-center">
                    <div class="w-6 h-6 rounded-full border border-dashed border-gray-600 flex items-center justify-center">
                        <div class="w-0 h-0 border-y-[4px] border-y-transparent border-l-[7px] border-l-gray-500 ml-0.5"></div>
                    </div>
                </div>
                {!! $label($blockLabel) !!}
            </div>
        @break

        {{-- ══ STATS ══════════════════════════════════════════════ --}}
        @case('stats')
            <div class="relative p-2 min-h-[48px]">
                <div class="grid grid-cols-{{ max($cols3,3) }} gap-1">
                    @for ($i = 0; $i < max($itemCount,3); $i++)
                    <div class="flex flex-col items-center gap-1 p-1.5 rounded border border-dashed border-gray-600">
                        <div class="h-4 w-3/4 rounded-sm border border-gray-500"></div>
                        <div class="h-1.5 w-full rounded-sm border border-dashed border-gray-600"></div>
                    </div>
                    @endfor
                </div>
                {!! $label($blockLabel) !!}
            </div>
        @break

        {{-- ══ RICH TEXT ══════════════════════════════════════════ --}}
        @case('rich_text')
            <div class="relative p-2 min-h-[48px]">
                <div class="mx-auto w-4/5 space-y-1">
                    <div class="h-2.5 w-2/3 rounded-sm border border-dashed border-gray-600"></div>
                    @for ($i = 0; $i < 4; $i++)
                    <div class="h-1.5 rounded-sm border border-dashed border-gray-600 {{ $i === 3 ? 'w-3/5' : 'w-full' }}"></div>
                    @endfor
                    <div class="h-1.5 w-4/5 rounded-sm border border-dashed border-gray-600"></div>
                    <div class="h-1.5 w-full rounded-sm border border-dashed border-gray-600"></div>
                    <div class="h-1.5 w-2/3 rounded-sm border border-dashed border-gray-600"></div>
                </div>
                {!! $label($blockLabel) !!}
            </div>
        @break

        {{-- ══ SOCIAL FEED ════════════════════════════════════════ --}}
        @case('social_feed')
            <div class="relative p-2 space-y-1.5 min-h-[56px]">
                <div class="h-2 w-2/5 rounded-sm border border-dashed border-gray-600 mx-auto"></div>
                <div class="grid grid-cols-{{ max($cols3,3) }} gap-1">
                    @for ($i = 0; $i < max($itemCount,3); $i++)
                    <div class="rounded border border-dashed border-gray-600 overflow-hidden">
                        <div class="h-9 border-b border-dashed border-gray-600"></div>
                        <div class="p-1 space-y-0.5">
                            <div class="h-1.5 rounded-sm border border-dashed border-gray-600"></div>
                            <div class="h-1 w-2/3 rounded-sm border border-dashed border-gray-600"></div>
                        </div>
                    </div>
                    @endfor
                </div>
                {!! $label($blockLabel) !!}
            </div>
        @break

        {{-- ══ FALLBACK ════════════════════════════════════════════ --}}
        @default
            <div class="relative h-10 flex items-center px-2 min-h-[40px]">
                <div class="h-1.5 w-2/3 rounded-sm border border-dashed border-gray-600"></div>
                {!! $label($blockLabel) !!}
            </div>
        @endswitch

    </div>
    @endforeach

    {{-- FOOTER (padre: borde continuo) --}}
    <div class="flex items-center justify-between rounded border border-gray-500 px-3 py-2 mt-1">
        <div class="w-5 h-5 rounded-sm border border-dashed border-gray-600 flex-shrink-0"></div>
        <div class="flex gap-3">
            <div class="h-1.5 w-8 rounded-sm border border-dashed border-gray-600"></div>
            <div class="h-1.5 w-8 rounded-sm border border-dashed border-gray-600"></div>
            <div class="h-1.5 w-8 rounded-sm border border-dashed border-gray-600"></div>
        </div>
        <div class="h-1.5 w-16 rounded-sm border border-dashed border-gray-600"></div>
    </div>

    <p class="text-center text-gray-600 pt-0.5" style="font-size:9px;">{{ count($blocks) }} bloque(s)</p>

    @endif
</div>
