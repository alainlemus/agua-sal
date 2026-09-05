<?php

namespace App\Filament\Resources\ContactSubmissionResource\Pages;

use App\Filament\Resources\ContactSubmissionResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;

class ListContactSubmissions extends ListRecords
{
    protected static string $resource = ContactSubmissionResource::class;

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Todos')
                ->icon('heroicon-o-inbox'),
            'pending' => Tab::make('Pendientes')
                ->icon('heroicon-o-clock')
                ->badgeColor('danger')
                ->badge(fn () => \App\Models\ContactSubmission::unattended()->count())
                ->modifyQueryUsing(fn ($query) => $query->where('is_attended', false)),
            'attended' => Tab::make('Atendidos')
                ->icon('heroicon-o-check-circle')
                ->modifyQueryUsing(fn ($query) => $query->where('is_attended', true)),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
