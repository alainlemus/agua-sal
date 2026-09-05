<?php

namespace App\Filament\Widgets;

use App\Models\PageView;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class VisitasTendenciaWidget extends ChartWidget
{
    protected ?string $heading     = 'Visitas de los últimos 14 días';
    protected static ?int    $sort        = 2;
    protected int | string | array $columnSpan  = 'full';
    protected ?string $maxHeight   = '280px';
    protected ?string $pollingInterval = '30s';

    protected function getData(): array
    {
        $days   = collect(range(13, 0))->map(fn ($d) => Carbon::today()->subDays($d));
        $labels = $days->map(fn ($d) => $d->translatedFormat('D d/m'))->toArray();

        $datasets = [];

        // ── Tipos fijos ──────────────────────────────────────────────────────
        $fixedTypes = [
            'home'             => ['label' => 'Inicio',             'color' => '#E52B2B'],
            'menu'             => ['label' => 'Menú',               'color' => '#f97316'],
            'resena'           => ['label' => 'Reseñas',            'color' => '#a855f7'],
            'contacto_enviado' => ['label' => 'Formulario enviado', 'color' => '#3b82f6'],
        ];

        foreach ($fixedTypes as $type => $meta) {
            $counts = $days->map(fn ($day) =>
                PageView::where('type', $type)
                    ->whereDate('created_at', $day)
                    ->count()
            )->toArray();

            $datasets[] = [
                'label'           => $meta['label'],
                'data'            => $counts,
                'borderColor'     => $meta['color'],
                'backgroundColor' => $meta['color'] . '33',
                'fill'            => true,
                'tension'         => 0.4,
                'pointRadius'     => 3,
            ];
        }

        // ── Páginas CMS dinámicas (type = 'pagina', una línea por slug) ───────
        // Paleta de colores para las páginas dinámicas
        $palette = [
            '#006847', '#10b981', '#06b6d4', '#8b5cf6',
            '#ec4899', '#f59e0b', '#84cc16', '#14b8a6',
        ];

        // Obtener todos los slugs con visitas en los últimos 14 días
        $paginaSlugs = PageView::where('type', 'pagina')
            ->where('created_at', '>=', Carbon::today()->subDays(13))
            ->select('slug', 'label')
            ->distinct()
            ->get()
            ->unique('slug');

        foreach ($paginaSlugs as $index => $pv) {
            $color  = $palette[$index % count($palette)];
            $nombre = $pv->label ?: $pv->slug;

            $counts = $days->map(fn ($day) =>
                PageView::where('type', 'pagina')
                    ->where('slug', $pv->slug)
                    ->whereDate('created_at', $day)
                    ->count()
            )->toArray();

            $datasets[] = [
                'label'           => $nombre,
                'data'            => $counts,
                'borderColor'     => $color,
                'backgroundColor' => $color . '33',
                'fill'            => true,
                'tension'         => 0.4,
                'pointRadius'     => 3,
            ];
        }

        return [
            'labels'   => $labels,
            'datasets' => $datasets,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => ['display' => true, 'position' => 'bottom'],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks'       => ['stepSize' => 1],
                ],
            ],
        ];
    }
}
