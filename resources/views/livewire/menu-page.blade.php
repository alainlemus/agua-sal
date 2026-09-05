<div>
    {{-- Header --}}
    <section class="pt-44 lg:pt-48 pb-12 bg-black relative overflow-hidden" x-data="{ shown: false }" x-intersect="shown = true">
        <div class="absolute inset-0 bg-gradient-to-b from-[var(--bg_primary)] to-black opacity-50 z-0"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center transition-all duration-1000 transform lg:py-20"
            :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'">
            <h1 class="text-5xl md:text-7xl font-['Anton'] text-white uppercase tracking-widest mb-4">
                Nuestro <span class="text-[var(--accent)]">Menú</span>
            </h1>
            @php $activeMenu = $menus->firstWhere('slug', $activeTab); @endphp
            <p class="text-xl text-gray-400 font-medium">{{ $activeMenu?->subtitle ?? '' }}</p>
            <div class="h-1 w-24 bg-[var(--accent_green)] mx-auto rounded mt-6"></div>
        </div>
    </section>

    <section class="bg-[var(--bg_primary)] min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if ($menus->isEmpty())
                <div class="text-center text-gray-500 italic py-20 text-xl">
                    Estamos preparando el menú. Vuelve pronto.
                </div>
            @else
                {{-- TABS --}}
                @if ($menus->count() > 1)
                    <div class="flex overflow-x-auto gap-2 pt-8 pb-2 justify-center flex-wrap">
                        @foreach ($menus as $menu)
