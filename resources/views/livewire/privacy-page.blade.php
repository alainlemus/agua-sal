<div>
    {{-- Header --}}
    <div class="relative pt-36 pb-14 overflow-hidden bg-[var(--bg_section)]">
        <div class="absolute inset-0 bg-gradient-to-br from-black via-[var(--bg_primary)] to-black"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center pt-10">
            <h1 class="text-3xl sm:text-5xl md:text-7xl font-['Anton'] text-white uppercase tracking-widest drop-shadow-2xl">
                {{ $siteInfo?->privacy_policy_title ?: 'Aviso de Privacidad' }}
            </h1>
            <div class="h-1 w-20 bg-[var(--accent)] mx-auto mt-6 rounded"></div>
        </div>
    </div>

    {{-- Contenido --}}
    <section class="py-20 bg-[var(--bg_primary)]">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            @if ($siteInfo?->privacy_policy_content)
                <div class="privacy-content text-gray-300 text-base leading-relaxed">
                    {!! $siteInfo->privacy_policy_content !!}
                </div>
            @else
                {{-- Contenido por defecto si aún no se ha editado --}}
                <div class="privacy-content text-gray-300 text-base leading-relaxed">

                    <p>En <strong>{{ siteName() }}</strong>, somos responsables del tratamiento de sus datos personales, los cuales serán protegidos conforme a lo dispuesto en la <em>Ley Federal de Protección de Datos Personales en Posesión de los Particulares</em> y demás normativa aplicable.</p>

                    <h2>¿Qué datos recopilamos?</h2>
                    <p>Recopilamos únicamente los datos que usted nos proporciona de forma voluntaria a través de nuestros formularios de contacto y de reseñas: nombre, correo electrónico y el contenido del mensaje.</p>

                    <h2>¿Para qué usamos sus datos?</h2>
                    <ul>
                        <li>Responder a sus solicitudes de información o reservaciones.</li>
                        <li>Gestionar las reseñas y opiniones sobre nuestros servicios.</li>
                        <li>Mejorar la experiencia en nuestro sitio web.</li>
                    </ul>

                    <h2>Cookies</h2>
                    <p>Este sitio utiliza cookies técnicas para su correcto funcionamiento. No utilizamos cookies de seguimiento de terceros sin su consentimiento.</p>

                    <h2>Derechos ARCO</h2>
                    <p>Usted tiene derecho a <strong>Acceder, Rectificar, Cancelar u Oponerse</strong> al tratamiento de sus datos personales. Para ejercer estos derechos, contáctenos a través de nuestro formulario de contacto o al correo electrónico indicado en este sitio.</p>

                    <h2>Cambios al aviso</h2>
                    <p>Nos reservamos el derecho de actualizar este aviso en cualquier momento. Cualquier cambio será publicado en esta misma página.</p>

                    <p class="text-sm text-gray-500">Última actualización: {{ date('d \d\e F \d\e Y') }}</p>
                </div>
            @endif

            <div class="mt-12 pt-8 border-t border-gray-800">
                <a href="/"
                    class="inline-flex items-center gap-2 text-[var(--accent)] hover:text-[var(--button_hover)] font-semibold transition-colors text-sm uppercase tracking-widest">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Volver al inicio
                </a>
            </div>
        </div>
    </section>
</div>
