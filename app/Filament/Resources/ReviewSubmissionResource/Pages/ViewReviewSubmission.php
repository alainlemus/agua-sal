<?php

namespace App\Filament\Resources\ReviewSubmissionResource\Pages;

use App\Filament\Resources\ReviewSubmissionResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewReviewSubmission extends ViewRecord
{
    protected static string $resource = ReviewSubmissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('redeem')
                ->label('Canjear regalo')
                ->icon('heroicon-o-gift')
                ->color('success')
                ->visible(fn () => ! $this->record->gift_redeemed)
                ->requiresConfirmation()
                ->form([
                    \Filament\Forms\Components\TextInput::make('redeemed_by')
                        ->label('Empleado que cobra el regalo')
                        ->required(),
                ])
                ->action(function (array $data) {
                    $this->record->redeem($data['redeemed_by']);
                    \Filament\Notifications\Notification::make()
                        ->title('¡Regalo canjeado!')
                        ->success()
                        ->send();
                    $this->refreshFormData([]);
                }),
        ];
    }
}