<button wire:click="setTab('{{ $menu->slug }}')"
                                class="px-6 py-3 rounded-full font-black text-sm uppercase tracking-widest transition-all whitespace-nowrap
                           {{ $activeTab === $menu->slug
                                ? 'bg-[var(--accent)] text-white shadow-lg shadow-red-900/40'
                                : 'bg-[var(--bg_card)] text-gray-400 hover:text-white hover:bg-[var(--bg_card_hover)]' }}">
                                {{ $menu->name }}
                                @if ($menu->schedule)
                                    <span
                                        class="block text-xs font-normal opacity-70 normal-case tracking-normal mt-0.5">{{ $menu->schedule }}</span>
                                @endif
                            </button>
                        @endforeach
                    </div>
                @else
                    <div class="pt-8"></div>
                @endif

                {{-- MENU CONTENT --}}
                @foreach ($menus as $menu)
                    <div @if ($menus->count() > 1) x-show="'{{ $menu->slug }}' === '{{ $activeTab }}'" @endif
                        class="py-12">

                        @if ($menu->description)
                            <p class="text-center text-gray-400 mb-10 max-w-2xl mx-auto">{{ $menu->description }}</p>
                        @endif

                        @php
                            $visibleSections = $menu->sections->filter(fn ($s) => $s->products()->count() > 0)->values();
                        @endphp

                        @if ($visibleSections->isEmpty())
                            <p class="text-center text-gray-600 italic py-10">Este menú no tiene secciones configuradas
                                aún.</p>
                        @else
                            <div @if ($visibleSections->count() > 1) x-data="{ activeCategory: '{{ $visibleSections->first()->id }}' }" @endif>

                                @if ($visibleSections->count() > 1)
                                    <div class="sticky top-20 lg:top-24 z-30 -mx-4 sm:mx-0 px-4 sm:px-0 py-3 mb-10 bg-[var(--bg_primary)]/95 backdrop-blur border-b border-[var(--border)]">
                                        <div class="relative"
                                            x-data="{
                                                atStart: true,
                                                atEnd: false,
                                                checkScroll(el) {
                                                    this.atStart = el.scrollLeft <= 4;
                                                    this.atEnd = el.scrollLeft + el.clientWidth >= el.scrollWidth - 4;
                                                }
                                            }"
                                            x-init="$nextTick(() => checkScroll($refs.scroller))">
                                            <div x-ref="scroller" @scroll="checkScroll($event.target)"
                                                class="flex overflow-x-auto gap-2 [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
                                                @foreach ($visibleSections as $section)
                                                    <button @click="activeCategory = '{{ $section->id }}'"
                                                        :class="activeCategory === '{{ $section->id }}' ? 'bg-[var(--accent_green)] text-white' : 'bg-[var(--bg_card)] text-gray-400 hover:text-white hover:bg-[var(--bg_card_hover)]'"
                                                        class="px-3.5 sm:px-5 py-2 sm:py-2.5 rounded-full font-black text-xs sm:text-sm uppercase tracking-widest whitespace-nowrap transition-colors shrink-0">
                                                        {{ $section->display_name }}
                                                    </button>
                                                @endforeach
                                                {{-- spacer so the fade hints below never cover a pill's label --}}
                                                <div class="w-6 shrink-0 sm:hidden" aria-hidden="true"></div>
                                            </div>
                                            {{-- mobile-only "can swipe this way" hints --}}
                                            <div x-show="!atStart" x-cloak
                                                class="pointer-events-none absolute inset-y-0 left-0 w-10 flex items-center justify-start bg-gradient-to-r from-[var(--bg_primary)] to-transparent sm:hidden">
                                                <svg class="w-4 h-4 text-[var(--text_secondary)]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                                                </svg>
                                            </div>
                                            <div x-show="!atEnd" x-cloak
                                                class="pointer-events-none absolute inset-y-0 right-0 w-10 flex items-center justify-end bg-gradient-to-l from-[var(--bg_primary)] to-transparent sm:hidden">
                                                <svg class="w-4 h-4 text-[var(--text_secondary)]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                            <div class="space-y-20">
                                @foreach ($visibleSections as $section)
                                    @php $products = $section->products(); @endphp
                                    @if ($products->count() > 0)
                                        <div @if ($visibleSections->count() > 1) x-show="activeCategory === '{{ $section->id }}'" @endif
                                            x-data="{ shown: false }" x-intersect="shown = true"
                                            class="transition-all duration-1000 transform"
                                            :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'">

                                            <div class="text-center mb-10">
                                                <h3 class="text-4xl font-['Anton'] font-black text-gray-200 tracking-wider mb-2">
                                                    {{ $section->display_name }}
                                                </h3>
                                                @if ($section->category?->description)
                                                    <p class="text-gray-400 text-sm max-w-2xl mx-auto">
                                                        {{ $section->category->description }}</p>
                                                @endif
                                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-10 gap-y-4">
                                @foreach ($products as $product)
                                    <div class="flex items-center gap-4 group hover:bg-[var(--bg_card)] p-3 sm:p-4 rounded-xl transition-colors border border-transparent hover:border-transparent">
                                        <div class="w-16 h-16 sm:w-24 sm:h-24 flex-shrink-0 rounded-lg overflow-hidden shadow-lg border border-gray-700">
                                            @php
                                                $placeholders = [
                                                    'https://images.unsplash.com/photo-1559737552-2f8195a0651b?auto=format&fit=crop&q=80',
                                                    'https://images.unsplash.com/photo-1615141982883-c7ad0e69fd62?auto=format&fit=crop&q=80',
                                                    'https://images.unsplash.com/photo-1565680018434-b513d5e5fd47?auto=format&fit=crop&q=80',
                                                ];
                                                $productImg = $product->image
                                                    ? asset('storage/' . $product->image)
                                                    : $placeholders[$loop->index % count($placeholders)];
                                            @endphp
                                            <img src="{{ $productImg }}"
                                                alt="{{ $product->name }}"
                                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                                                onerror="this.src='https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&q=80'">
                                        </div>
                                        <div class="flex-grow flex flex-col justify-center min-w-0">
                                            <div class="flex justify-between items-baseline mb-1 sm:mb-2 border-b border-transparent pb-1 sm:pb-2 gap-2">
                                                 <h4 class="text-base sm:text-xl font-black text-white uppercase tracking-wide group-hover:text-[var(--accent)] transition-colors truncate">
                                                     {{ $product->name }}
                                                 </h4>
                                                <span class="text-[var(--accent_green)] font-bold text-base sm:text-xl flex-shrink-0">
                                                    ${{ number_format($product->price, 2) }}
                                                </span>
                                            </div>
                                             <p class="text-gray-400 text-xs sm:text-sm line-clamp-2">{{ $product->description }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                            </div>
                        @endif

                    </div>
                @endforeach

            @endif {{-- end menus check --}}
        </div>
    </section>
</div>
