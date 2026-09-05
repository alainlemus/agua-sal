<x-filament-panels::header
    heading="Campañas de Reseñas"
    :actions="$actions ?? []"
/>

<div class="rc-banner-wrap">
    <style>
        .rc-banner-wrap {
            padding: 0 1rem;
        }

        .rc-banner {
            border-radius: 0.75rem;
            border: 1px solid rgba(59, 130, 246, 0.3);
            background-color: rgba(59, 130, 246, 0.1);
            padding: 1rem;
            font-size: 0.875rem;
            color: #93c5fd;
        }

        .rc-banner__title {
            font-weight: 600;
            color: #bfdbfe;
            font-size: 1rem;
            margin: 0 0 0.75rem;
        }

        .rc-banner__grid {
            display: grid;
            gap: 0.75rem;
        }

        @media (min-width: 640px) {
            .rc-banner__grid {
                grid-template-columns: 1fr 1fr;
            }
        }

        .rc-banner__card {
            border-radius: 0.5rem;
            padding: 0.75rem;
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .rc-banner__card--primary {
            background-color: rgba(59, 130, 246, 0.1);
            border: 1px solid rgba(96, 165, 250, 0.2);
        }

        .rc-banner__card--muted {
            background-color: rgba(107, 114, 128, 0.1);
            border: 1px solid rgba(156, 163, 175, 0.2);
        }

        .rc-banner__card-title {
            font-weight: 600;
        }

        .rc-banner__card--primary .rc-banner__card-title {
            color: #93c5fd;
        }

        .rc-banner__card--muted .rc-banner__card-title {
            color: #9ca3af;
        }

        .rc-banner__badge {
            font-size: 0.75rem;
            font-weight: 400;
        }

        .rc-banner__card--primary .rc-banner__badge {
            color: #60a5fa;
        }

        .rc-banner__card--muted .rc-banner__badge {
            color: #6b7280;
        }

        .rc-banner__card--primary p {
            color: rgba(191, 219, 254, 0.8);
        }

        .rc-banner__card--muted p {
            color: rgba(156, 163, 175, 0.8);
        }

        .rc-banner__code {
            font-size: 0.75rem;
            background-color: rgba(30, 58, 138, 0.4);
            padding: 0.1rem 0.35rem;
            border-radius: 0.25rem;
        }

        .rc-banner__hint {
            font-size: 0.75rem;
        }

        .rc-banner__card--primary .rc-banner__hint {
            color: rgba(96, 165, 250, 0.7);
        }

        .rc-banner__card--muted .rc-banner__hint {
            color: rgba(107, 114, 128, 0.7);
        }
    </style>

    <div class="rc-banner">
        <p class="rc-banner__title">📋 ¿Cómo funciona el sistema de reseñas?</p>

        <div class="rc-banner__grid">
            <div class="rc-banner__card rc-banner__card--primary">
                <p class="rc-banner__card-title">🔵 QR Permanente <span class="rc-banner__badge">(recomendado)</span></p>
                <p>
                    Un solo código QR fijo que siempre apunta a <code class="rc-banner__code">/resena/activa</code>.
                    El cliente lo escanea, llena el formulario y recibe su regalo automáticamente.
                    <strong>No requiere que nadie del equipo haga nada.</strong>
                </p>
                <p class="rc-banner__hint">→ Descarga el PDF o PNG desde el menú QR de la campaña activa e imprímelo en las mesas.</p>
            </div>

            <div class="rc-banner__card rc-banner__card--muted">
                <p class="rc-banner__card-title">⚪ Enlace por personal <span class="rc-banner__badge">(flujo manual)</span></p>
                <p>
                    El personal genera un enlace de un solo uso desde el panel y se lo muestra al cliente.
                    Expira después de un tiempo definido y no puede reutilizarse.
                </p>
                <p class="rc-banner__hint">→ Usa "Generar enlace" en las acciones de cada campaña. Ya no es necesario con el QR permanente.</p>
            </div>
        </div>
    </div>
</div>
