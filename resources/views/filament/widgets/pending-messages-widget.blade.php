<x-filament-widgets::widget>
    @php
        $count = $this->getUnattendedCount();
        $messages = $this->getLatestMessages();
    @endphp

    {{--
        Nota: este widget usa clases utilitarias de Tailwind que NO existen en
        el bundle CSS del panel de admin de Filament v4 (el panel carga su
        propio CSS precompilado, no el build de Tailwind del sitio). Por eso
        se reimplementa aquí, con ámbito acotado a .pmw-wrap, el subconjunto
        exacto de utilidades que este archivo usa.
    --}}
    <style>
        .pmw-wrap .flex { display: flex; }
        .pmw-wrap .flex-col { flex-direction: column; }
        .pmw-wrap .flex-1 { flex: 1 1 0%; }
        .pmw-wrap .flex-shrink-0 { flex-shrink: 0; }
        .pmw-wrap .inline-flex { display: inline-flex; }
        .pmw-wrap .inline-block { display: inline-block; }
        .pmw-wrap .items-center { align-items: center; }
        .pmw-wrap .items-start { align-items: flex-start; }
        .pmw-wrap .justify-center { justify-content: center; }
        .pmw-wrap .gap-2 { gap: 0.5rem; }
        .pmw-wrap .gap-3 { gap: 0.75rem; }
        .pmw-wrap .space-y-3 > :not([hidden]) ~ :not([hidden]) { margin-top: 0.75rem; }
        .pmw-wrap .min-w-0 { min-width: 0px; }
        .pmw-wrap .truncate { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .pmw-wrap .overflow-hidden { overflow: hidden; }
        .pmw-wrap .h-2 { height: 0.5rem; }
        .pmw-wrap .w-2 { width: 0.5rem; }
        .pmw-wrap .h-4 { height: 1rem; }
        .pmw-wrap .w-4 { width: 1rem; }
        .pmw-wrap .h-12 { height: 3rem; }
        .pmw-wrap .w-12 { width: 3rem; }
        .pmw-wrap .px-2 { padding-left: 0.5rem; padding-right: 0.5rem; }
        .pmw-wrap .px-4 { padding-left: 1rem; padding-right: 1rem; }
        .pmw-wrap .py-0\.5 { padding-top: 0.125rem; padding-bottom: 0.125rem; }
        .pmw-wrap .py-3 { padding-top: 0.75rem; padding-bottom: 0.75rem; }
        .pmw-wrap .py-8 { padding-top: 2rem; padding-bottom: 2rem; }
        .pmw-wrap .mb-3 { margin-bottom: 0.75rem; }
        .pmw-wrap .mt-0\.5 { margin-top: 0.125rem; }
        .pmw-wrap .font-bold { font-weight: 700; }
        .pmw-wrap .font-medium { font-weight: 500; }
        .pmw-wrap .font-normal { font-weight: 400; }
        .pmw-wrap .text-sm { font-size: 0.875rem; line-height: 1.25rem; }
        .pmw-wrap .text-xs { font-size: 0.75rem; line-height: 1rem; }
        .pmw-wrap .text-center { text-align: center; }
        .pmw-wrap .text-right { text-align: right; }
        .pmw-wrap .transition-colors { transition-property: color, background-color, border-color; transition-duration: 150ms; }
        .pmw-wrap .rounded-full { border-radius: 9999px; }
        .pmw-wrap .rounded-lg { border-radius: 0.5rem; }
        .pmw-wrap .border,
        .pmw-wrap .border-gray-200,
        .pmw-wrap .dark\:border-gray-700 { border-width: 1px; border-style: solid; border-color: #374151; }
        .pmw-wrap .divide-gray-100 > :not([hidden]) ~ :not([hidden]),
        .pmw-wrap .dark\:divide-gray-800 > :not([hidden]) ~ :not([hidden]) { border-top-width: 1px; border-top-style: solid; border-top-color: #1f2937; }
        .pmw-wrap .bg-white,
        .pmw-wrap .dark\:bg-gray-900 { background-color: #111827; }
        .pmw-wrap .hover\:bg-gray-50:hover,
        .pmw-wrap .dark\:hover\:bg-gray-800:hover { background-color: #1f2937; }
        .pmw-wrap .bg-red-500 { background-color: #ef4444; }
        .pmw-wrap .bg-red-600 { background-color: #dc2626; }
        .pmw-wrap .text-white { color: #ffffff; }
        .pmw-wrap .text-gray-400 { color: #9ca3af; }
        .pmw-wrap .text-gray-500 { color: #6b7280; }
        .pmw-wrap .text-gray-600,
        .pmw-wrap .dark\:text-gray-400 { color: #9ca3af; }
        .pmw-wrap .text-gray-900,
        .pmw-wrap .dark\:text-gray-100 { color: #f3f4f6; }
        .pmw-wrap .text-green-500 { color: #22c55e; }
        .pmw-wrap .text-red-500 { color: #ef4444; }
        .pmw-wrap .text-amber-600 { color: #d97706; }
        .pmw-wrap .hover\:text-amber-500:hover { color: #f59e0b; }
    </style>

    <x-filament::section>
        <x-slot name="heading">
            <div class="pmw-wrap">
                <div class="flex items-center gap-2">
                    <span>Mensajes de Contacto</span>
                    @if ($count > 0)
                        <span class="inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold rounded-full bg-red-600 text-white">
                            {{ $count }}
                        </span>
                    @endif
                </div>
            </div>
        </x-slot>

        <div class="pmw-wrap">
        @if ($count === 0)
            <div class="flex flex-col items-center justify-center py-8 text-center">
                <x-filament::icon
                    icon="heroicon-o-check-circle"
                    class="w-12 h-12 text-green-500 mb-3"
                />
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">
                    Todo al día — no hay mensajes pendientes
                </p>
            </div>
        @else
            <div class="space-y-3">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Tienes <strong class="text-red-500">{{ $count }} {{ $count === 1 ? 'mensaje pendiente' : 'mensajes pendientes' }}</strong> sin atender.
                </p>

                <div class="divide-y divide-gray-100 dark:divide-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                    @foreach ($messages as $message)
                        <a
                            href="{{ route('filament.admin.resources.contact-submissions.view', $message) }}"
                            class="flex items-start gap-3 px-4 py-3 bg-white dark:bg-gray-900 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors"
                        >
                            <div class="flex-shrink-0 mt-0.5">
                                <span class="inline-block w-2 h-2 rounded-full bg-red-500"></span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">
                                    {{ $message->sender_name ?: 'Sin nombre' }}
                                    @if ($message->sender_email)
                                        <span class="font-normal text-gray-500 dark:text-gray-400">&lt;{{ $message->sender_email }}&gt;</span>
                                    @endif
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                    {{ $message->form_title }} &mdash; {{ $message->created_at->diffForHumans() }}
                                </p>
                            </div>
                            <x-filament::icon
                                icon="heroicon-m-chevron-right"
                                class="w-4 h-4 text-gray-400 flex-shrink-0 mt-0.5"
                            />
                        </a>
                    @endforeach
                </div>

                @if ($count > 5)
                    <div class="text-right">
                        <a
                            href="{{ route('filament.admin.resources.contact-submissions.index', ['tableTab' => 'pending']) }}"
                            class="text-sm text-amber-600 hover:text-amber-500 font-medium"
                        >
                            Ver todos los {{ $count }} mensajes &rarr;
                        </a>
                    </div>
                @else
                    <div class="text-right">
                        <a
                            href="{{ route('filament.admin.resources.contact-submissions.index') }}"
                            class="text-sm text-amber-600 hover:text-amber-500 font-medium"
                        >
                            Ver todos los mensajes &rarr;
                        </a>
                    </div>
                @endif
            </div>
        @endif
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
