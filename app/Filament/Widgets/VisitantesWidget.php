<?php

namespace App\Filament\Widgets;

use App\Models\PageView;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\DB;

class VisitantesWidget extends Widget
{
    protected string $view  = 'filament.widgets.visitantes-widget';
    protected static ?int $sort    = 4;
    protected int | string | array $columnSpan = 'full';
    protected ?string $pollingInterval  = null;

    public function getTopPaises(): \Illuminate\Support\Collection
    {
        return PageView::query()
            ->select('country', 'country_code', DB::raw('COUNT(*) as total'))
            ->whereNotNull('country')
            ->groupBy('country', 'country_code')
            ->orderByDesc('total')
            ->limit(10)
            ->get();
    }

    public function getTotalVisitas(): int
    {
        return PageView::count();
    }

    public function getTotalGeolocalizadas(): int
    {
        return PageView::whereNotNull('country')->count();
    }

    protected function getViewData(): array
    {
        $topPaises    = $this->getTopPaises();
        $maxVisitas   = $topPaises->max('total') ?: 1;
        $totalVisitas = $this->getTotalVisitas();
        $totalGeo     = $this->getTotalGeolocalizadas();

        return compact('topPaises', 'maxVisitas', 'totalVisitas', 'totalGeo');
    }
}
