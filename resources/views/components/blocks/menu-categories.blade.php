@props(['data' => []])
@php
    $__cats = \App\Models\Category::query()
        ->where('is_active', true)
        ->whereHas('products', fn ($q) => $q->where('is_active', true))
        ->withCount(['products as products_count' => fn ($q) => $q->where('is_active', true)])
        ->withMin(['products as min_price' => fn ($q) => $q->where('is_active', true)], 'price')
        ->orderBy('id')
        ->get();
@endphp
@if ($__cats->isNotEmpty())
    <section class="bg-[var(--bg_primary)] py-24" id="explora-el-menu">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-14 text-center">
                <h2 class="mb-4 font-['Anton'] text-4xl uppercase tracking-widest text-[var(--text_primary)] md:text-5xl">{{ $data['heading'] ?? 'Explora el menú' }}</h2>
                <p class="text-[var(--text_secondary)]">{{ $data['subtitle'] ?? 'Elige por dónde empezar.' }}</p>
            </div>
            <div class="grid grid-cols-2 gap-4 md:grid-cols-3 lg:grid-cols-4">
                @foreach ($__cats as $cat)
                    <a href="/menu" class="tilt-card group relative flex min-h-44 flex-col justify-between overflow-hidden rounded-2xl border border-[var(--border)] bg-gradient-to-br from-[var(--bg_card)] to-[var(--bg_section)] p-5 transition hover:border-[var(--accent)]">
                        <span class="text-xs font-semibold uppercase tracking-widest text-[var(--accent_secondary)]">{{ $cat->products_count }} {{ $cat->products_count === 1 ? 'platillo' : 'platillos' }}</span>
                        <div>
                            <h3 class="font-['Anton'] text-2xl uppercase tracking-wider text-[var(--text_primary)] md:text-3xl">{{ $cat->name }}</h3>
                            @if ($cat->min_price)
                                <p class="mt-1 text-sm text-[var(--text_secondary)]">desde ${{ number_format((float) $cat->min_price, 0) }}</p>
                            @endif
                        </div>
                        <svg class="absolute right-4 top-4 h-5 w-5 text-[var(--accent)] opacity-0 transition group-hover:translate-x-1 group-hover:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-6-6l6 6-6 6"/></svg>
                    </a>
                @endforeach
            </div>
            <div class="mt-12 text-center">
                <a href="/menu" class="inline-flex items-center gap-2 rounded-full bg-[var(--accent)] px-8 py-3 text-sm font-bold uppercase tracking-widest transition hover:brightness-110">Ver menú completo</a>
            </div>
        </div>
    </section>
@endif
