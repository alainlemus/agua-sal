<div class="fi-header flex flex-col gap-y-2 px-4 py-6 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between gap-x-4">
        <div>
            <h1 class="fi-header-heading text-2xl font-bold tracking-tight text-gray-950 dark:text-white">
                Campañas de Reseñas
            </h1>
        </div>
        <div class="flex shrink-0 items-center gap-x-3">
            {{ $actions ?? '' }}
        </div>
    </div>

    {{-- Banner informativo --}}
    <div class="mt-2 rounded-xl border border-blue-500/30 bg-blue-500/10 p-4 text-sm text-blue-300 space-y-3">
        <p class="font-semibold text-blue-200 text-base">📋 ¿Cómo funciona el sistema de reseñas?</p>

        <div class="grid gap-3 sm:grid-cols-2">
            {{-- QR Permanente --}}
            <div class="rounded-lg bg-blue-500/10 border border-blue-400/20 p-3 space-y-1">
                <p class="font-semibold text-blue-300">🔵 QR Permanente <span class="text-xs font-normal text-blue-400">(recomendado)</span></p>
                <p class="text-blue-200/80">
                    Un solo código QR fijo que siempre apunta a <code class="text-xs bg-blue-900/40 px-1 rounded">/resena/activa</code>.
                    El cliente lo escanea, llena el formulario y recibe su regalo automáticamente.
                    <strong class="text-blue-200">No requiere que nadie del equipo haga nada.</strong>
                </p>
                <p class="text-blue-400/70 text-xs">→ Descarga el PDF o PNG desde el menú QR de la campaña activa e imprímelo en las mesas.</p>
            </div>

            {{-- Por token / mesero --}}
            <div class="rounded-lg bg-gray-500/10 border border-gray-400/20 p-3 space-y-1">
                <p class="font-semibold text-gray-400">⚪ Enlace por personal <span class="text-xs font-normal text-gray-500">(flujo manual)</span></p>
                <p class="text-gray-400/80">
                    El personal genera un enlace de un solo uso desde el panel y se lo muestra al cliente.
                    Expira después de un tiempo definido y no puede reutilizarse.
                </p>
                <p class="text-gray-500/70 text-xs">→ Usa "Generar enlace" en las acciones de cada campaña. Ya no es necesario con el QR permanente.</p>
            </div>
        </div>
    </div>
</div>
