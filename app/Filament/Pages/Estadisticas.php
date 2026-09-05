<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Estadisticas extends BaseDashboard
{
    protected static string | \BackedEnum | null $navigationIcon  = 'heroicon-o-chart-bar';
    protected static ?string $navigationLabel = 'Estadísticas';
    protected static string | \UnitEnum | null $navigationGroup = 'Estadísticas';
    protected static ?string $title           = 'Estadísticas';
    protected static ?int    $navigationSort  = 0;

    public function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\StatsOverviewWidget::class,
            \App\Filament\Widgets\VisitasTendenciaWidget::class,
            \App\Filament\Widgets\TopPaginasWidget::class,
            \App\Filament\Widgets\VisitantesWidget::class,
            \App\Filament\Widgets\VisitasRecientesWidget::class,
            \App\Filament\Widgets\RecentReviewsWidget::class,
            \App\Filament\Widgets\PendingMessagesWidget::class,
        ];
    }

    public function getColumns(): array | int
    {
        return 2;
    }
}
