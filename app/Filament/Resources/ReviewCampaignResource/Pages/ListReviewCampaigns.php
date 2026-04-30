<?php

namespace App\Filament\Resources\ReviewCampaignResource\Pages;

use App\Filament\Resources\ReviewCampaignResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\IconSize;
use Illuminate\Contracts\View\View;

class ListReviewCampaigns extends ListRecords
{
    protected static string $resource = ReviewCampaignResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Nueva campaña'),
        ];
    }

    public function getHeader(): ?View
    {
        return view('filament.review-campaigns-header');
    }
}
