<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @php
        $__seo = \App\Models\SiteInfo::first();
        $__siteName = $__seo?->site_name ?: config('app.name', 'Mi Restaurante');
        $__pageTitle = isset($title) && $title ? $title . ' — ' . $__siteName : ($__seo?->seo_title ?: $__siteName);
        $__desc = $__seo?->seo_description ?: $__siteName;
        $__ogImg = $__seo?->og_image ? asset('storage/' . $__seo->og_image) : asset('images/og-default.jpg');
        $__canonical = url()->current();

        // Enhanced JSON-LD Schema for Restaurant
        $__ld = [
            '@context' => 'https://schema.org',
            '@type' => 'Restaurant',
            'name' => $__siteName,
            'description' => $__desc,
            'url' => url('/'),
            'servesCuisine' => $__seo?->serves_cuisine ?: 'Mexican',
            'priceRange' => $__seo?->price_range ?: '$$',
            'acceptsReservations' => 'True',
        ];

        // Logo and image
        if ($__seo?->site_logo) {
            $__logoUrl = asset('storage/' . $__seo->site_logo);
            $__ld['logo'] = $__logoUrl;
            $__ld['image'] = $__logoUrl;
        }

        // Address with proper structure
        if ($__seo?->address) {
            $__ld['address'] = [
                '@type' => 'PostalAddress',
                'streetAddress' => $__seo->address,
                'addressLocality' => $__seo->address_locality ?? '',
                'addressRegion' => $__seo->address_region ?? '',
                'postalCode' => $__seo->postal_code ?? '',
                'addressCountry' => 'MX',
            ];
            $__ld['geo'] = [
                '@type' => 'GeoCoordinates',
                'latitude' => $__seo->latitude ?? 0,
                'longitude' => $__seo->longitude ?? 0,
            ];
        }

        // Phone
        if ($__seo?->phone) {
            $__ld['telephone'] = $__seo->phone;
        }

        // Email
        if ($__seo?->email) {
            $__ld['email'] = $__seo->email;
        }

        // Opening hours specification
        if ($__seo && is_array($__seo->schedules) && count($__seo->schedules) > 0) {
            $__ld['openingHoursSpecification'] = [];
            $__dayMap = [
                'Lunes' => 'Monday',
                'Martes' => 'Tuesday',
                'Miércoles' => 'Wednesday',
                'Jueves' => 'Thursday',
                'Viernes' => 'Friday',
                'Sábado' => 'Saturday',
                'Domingo' => 'Sunday',
            ];
            foreach ($__seo->schedules as $schedule) {
                $days = $schedule['days'] ?? '';
                $hours = $schedule['hours'] ?? '';
                if ($days && $hours) {
                    // Parse hours like "1pm - 10pm"
                    preg_match('/(\d+)(am|pm)\s*-\s*(\d+)(am|pm)/i', $hours, $matches);
                    $opens = '';
                    $closes = '';
                    if ($matches) {
                        $opens = $matches[1] % 12 . ':00 ' . strtoupper($matches[2]);
                        $closes = $matches[3] % 12 . ':00 ' . strtoupper($matches[4]);
                    }
                    foreach ($__dayMap as $es => $en) {
                        if (stripos($days, $es) !== false) {
                            $__ld['openingHoursSpecification'][] = [
                                '@type' => 'OpeningHoursSpecification',
                                'dayOfWeek' => $en,
                                'opens' => $opens ?: '13:00',
                                'closes' => $closes ?: '22:00',
                            ];
                        }
                    }
                }
            }
        }

        // Menu URL
        $__ld['hasMenu'] = [
            '@type' => 'Menu',
            'url' => url('/menu'),
        ];

        // Aggregate rating from reviews
        $__avgRating = \App\Models\ReviewSubmission::whereIn('rating', [4, 5])->avg('rating');
        if ($__avgRating) {
            $__ld['aggregateRating'] = [
                '@type' => 'AggregateRating',
                'ratingValue' => round($__avgRating, 1),
                'ratingCount' => \App\Models\ReviewSubmission::whereIn('rating', [4, 5])->count(),
                'bestRating' => 5,
                'worstRating' => 1,
            ];
        }

        // Social profiles for sameAs
        $__sameAs = [];
        if ($__seo && is_array($__seo->social_links)) {
            foreach ($__seo->social_links as $social) {
                if (!empty($social['url'])) {
                    $__sameAs[] = $social['url'];
                }
            }
        }
        if (!empty($__sameAs)) {
            $__ld['sameAs'] = $__sameAs;
        }
        $__themeCSS = '';
        $__themeColors = $__seo?->getThemeColors() ?: [];
        if (!empty($__themeColors)) {
            $__cssParts = [];
            foreach ($__themeColors as $__k => $__v) {
                $__cssParts[] = "--{$__k}:{$__v}";
            }
            $__themeCSS = ':root { ' . implode('; ', $__cssParts) . ' }';
        }
    @endphp

    <title>{{ $__pageTitle }}</title>

    @if ($__themeCSS)
    <style>{{ $__themeCSS }}</style>
    @endif

    <!-- SEO básico -->
    <meta name="description" content="{{ $__desc }}">
    @if ($__seo?->seo_keywords)
        <meta name="keywords" content="{{ $__seo->seo_keywords }}">
    @endif
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ $__canonical }}">

    <!-- Open Graph (Facebook, WhatsApp, LinkedIn…) -->
    <meta property="og:type" content="{{ $__seo?->og_type ?? 'website' }}">
    <meta property="og:site_name" content="{{ $__siteName }}">
    <meta property="og:title" content="{{ $__pageTitle }}">
    <meta property="og:description" content="{{ $__desc }}">
    <meta property="og:url" content="{{ $__canonical }}">
    <meta property="og:image" content="{{ $__ogImg }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:locale" content="es_MX">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="{{ $__seo?->twitter_card ?? 'summary_large_image' }}">
    <meta name="twitter:title" content="{{ $__pageTitle }}">
    <meta name="twitter:description" content="{{ $__desc }}">
    <meta name="twitter:image" content="{{ $__ogImg }}">
    @if ($__seo?->twitter_site)
        <meta name="twitter:site" content="{{ $__seo->twitter_site }}">
    @endif

    <!-- Structured Data: Restaurant Schema -->
    <script type="application/ld+json">{!! json_encode($__ld, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}</script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Vite & Tailwind -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Favicon -->
    @if ($__seo?->favicon)
        <link rel="icon" type="image/png" href="{{ asset('storage/' . $__seo->favicon) }}">
        <link rel="apple-touch-icon" href="{{ asset('storage/' . $__seo->favicon) }}">
    @else
        <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    @endif

    <!-- View Transitions API -->
    <meta name="view-transition" content="same-origin">
    <style>
        @@view-transition {
            navigation: auto;
        }
    </style>

    @livewireStyles
</head>

<body class="bg-[var(--bg_primary)] text-[var(--text_primary)] font-sans antialiased text-base selection:bg-[var(--accent)] selection:text-white">

    <!-- Skip Navigation Link for Accessibility -->
    <a href="#main-content" class="skip-nav-link"
        style="position:absolute;width:1px;height:1px;padding:0;margin:-1px;overflow:hidden;clip:rect(0,0,0,0);white-space:nowrap;border:0;">
        Saltar al contenido principal
    </a>

    @php
        $globalSiteInfo = \App\Models\SiteInfo::first();
        $navPages = \App\Models\Page::navItems();
        $currentSlug = request()->segment(1) ?? '';
        $navSocials  = $globalSiteInfo?->social_links ?? [];
        $hoverColors = [
            'facebook'  => 'hover:text-[#1877F2]',
            'instagram' => 'hover:text-[#E1306C]',
            'tiktok'    => 'hover:text-white',
            'x'         => 'hover:text-white',
            'youtube'   => 'hover:text-[#FF0000]',
            'whatsapp'  => 'hover:text-[#25D366]',
            'other'     => 'hover:text-[#E52B2B]',
        ];
    @endphp

    <!-- Navigation -->
    <nav x-data="{ open: false, scrolled: false, showMobileTrigger: false }"
        x-init="
            scrolled = window.scrollY > 10;
            window.addEventListener('scroll', () => { scrolled = window.scrollY > 10 });
            const navLinks = $el.querySelector('.nav-links-container');
            const checkOverflow = () => {
                if (navLinks) {
                    showMobileTrigger = navLinks.scrollHeight > 50 || navLinks.scrollWidth > navLinks.parentElement.scrollWidth;
                }
            };
            checkOverflow();
            window.addEventListener('resize', checkOverflow);
        "
        :class="scrolled ? 'bg-[var(--bg_primary)]/95 backdrop-blur border-b border-[var(--border)]' : 'bg-transparent border-b border-transparent'"
        class="fixed w-full z-50 transition-all duration-300" role="navigation" aria-label="Navegación principal">
<div class="max-w-[90rem] mx-auto px-4 sm:px-6 lg:px-12 xl:px-8">
            <div class="flex flex-wrap justify-between items-center gap-y-4 transition-all duration-300 py-3 md:py-4"
                 :class="scrolled ? 'py-3' : 'py-4'">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="/" class="flex items-center gap-2 transition-transform hover:scale-105 duration-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-[var(--accent)] focus-visible:ring-offset-2 focus-visible:ring-offset-[var(--bg_primary)] rounded">
                        @if ($globalSiteInfo?->site_logo)
                            <img src="{{ asset('storage/' . $globalSiteInfo->site_logo) }}" alt="{{ siteName() }} - Inicio"
                                class="object-contain rounded drop-shadow-lg transition-all duration-300"
                                :class="scrolled ? 'h-12 lg:h-16' : 'h-28 lg:h-32'" width="144" height="48">
                        @else
                            <img src="{{ asset('images/logo.png') }}" alt="{{ siteName() }} - Inicio"
                                class="object-contain rounded drop-shadow-lg transition-all duration-300"
                                :class="scrolled ? 'h-12 lg:h-16' : 'h-28 lg:h-32'" width="144" height="48">
                        @endif
                    </a>
                </div>

                <!-- Desktop Nav Links -->
                <div class="hidden md:flex items-center flex-wrap justify-center gap-x-5 gap-y-1 nav-links-container" role="list">
                    @foreach ($navPages as $navPage)
                        @php
                            $isActive = $navPage->slug === 'home' ? request()->is('/') : request()->is($navPage->slug);
                        @endphp
                        <a href="{{ $navPage->url }}"
                            class="flex items-center gap-1 transition-colors duration-200 uppercase text-sm tracking-widest font-semibold {{ $isActive ? 'text-white border-b-2 border-[#E52B2B] pb-0.5' : 'text-gray-300 hover:text-white' }}"
                            role="listitem" @if ($isActive) aria-current="page" @endif>
                            @if ($navPage->nav_icon)
                                <span aria-hidden="true">{{ $navPage->nav_icon }}</span>
                            @endif
                            {{ $navPage->nav_label ?: $navPage->title }}
                        </a>
                    @endforeach
                </div>

                <!-- Desktop CTA + Redes Sociales -->
                <div class="hidden md:flex items-center gap-4">
                    <a href="/menu"
                        class="inline-flex items-center gap-2 bg-[var(--button_green)] hover:bg-[var(--button_green_hover)] text-[var(--text_primary)] px-6 py-2 rounded font-bold uppercase tracking-widest text-sm transition-all shadow-lg shadow-green-900/30 focus:outline-none focus-visible:ring-2 focus-visible:ring-[var(--button_green)] focus-visible:ring-offset-2 focus-visible:ring-offset-[var(--bg_primary)]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        Menú
                    </a>
                    <a href="{{ $globalSiteInfo?->whatsapp ? 'https://wa.me/' . ltrim($globalSiteInfo->whatsapp, '+') : '#' }}" target="_blank" rel="noopener"
                        class="inline-flex items-center gap-2 bg-[var(--button_primary)] hover:bg-[var(--button_hover)] text-[var(--text_primary)] px-6 py-2 rounded font-bold uppercase tracking-widest text-sm transition-all shadow-lg focus:outline-none focus-visible:ring-2 focus-visible:ring-[var(--button_primary)] focus-visible:ring-offset-2 focus-visible:ring-offset-[var(--bg_primary)]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Reservar Ahora
                    </a>

                    @if (count($navSocials ?? []) > 0)
                        <div class="flex items-center gap-2 border-l border-gray-700 pl-4" role="list" aria-label="Redes sociales">
                            @foreach ($navSocials as $social)
                                @php
                                    $p  = $social['platform'] ?? 'other';
                                    $hc = $hoverColors[$p] ?? 'hover:text-[#E52B2B]';
                                    $label = $social['label'] ?? ucfirst($p);
                                @endphp
                                <a href="{{ $social['url'] ?? '#' }}" target="_blank" rel="noopener"
                                   aria-label="{{ $label }}"
                                   class="flex justify-center items-center {{ $hc }} transition-colors duration-300"
                                   :class="scrolled ? 'text-gray-400' : 'text-white'">
                                    @include('components.social-icon', ['platform' => $p])
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Mobile Hamburger -->
                <button @click="open = !open"
                    class="md:hidden text-gray-300 hover:text-white p-2 rounded-lg transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-[#E52B2B] focus-visible:ring-offset-2 focus-visible:ring-offset-[#1c1c1c]"
                    :aria-expanded="open.toString()" aria-controls="mobile-menu" aria-label="Abrir menú de navegación">
                    <svg x-show="!open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg x-show="open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true" style="display:none">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <!-- Desktop Overflow Trigger -->
                <button x-show="showMobileTrigger && !open" @click="open = !open"
                    class="hidden md:flex items-center text-gray-300 hover:text-white p-2 rounded-lg transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-[#E52B2B] focus-visible:ring-offset-2 focus-visible:ring-offset-[#1c1c1c] border border-gray-600"
                    aria-expanded="open.toString()" aria-controls="mobile-menu" aria-label="Ver más opciones">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu Panel -->
        <div id="mobile-menu" x-show="open" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            class="md:hidden bg-[var(--bg_primary)] border-t border-[var(--border)] shadow-2xl" style="display:none" role="navigation"
            aria-label="Menú móvil">
            <div class="max-w-7xl mx-auto px-4 py-6 space-y-1">

                {{-- Páginas CMS dinámicas con show_in_nav = true --}}
                @foreach ($navPages as $navPage)
                    <a href="{{ $navPage->url }}" @click="open=false"
                        class="flex items-center gap-3 px-4 py-3 rounded-lg uppercase text-sm tracking-widest font-semibold transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-[#E52B2B] {{ ($navPage->slug === 'home' ? request()->is('/') : request()->is($navPage->slug)) ? 'text-white bg-white/10' : 'text-gray-300 hover:text-white hover:bg-white/5' }}">
                        @if ($navPage->nav_icon)
                            <span aria-hidden="true">{{ $navPage->nav_icon }}</span>
                        @endif
                        {{ $navPage->nav_label ?: $navPage->title }}
                    </a>
                @endforeach

                <div class="pt-4 border-t border-gray-800 flex flex-col gap-3">
                    <a href="/menu" @click="open=false"
                        class="inline-flex items-center justify-center gap-2 w-full bg-[var(--button_green)] hover:bg-[var(--button_green_hover)] text-[var(--text_primary)] px-6 py-3 rounded font-bold uppercase tracking-widest text-sm transition-all shadow-lg focus:outline-none focus-visible:ring-2 focus-visible:ring-[var(--button_green)] focus-visible:ring-offset-2 focus-visible:ring-offset-[var(--bg_primary)]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        Menú
                    </a>
                    <a href="{{ $globalSiteInfo?->whatsapp ? 'https://wa.me/' . ltrim($globalSiteInfo->whatsapp, '+') : '#' }}"
                        target="_blank" rel="noopener" @click="open=false"
                        class="flex items-center justify-center gap-2 w-full bg-[var(--button_primary)] hover:bg-[var(--button_hover)] text-[var(--text_primary)] px-6 py-3 rounded font-bold uppercase tracking-widest text-sm transition-all shadow-lg focus:outline-none focus-visible:ring-2 focus-visible:ring-[var(--button_primary)] focus-visible:ring-offset-2 focus-visible:ring-offset-[var(--bg_primary)]">
                        Reservar Ahora
                    </a>

                    @if (count($navSocials ?? []) > 0)
                        <div class="flex items-center justify-center gap-4 mt-5" role="list"
                            aria-label="Redes sociales">
                            @foreach ($navSocials as $social)
                                @php
                                    $p = $social['platform'] ?? 'other';
                                    $hc = $hoverColors[$p] ?? 'hover:text-[#E52B2B]';
                                    $label = $social['label'] ?? ucfirst($p);
                                @endphp
                                <a href="{{ $social['url'] ?? '#' }}" target="_blank" rel="noopener"
                                    aria-label="{{ $label }}"
                                    class="text-gray-400 {{ $hc }} transition-colors duration-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#E52B2B] focus-visible:ring-offset-2 focus-visible:ring-offset-[#111]">
                                    @include('components.social-icon', ['platform' => $p])
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </nav>


    <main id="main-content" tabindex="-1">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-[var(--bg_primary)] py-12 border-t border-[var(--border)]" id="contact">
<div class="max-w-[90rem] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="flex flex-col items-center text-center">
                    <!-- Footer Logo -->
                    <div class="mb-6">
                        @if ($globalSiteInfo?->site_logo)
                            <img src="{{ asset('storage/' . $globalSiteInfo->site_logo) }}"
                                alt="{{ siteName() }} Logo" class="w-auto object-contain rounded drop-shadow-lg"
                                width="96" height="96">
                        @else
                            <img src="{{ asset('images/logo.png') }}" alt="{{ siteName() }} Logo"
                                class="h-24 w-auto object-contain rounded drop-shadow-lg" width="96"
                                height="96">
                        @endif
                    </div>
                    @if ($globalSiteInfo?->tagline)
                        <p class="text-[var(--text_secondary)]">{{ $globalSiteInfo->tagline }}</p>
                    @endif

                    {{-- Redes sociales --}}
                    @php
                        $socials = $globalSiteInfo?->social_links ?? [];
                        $hoverColors = [
                            'facebook' => 'hover:text-[#1877F2]',
                            'instagram' => 'hover:text-[#E1306C]',
                            'tiktok' => 'hover:text-white',
                            'x' => 'hover:text-white',
                            'youtube' => 'hover:text-[#FF0000]',
                            'whatsapp' => 'hover:text-[#25D366]',
                            'other' => 'hover:text-[#E52B2B]',
                        ];
                    @endphp
                    @if (count($socials) > 0)
                        <div class="flex items-center gap-3 mt-5" role="list"
                            aria-label="Redes sociales">
                            @foreach ($socials as $social)
                                @php
                                    $platform = $social['platform'] ?? 'other';
                                    $color = $hoverColors[$platform] ?? 'hover:text-[#E52B2B]';
                                    $label = $social['label'] ?? ucfirst($platform);
                                @endphp
                                <a href="{{ $social['url'] ?? '#' }}" target="_blank" rel="noopener"
                                    aria-label="{{ $label }}"
                                    class="text-[var(--text_secondary)] {{ $color }} transition-colors duration-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-[var(--accent)] focus-visible:ring-offset-2 focus-visible:ring-offset-[var(--bg_primary)] flex justify-center items-center shrink-0"
                                    role="listitem">
                                    @include('components.social-icon', [
                                        'platform' => $platform,
                                        'size' => 'w-6 h-6',
                                    ])
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
                <div>
                    <h4 class="text-lg font-bold text-white mb-4 uppercase tracking-wider">Contacto</h4>
                    <ul class="text-gray-400 space-y-3">
                        @if ($globalSiteInfo?->address)
                            <li class="flex items-start gap-3">
                                <svg class="w-5 h-5 shrink-0 mt-0.5 text-[#E52B2B]" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span>{{ $globalSiteInfo->address }}</span>
                            </li>
                        @endif
                        @if ($globalSiteInfo?->phone)
                            <li class="flex items-start gap-3">
                                <svg class="w-5 h-5 shrink-0 mt-0.5 text-[#E52B2B]" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                <a href="tel:{{ $globalSiteInfo->phone }}"
                                    class="hover:text-white transition-colors">{{ $globalSiteInfo->phone }}</a>
                            </li>
                        @endif
                        @if ($globalSiteInfo?->email)
                            <li class="flex items-start gap-3">
                                <svg class="w-5 h-5 shrink-0 mt-0.5 text-[#E52B2B]" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <a href="mailto:{{ $globalSiteInfo->email }}"
                                    class="hover:text-white transition-colors">{{ $globalSiteInfo->email }}</a>
                            </li>
                        @endif
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-bold text-white mb-4 uppercase tracking-wider">Horarios</h4>
                    <ul class="text-gray-400 space-y-2">
                        @if ($globalSiteInfo && is_array($globalSiteInfo->schedules) && count($globalSiteInfo->schedules) > 0)
                            @foreach ($globalSiteInfo->schedules as $schedule)
                                <li>
                                    <span class="text-white font-medium">{{ $schedule['days'] ?? '' }}:</span>
                                    {{ $schedule['hours'] ?? '' }}
                                </li>
                            @endforeach
                        @else
                            <li>Lunes – Sábado: 1pm – 10pm</li>
                            <li>Domingos: 12pm – 8pm</li>
                        @endif
                    </ul>
                </div>
            </div>
            <div
                class="mt-12 pt-8 border-t border-gray-800 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 text-gray-500 text-sm">
                <span>&copy; {{ date('Y') }} {{ siteName() }}. Todos los derechos reservados.</span>
                <div class="flex items-center gap-6">
                    <a href="/aviso-de-privacidad"
                        class="flex justify-center items-center hover:text-gray-300 transition-colors underline underline-offset-2 focus:outline-none focus-visible:text-white whitespace-nowrap">Aviso
                        de Privacidad</a>
                    <button
                        onclick="
                            var el = document.getElementById('cookie-banner');
                            var alpineData = Alpine.$data ? Alpine.$data(el) : (el._x_dataStack && el._x_dataStack[0]);
                            if (alpineData) { alpineData.show = true; } else { el.style.display = 'flex'; }
                        "
                        class="hover:text-gray-300 transition-colors underline underline-offset-2 focus:outline-none focus-visible:text-white whitespace-nowrap">
                        Gestionar Cookies
                    </button>
                </div>
            </div>
        </div>
    </footer>

    @if ($globalSiteInfo?->background_music)
        <audio id="bgMusicPlayer" src="{{ asset('storage/' . $globalSiteInfo->background_music) }}" loop></audio>
        <div x-data="{
            playing: false,
            started: false,
            init() {
                const savedState = localStorage.getItem('music_state');
                if (savedState === 'playing') {
                    const audio = document.getElementById('bgMusicPlayer');
                    audio.play().then(() => { this.playing = true;
                        this.started = true; }).catch(() => {});
                }
                @if($globalSiteInfo?->auto_play_music)
                if (!this.started && !savedState) {
                    document.addEventListener('click', () => {
                        if (!this.started) {
                            this.started = true;
                            const audio = document.getElementById('bgMusicPlayer');
                            audio.play().then(() => {
                                this.playing = true;
                                localStorage.setItem('music_state', 'playing');
                            }).catch(() => {});
                        }
                    }, { once: true });
                }
                @endif
                document.getElementById('bgMusicPlayer')?.addEventListener('ended', () => {
                    this.playing = false;
                    localStorage.setItem('music_state', 'stopped');
                });
            },
            toggle() {
                const audio = document.getElementById('bgMusicPlayer');
                if (this.playing) {
                    audio.pause();
                    this.playing = false;
                    localStorage.setItem('music_state', 'stopped');
                } else {
                    audio.play().then(() => {
                        this.playing = true;
                        this.started = true;
                        localStorage.setItem('music_state', 'playing');
                    }).catch(() => {});
                }
            }
        }" class="fixed bottom-6 left-6 z-50">
            <button @click="toggle"
                class="bg-[#1c1c1c] hover:bg-[#2a2a2a] text-white p-3 rounded-full shadow-lg transition-all hover:scale-110 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#E52B2B] flex items-center justify-center"
                :class="playing ? 'ring-2 ring-[#E52B2B]' : ''"
                :aria-label="playing ? 'Pausar música' : 'Reproducir música'">
                <svg x-show="!playing" class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M8 5v14l11-7z" />
                </svg>
                <svg x-show="playing" x-cloak class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"
                    aria-hidden="true">
                    <path d="M6 4h4v16H6V4zm8 0h4v16h-4V4z" />
                </svg>
            </button>
        </div>
        <style>
            [x-cloak] {
                display: none !important;
            }
        </style>
    @endif

    @if ($globalSiteInfo?->whatsapp)
        <a href="https://wa.me/{{ ltrim($globalSiteInfo->whatsapp, '+') }}" target="_blank" rel="noopener"
            aria-label="Contactar por WhatsApp"
            class="fixed bottom-6 right-6 bg-[#25D366] text-white p-4 rounded-full shadow-lg z-50 flex items-center justify-center focus:outline-none focus-visible:ring-4 focus-visible:ring-white/30 whatsapp-btn">
            <svg class="w-8 h-8 relative z-10" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"
                xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 0 0-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z" />
            </svg>
        </a>
        <style>
            .whatsapp-btn::before {
                content: '';
                position: absolute;
                inset: 0;
                border-radius: 50%;
                background: #25D366;
                animation: whatsapp-ring 2s ease-out infinite;
                z-index: 0;
            }
            @keyframes whatsapp-ring {
                0% { transform: scale(1); opacity: 0.6; }
                100% { transform: scale(1.6); opacity: 0; }
            }
        </style>
    @endif

    {{-- Burbujas Effect --}}
    @if ($globalSiteInfo?->enable_bubbles !== false)
    <div class="bubbles-container pointer-events-none fixed inset-0 overflow-hidden z-30" aria-hidden="true">
        <div class="bubble absolute w-3 h-3 rounded-full bg-cyan-400/30 animate-bubble" style="left: 5%; animation-duration: 8s; animation-delay: 0s;"></div>
        <div class="bubble absolute w-5 h-5 rounded-full bg-cyan-300/25 animate-bubble" style="left: 12%; animation-duration: 10s; animation-delay: 1s;"></div>
        <div class="bubble absolute w-4 h-4 rounded-full bg-cyan-500/35 animate-bubble" style="left: 18%; animation-duration: 7s; animation-delay: 2s;"></div>
        <div class="bubble absolute w-6 h-6 rounded-full bg-cyan-400/20 animate-bubble" style="left: 25%; animation-duration: 9s; animation-delay: 0.5s;"></div>
        <div class="bubble absolute w-3 h-3 rounded-full bg-cyan-300/30 animate-bubble" style="left: 32%; animation-duration: 11s; animation-delay: 3s;"></div>
        <div class="bubble absolute w-5 h-5 rounded-full bg-cyan-500/25 animate-bubble" style="left: 38%; animation-duration: 8s; animation-delay: 1.5s;"></div>
        <div class="bubble absolute w-4 h-4 rounded-full bg-cyan-400/35 animate-bubble" style="left: 45%; animation-duration: 10s; animation-delay: 2.5s;"></div>
        <div class="bubble absolute w-7 h-7 rounded-full bg-cyan-300/20 animate-bubble" style="left: 52%; animation-duration: 9s; animation-delay: 0.8s;"></div>
        <div class="bubble absolute w-4 h-4 rounded-full bg-cyan-400/30 animate-bubble" style="left: 58%; animation-duration: 12s; animation-delay: 3.5s;"></div>
        <div class="bubble absolute w-5 h-5 rounded-full bg-cyan-500/25 animate-bubble" style="left: 65%; animation-duration: 7s; animation-delay: 1.2s;"></div>
        <div class="bubble absolute w-3 h-3 rounded-full bg-cyan-300/35 animate-bubble" style="left: 72%; animation-duration: 10s; animation-delay: 2.2s;"></div>
        <div class="bubble absolute w-6 h-6 rounded-full bg-cyan-400/25 animate-bubble" style="left: 78%; animation-duration: 8s; animation-delay: 0.3s;"></div>
        <div class="bubble absolute w-4 h-4 rounded-full bg-cyan-500/30 animate-bubble" style="left: 85%; animation-duration: 11s; animation-delay: 4s;"></div>
        <div class="bubble absolute w-5 h-5 rounded-full bg-cyan-300/20 animate-bubble" style="left: 90%; animation-duration: 9s; animation-delay: 1.8s;"></div>
        <div class="bubble absolute w-3 h-3 rounded-full bg-cyan-400/35 animate-bubble" style="left: 95%; animation-duration: 10s; animation-delay: 2.8s;"></div>
    </div>
    @endif

    {{-- Sal Sparkle Effect --}}
    @if ($globalSiteInfo?->enable_salt_effect !== false)
    <div class="sparkle-container pointer-events-none fixed inset-0 overflow-hidden z-30" aria-hidden="true">
        <div class="sparkle absolute w-[3px] h-[3px] bg-white animate-sparkle" style="left: 8%; animation-duration: 3s; animation-delay: 0s;"></div>
        <div class="sparkle absolute w-[2px] h-[2px] bg-amber-100 animate-sparkle" style="left: 15%; animation-duration: 4s; animation-delay: 0.5s;"></div>
        <div class="sparkle absolute w-[4px] h-[4px] bg-white animate-sparkle" style="left: 22%; animation-duration: 3.5s; animation-delay: 1s;"></div>
        <div class="sparkle absolute w-[2px] h-[2px] bg-amber-50 animate-sparkle" style="left: 30%; animation-duration: 4.5s; animation-delay: 1.5s;"></div>
        <div class="sparkle absolute w-[3px] h-[3px] bg-white animate-sparkle" style="left: 38%; animation-duration: 3s; animation-delay: 2s;"></div>
        <div class="sparkle absolute w-[2px] h-[2px] bg-amber-100 animate-sparkle" style="left: 45%; animation-duration: 4s; animation-delay: 2.5s;"></div>
        <div class="sparkle absolute w-[4px] h-[4px] bg-white animate-sparkle" style="left: 55%; animation-duration: 3.5s; animation-delay: 0.3s;"></div>
        <div class="sparkle absolute w-[2px] h-[2px] bg-amber-50 animate-sparkle" style="left: 62%; animation-duration: 4.5s; animation-delay: 0.8s;"></div>
        <div class="sparkle absolute w-[3px] h-[3px] bg-white animate-sparkle" style="left: 70%; animation-duration: 3s; animation-delay: 1.3s;"></div>
        <div class="sparkle absolute w-[2px] h-[2px] bg-amber-100 animate-sparkle" style="left: 78%; animation-duration: 4s; animation-delay: 1.8s;"></div>
        <div class="sparkle absolute w-[4px] h-[4px] bg-white animate-sparkle" style="left: 85%; animation-duration: 3.5s; animation-delay: 2.3s;"></div>
        <div class="sparkle absolute w-[3px] h-[3px] bg-amber-50 animate-sparkle" style="left: 92%; animation-duration: 4s; animation-delay: 2.8s;"></div>
    </div>
    @endif

    <style>
        @keyframes bubble-rise {
            0% {
                transform: translateY(100vh) scale(0.5);
                opacity: 0;
            }
            10% {
                opacity: 0.6;
            }
            90% {
                opacity: 0.4;
            }
            100% {
                transform: translateY(-10vh) scale(1);
                opacity: 0;
            }
        }
        .animate-bubble {
            animation: bubble-rise linear infinite;
        }

        @keyframes sparkle-fall {
            0% {
                transform: translateY(-5vh) scale(0.5) rotate(0deg);
                opacity: 0;
            }
            10% {
                opacity: 1;
                transform: translateY(5vh) scale(1) rotate(45deg);
            }
            50% {
                opacity: 0.8;
                transform: translateY(45vh) scale(1.2) rotate(180deg);
            }
            90% {
                opacity: 0.3;
                transform: translateY(85vh) scale(0.8) rotate(315deg);
            }
            100% {
                transform: translateY(105vh) scale(0.5) rotate(360deg);
                opacity: 0;
            }
        }
        .animate-sparkle {
            animation: sparkle-fall linear infinite;
        }

        .waves-container {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 150px;
            pointer-events: none;
            z-index: 15;
            overflow: hidden;
        }
        .wave {
            position: absolute;
            bottom: 0;
            left: -10%;
            width: 120%;
            height: 100%;
            background: linear-gradient(to top, var(--bg_secondary) 0%, transparent 100%);
            opacity: 0.3;
            animation: wave-flow 8s ease-in-out infinite;
        }
        .wave:nth-child(2) {
            animation-delay: -2s;
            opacity: 0.2;
            animation-duration: 10s;
        }
        .wave:nth-child(3) {
            animation-delay: -4s;
            opacity: 0.15;
            animation-duration: 12s;
        }
        @keyframes wave-flow {
            0%, 100% { transform: translateX(0) translateY(0); }
            25% { transform: translateX(5%) translateY(-5px); }
            50% { transform: translateX(0) translateY(0); }
            75% { transform: translateX(-5%) translateY(5px); }
        }
    </style>

    {{-- Animated Waves Effect --}}
    @if ($globalSiteInfo?->enable_waves !== false)
    <div class="waves-container" aria-hidden="true">
        <div class="wave"></div>
        <div class="wave"></div>
        <div class="wave"></div>
    </div>
    @endif

    @php $__cookieKey = \Illuminate\Support\Str::slug(siteName()) . '_cookies_accepted'; @endphp
    <div id="cookie-banner" role="dialog" aria-modal="true" aria-labelledby="cookie-banner-title"
        aria-describedby="cookie-banner-desc" x-data="{
            show: false,
            init() {
                if (!localStorage.getItem('{{ $__cookieKey }}')) {
                    setTimeout(() => { this.show = true }, 800);
                }
            },
            accept(type) {
                localStorage.setItem('{{ $__cookieKey }}', type);
                this.show = false;
                document.getElementById('cookie-banner').style.display = 'none';
            }
        }" x-init="init()" x-show="show"
        x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-4"
        style="display:none"
        class="fixed bottom-0 inset-x-0 z-[9998] bg-[var(--bg_primary)] border-t border-[var(--border)] shadow-2xl px-4 py-5 flex flex-col sm:flex-row items-start sm:items-center gap-4">
        <div class="flex-1 text-sm text-gray-300">
            <span id="cookie-banner-title" class="font-bold text-white">Usamos cookies</span>
            <span id="cookie-banner-desc">para mejorar tu experiencia. Al continuar navegando, aceptas el uso de
                cookies según nuestra</span>
            <a href="/aviso-de-privacidad"
                class="underline text-[#E52B2B] hover:text-red-400 ml-1 focus:outline-none focus-visible:text-red-300">Política
                de Privacidad</a>.
        </div>
        <div class="flex gap-3 shrink-0">
            <button @click="accept('essential')"
                class="text-xs font-bold uppercase tracking-widest border border-gray-600 text-gray-300 hover:text-white hover:border-gray-400 px-4 py-2 rounded transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-gray-400 focus-visible:ring-offset-2 focus-visible:ring-offset-[#111]">
                Solo esenciales
            </button>
            <button @click="accept('all')"
                class="text-xs font-bold uppercase tracking-widest bg-[var(--button_primary)] hover:bg-[var(--button_hover)] text-[var(--text_primary)] px-5 py-2 rounded transition-colors shadow-lg focus:outline-none focus-visible:ring-2 focus-visible:ring-[var(--button_primary)] focus-visible:ring-offset-2 focus-visible:ring-offset-[var(--bg_primary)]">
                Aceptar todo
            </button>
        </div>
    </div>

    @livewireScripts
</body>

</html>
