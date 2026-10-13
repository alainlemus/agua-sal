@php $__st = openStatus(); @endphp
@if ($__st['known'])
    <span {{ $attributes->merge(['class' => 'inline-flex items-center gap-2 rounded-full border px-4 py-1.5 text-xs font-semibold uppercase tracking-widest backdrop-blur ' . ($__st['open'] ? 'border-emerald-400/40 bg-emerald-500/10 text-emerald-300' : 'border-white/20 bg-white/5 text-gray-300')]) }}>
        <span class="relative flex h-2 w-2">
            @if ($__st['open'])<span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75"></span>@endif
            <span class="relative inline-flex h-2 w-2 rounded-full {{ $__st['open'] ? 'bg-emerald-400' : 'bg-gray-400' }}"></span>
        </span>
        {{ $__st['label'] }}
    </span>
@endif
