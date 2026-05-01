<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página no encontrada — {{ siteName() }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .font-display {
            font-family: 'Anton', sans-serif;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes floatPapi {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-12px);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.8s ease forwards;
        }

        .delay-200 {
            animation-delay: 200ms;
        }

        .delay-400 {
            animation-delay: 400ms;
        }

        .delay-600 {
            animation-delay: 600ms;
        }

        .animate-float {
            animation: floatPapi 4s ease-in-out infinite;
        }

        /* smoke text effect */
        .smoke-glow {
            text-shadow: 0 0 40px rgba(229, 43, 43, 0.4), 0 0 80px rgba(229, 43, 43, 0.15);
        }
    </style>
</head>

<body class="bg-[#0f0f0f] text-gray-200 antialiased overflow-x-hidden">

    <!-- VIDEO / IMAGE BACKGROUND -->
    <div class="fixed inset-0 z-0">
        <video autoplay loop muted playsinline class="w-full h-full object-cover opacity-30">
            <source src="{{ asset('storage/videos/hero.mp4') }}" type="video/mp4">
        </video>
        <!-- Gradient overlay: heavier at center so text is readable -->
        <div class="absolute inset-0 bg-gradient-to-b from-black/70 via-black/50 to-black/80"></div>
        <!-- Subtle red vignette -->
        <div class="absolute inset-0"
            style="background: radial-gradient(ellipse at center, transparent 40%, rgba(0,0,0,0.85) 100%);"></div>
    </div>

    <!-- CONTENT -->
    <div class="relative z-10 min-h-screen flex flex-col items-center justify-center px-4 py-16">

        <!-- Top logo -->
        <div class="absolute top-6 left-6">
            <a href="/">
                @php $siteInfo = siteInfo(); @endphp
                @if ($siteInfo?->site_logo)
                    <img src="{{ asset('storage/' . $siteInfo->site_logo) }}" alt="{{ siteName() }}"
                         class="h-14 w-auto object-contain drop-shadow-lg"
                         onerror="this.style.display='none'">
                @else
                    <img src="{{ asset('storage/images/logo.png') }}" alt="{{ siteName() }}"
                        class="h-14 w-auto object-contain drop-shadow-lg">
                @endif
            </a>
        </div>

        <!-- Main card -->
        <div class="max-w-3xl w-full mx-auto flex flex-col lg:flex-row items-center gap-10 lg:gap-16">

            <!-- Don Papi illustration -->
            <div class="flex-shrink-0 animate-float animate-fade-in-up">
                <img src="{{ asset('storage/images/papi.png') }}" alt="{{ siteName() }}"
                    class="h-72 md:h-96 w-auto object-contain drop-shadow-[0_0_50px_rgba(229,43,43,0.3)] filter contrast-110">
            </div>

            <!-- Message box -->
            <div class="text-center lg:text-left">

                <!-- 404 badge -->
                <div
                    class="opacity-0 animate-fade-in-up delay-200 inline-flex items-center gap-2 bg-[#E52B2B]/20 border border-[#E52B2B]/40 text-[#E52B2B] text-xs font-bold uppercase tracking-[0.3em] px-4 py-2 rounded-full mb-6">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd" />
                    </svg>
                    Error 404 — Página no encontrada
                </div>

                <!-- Heading -->
                <h1
                    class="opacity-0 animate-fade-in-up delay-200 font-display text-5xl md:text-7xl text-white uppercase tracking-widest leading-none mb-2 smoke-glow">
                    ¡Que rollo raza!
                </h1>
                <div class="opacity-0 animate-fade-in-up delay-400 h-1 w-20 bg-[#E52B2B] rounded mb-6 lg:mx-0 mx-auto">
                </div>

                <!-- Subtitle -->
                <p class="opacity-0 animate-fade-in-up delay-400 text-gray-300 text-lg md:text-xl leading-relaxed mb-4">
                    ¿A dónde vas?
                </p>
                <p
                    class="opacity-0 animate-fade-in-up delay-400 text-gray-400 text-base md:text-lg leading-relaxed mb-10">
                    Esta página ya no existe o nunca existió.<br>
                    Mejor regresate a lo mejor de <span class="text-[#E52B2B] font-semibold">{{ siteName() }}</span> 🔥
                </p>

                <!-- CTA Button -->
                <div
                    class="opacity-0 animate-fade-in-up delay-600 flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    <a href="/"
                        class="inline-flex items-center justify-center gap-3 bg-[#E52B2B] hover:bg-red-700 text-white px-8 py-4 rounded font-bold uppercase tracking-widest text-sm transition-all duration-300 shadow-[0_0_25px_rgba(229,43,43,0.4)] hover:shadow-[0_0_40px_rgba(229,43,43,0.6)] hover:-translate-y-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Regresar al inicio
                    </a>
                    <a href="/menu"
                        class="inline-flex items-center justify-center gap-3 bg-transparent hover:bg-white/5 text-gray-300 hover:text-white border border-gray-600 hover:border-gray-400 px-8 py-4 rounded font-bold uppercase tracking-widest text-sm transition-all duration-300">
                        Ver el menú
                    </a>
                </div>

            </div>
        </div>

        <!-- Bottom decorative text -->
        <div
            class="opacity-0 animate-fade-in-up delay-600 absolute bottom-8 left-0 right-0 text-center text-gray-600 text-xs uppercase tracking-[0.4em]">
            {{ siteName() }}
        </div>

    </div>

</body>

</html>
