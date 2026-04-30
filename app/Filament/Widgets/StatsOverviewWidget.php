<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use App\Models\ContactSubmission;
use App\Models\Menu;
use App\Models\Page;
use App\Models\PageView;
use App\Models\Product;
use App\Models\ReviewCampaign;
use App\Models\ReviewSubmission;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected static bool $isLazy = false;

    protected function getStats(): array
    {
        $totalReviews   = ReviewSubmission::count();
        $avgRating      = ReviewSubmission::avg('rating');
        $fiveStars      = ReviewSubmission::where('rating', 5)->count();
        $fourStars      = ReviewSubmission::where('rating', 4)->count();
        $pendingMessages = ContactSubmission::where('is_attended', false)->count();
        $totalMessages  = ContactSubmission::count();

        $visitasHoy    = PageView::whereDate('created_at', today())->count();
        $visitasSemana = PageView::where('created_at', '>=', now()->subDays(7))->count();
        $visitasTotal  = PageView::count();

        $avgFormatted = $avgRating ? number_format($avgRating, 1) . ' ⭐' : '—';

        return [
            Stat::make('Visitas hoy', $visitasHoy)
                ->description("Esta semana: {$visitasSemana}  |  Total: {$visitasTotal}")
                ->descriptionIcon('heroicon-m-eye')
                ->color('info'),

            Stat::make('Reseñas totales', $totalReviews)
                ->description("5⭐: {$fiveStars}  |  4⭐: {$fourStars}")
                ->descriptionIcon('heroicon-m-star')
                ->color('warning'),

            Stat::make('Calificación promedio', $avgFormatted)
                ->description('De todas las reseñas recibidas')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color($avgRating >= 4.5 ? 'success' : ($avgRating >= 3.5 ? 'warning' : 'danger')),

            Stat::make('Campañas activas', ReviewCampaign::where('is_active', true)->count())
                ->description('Campañas de reseñas en curso')
                ->descriptionIcon('heroicon-m-qr-code')
                ->color('info'),

            Stat::make('Platillos en menú', Product::where('is_active', true)->count())
                ->description(Category::count() . ' categorías  |  ' . Menu::count() . ' menús')
                ->descriptionIcon('heroicon-m-fire')
                ->color('success'),

            Stat::make('Mensajes de contacto', $totalMessages)
                ->description($pendingMessages > 0 ? "{$pendingMessages} sin leer" : 'Todos leídos ✓')
                ->descriptionIcon('heroicon-m-envelope')
                ->color($pendingMessages > 0 ? 'danger' : 'success'),

            Stat::make('Páginas publicadas', Page::where('is_published', true)->count())
                ->description(Page::count() . ' páginas en total')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('gray'),
        ];
    }
}
