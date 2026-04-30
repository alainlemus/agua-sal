<x-filament-widgets::widget>
    @php
        $count = $this->getUnattendedCount();
        $messages = $this->getLatestMessages();
    @endphp

    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center gap-2">
                <span>Mensajes de Contacto</span>
                @if ($count > 0)
                    <span class="inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold rounded-full bg-red-600 text-white">
                        {{ $count }}
                    </span>
                @endif
            </div>
        </x-slot>

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
    </x-filament::section>
</x-filament-widgets::widget>
