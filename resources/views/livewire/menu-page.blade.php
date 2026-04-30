<div>
    {{-- Header --}}
    <section class="pt-32 pb-12 bg-black relative overflow-hidden" x-data="{ shown: false }" x-intersect="shown = true">
        <div class="absolute inset-0 bg-gradient-to-b from-[#1c1c1c] to-black opacity-50 z-0"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center transition-all duration-1000 transform lg:py-20"
            :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'">
            <h1 class="text-5xl md:text-7xl font-['Anton'] text-white uppercase tracking-widest mb-4">
                Nuestro <span class="text-[#E52B2B]">Menú</span>
            </h1>
            @php $activeMenu = $menus->firstWhere('slug', $activeTab); @endphp
            <p class="text-xl text-gray-400 font-medium">{{ $activeMenu?->subtitle ?? '' }}</p>
            <div class="h-1 w-24 bg-[#18833b] mx-auto rounded mt-6"></div>
        </div>
    </section>

    <section class="bg-[#1c1c1c] min-h-screen">
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
                                ? 'bg-[#E52B2B] text-white shadow-lg shadow-red-900/40'
                                : 'bg-[#2a2a2a] text-gray-400 hover:text-white hover:bg-[#333]' }}">
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

                        @if ($menu->sections->isEmpty())
                            <p class="text-center text-gray-600 italic py-10">Este menú no tiene secciones configuradas
                                aún.</p>
                        @else
                            <div class="space-y-20">
                                @foreach ($menu->sections as $section)
                                    @php $products = $section->products(); @endphp
                                    @if ($products->count() > 0)
                                        <div x-data="{ shown: false }" x-intersect="shown = true"
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
                                    <div class="flex items-center gap-4 group hover:bg-[#2a2a2a] p-3 sm:p-4 rounded-xl transition-colors border border-transparent hover:border-gray-800">
                                        <div class="w-16 h-16 sm:w-24 sm:h-24 flex-shrink-0 rounded-lg overflow-hidden shadow-lg border border-gray-700">
                                            @php
                                                $placeholders = [
                                                    asset('storage/images/hamburguesa.jpg'),
                                                    asset('storage/images/gabachona.jpg'),
                                                ];
                                                $productImg = $product->image
                                                    ? asset('storage/' . $product->image)
                                                    : $placeholders[$product->id % count($placeholders)];
                                            @endphp
                                            <img src="{{ $productImg }}"
                                                alt="{{ $product->name }}"
                                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                        </div>
                                        <div class="flex-grow flex flex-col justify-center min-w-0">
                                            <div class="flex justify-between items-baseline mb-1 sm:mb-2 border-b border-gray-800 pb-1 sm:pb-2 gap-2">
                                                 <h4 class="text-base sm:text-xl font-black text-white uppercase tracking-wide group-hover:text-[#E52B2B] transition-colors truncate">
                                                     {{ $product->name }}
                                                 </h4>
                                                <span class="text-[#18833b] font-bold text-base sm:text-xl flex-shrink-0">
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
                        @endif

                    </div>
                @endforeach

            @endif {{-- end menus check --}}
        </div>
    </section>
</div>
