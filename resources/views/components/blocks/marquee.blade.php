@props(['data' => []])
@php $__items = collect($data['items'] ?? [])->pluck('text')->filter()->values(); @endphp
@if ($__items->isNotEmpty())
    <section class="marquee relative overflow-hidden border-y border-[var(--border)] bg-[var(--bg_dark)] py-5" aria-label="{{ $data['label'] ?? 'Lo que encontrarás en el menú' }}" data-no-motion>
        <div class="marquee__track">
            @foreach ([0, 1] as $copy)
                <ul class="marquee__group" @if ($copy) aria-hidden="true" @endif>
                    @foreach ($__items as $text)
                        <li class="flex items-center gap-8">
                            <span class="font-['Anton'] text-3xl uppercase tracking-widest text-[var(--text_primary)] md:text-5xl">{{ $text }}</span>
                            <span class="h-3 w-3 shrink-0 rounded-full bg-[var(--accent)]"></span>
                        </li>
                    @endforeach
                </ul>
            @endforeach
        </div>
    </section>
@endif
