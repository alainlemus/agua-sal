<?php

namespace App\Filament\Resources\ReviewCampaignResource\Pages;

use App\Filament\Resources\ReviewCampaignResource;
use Filament\Resources\Pages\CreateRecord;

class CreateReviewCampaign extends CreateRecord
{
    protected static string $resource = ReviewCampaignResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
