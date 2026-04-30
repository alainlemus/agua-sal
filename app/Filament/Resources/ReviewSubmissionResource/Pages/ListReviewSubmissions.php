<?php

namespace App\Filament\Resources\ReviewSubmissionResource\Pages;

use App\Filament\Resources\ReviewSubmissionResource;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListReviewSubmissions extends ListRecords
{
    protected static string $resource = ReviewSubmissionResource::class;

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Todos')->icon('heroicon-o-star'),
            'pending' => Tab::make('Sin canjear')
                ->icon('heroicon-o-gift')
                ->badgeColor('warning')
                ->badge(fn () => \App\Models\ReviewSubmission::pending()->count())
                ->modifyQueryUsing(fn ($query) => $query->where('gift_redeemed', false)),
            'redeemed' => Tab::make('Canjeados')
                ->icon('heroicon-o-check-circle')
                ->modifyQueryUsing(fn ($query) => $query->where('gift_redeemed', true)),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
