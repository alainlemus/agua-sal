<?php

namespace App\Filament\Resources\ReviewCampaignResource\Pages;

use App\Filament\Resources\ReviewCampaignResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReviewCampaign extends EditRecord
{
    protected static string $resource = ReviewCampaignResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
