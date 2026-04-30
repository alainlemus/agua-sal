<?php

namespace App\Filament\Resources\PageResource\Pages;

use App\Filament\Resources\PageResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\URL;

class EditPage extends EditRecord
{
    protected static string $resource = PageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('preview')
                ->label('Vista previa')
                ->icon('heroicon-o-eye')
                ->color('info')
                ->modalHeading(fn () => 'Vista previa — ' . $this->record->title)
                ->modalContent(function () {
                    $url = URL::signedRoute('page.preview', [
                        'slug' => $this->record->slug,
                    ], now()->addMinutes(60));

                    return view('filament.modals.page-preview', ['url' => $url]);
                })
                ->modalWidth('7xl')
                ->modalSubmitAction(false)
                ->modalCancelActionLabel('Cerrar'),

            Actions\DeleteAction::make(),
        ];
    }
}
