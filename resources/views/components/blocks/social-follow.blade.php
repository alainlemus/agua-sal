@props(['data' => []])
@php
    $__links = collect(siteInfo()?->social_links ?? [])->keyBy('platform');
    $__cards = [
        'facebook'  => ['Facebook',  $data['facebook_followers'] ?? null,  'M22 12a10 10 0 10-11.6 9.9v-7H7.9V12h2.5V9.8c0-2.5 1.5-3.9 3.8-3.9 1.1 0 2.2.2 2.2.2v2.5h-1.3c-1.2 0-1.6.8-1.6 1.6V12h2.8l-.4 2.9h-2.3v7A10 10 0 0022 12z'],
        'instagram' => ['Instagram', $data['instagram_followers'] ?? null, 'M7.8 2h8.4A5.8 5.8 0 0122 7.8v8.4a5.8 5.8 0 01-5.8 5.8H7.8A5.8 5.8 0 012 16.2V7.8A5.8 5.8 0 017.8 2zm0 2A3.8 3.8 0 004 7.8v8.4A3.8 3.8 0 007.8 20h8.4a3.8 3.8 0 003.8-3.8V7.8A3.8 3.8 0 0016.2 4H7.8zM12 7a5 5 0 110 10 5 5 0 010-10zm0 2a3 3 0 100 6 3 3 0 000-6zm5.3-3.2a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z'],
    ];
@endphp
<section class="bg-[var(--bg_section)] py-24" id="redes">
    <div class="mx-auto max-w-5xl px-4 text-center sm:px-6 lg:px-8">
        <h2 class="mb-4 font-['Anton'] text-4xl uppercase tracking-widest text-[var(--text_primary)] md:text-5xl">{{ $data['heading'] ?? 'Síguenos' }}</h2>
        <p class="mb-12 text-[var(--text_secondary)]">{{ $data['subtitle'] ?? 'Promociones, platillos nuevos y el ambiente de cada día.' }}</p>
        <div class="grid gap-6 sm:grid-cols-2">
            @foreach ($__cards as $platform => [$name, $followers, $path])
                @continue(! $__links->has($platform))
                <a href="{{ $__links[$platform]['url'] }}" target="_blank" rel="noopener"
                   class="tilt-card group flex items-center gap-5 rounded-3xl border border-[var(--border)] bg-gradient-to-br from-[var(--bg_card)] to-[var(--bg_primary)] p-6 text-left transition hover:border-[var(--accent)]">
                    <span class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-[var(--accent)]/15 text-[var(--accent)]">
                        <svg class="h-8 w-8" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="{{ $path }}"/></svg>
                    </span>
                    <span>
                        <span class="block font-['Anton'] text-2xl uppercase tracking-wider text-[var(--text_primary)]">{{ $name }}</span>
                        @if ($followers)<span class="block text-sm text-[var(--text_secondary)]">{{ $followers }} seguidores</span>@endif
                        <span class="mt-1 block text-sm font-semibold text-[var(--accent)] group-hover:underline">Seguir →</span>
                    </span>
                </a>
            @endforeach
        </div>
    </div>
</section>
