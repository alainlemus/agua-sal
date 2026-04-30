<?php

namespace App\Filament\Widgets;

use App\Models\ReviewSubmission;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentReviewsWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    protected static ?string $heading = 'Últimas Reseñas Recibidas';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                ReviewSubmission::query()->latest()->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('customer_name')
                    ->label('Cliente')
                    ->searchable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('rating')
                    ->label('Calificación')
                    ->formatStateUsing(function (int $state): string {
                        return str_repeat('⭐', $state) . " ({$state}/5)";
                    })
                    ->badge()
                    ->color(fn (int $state): string => match (true) {
                        $state === 5 => 'success',
                        $state === 4 => 'warning',
                        $state === 3 => 'info',
                        default      => 'danger',
                    }),

                Tables\Columns\TextColumn::make('comment')
                    ->label('Comentario')
                    ->limit(80)
                    ->placeholder('Sin comentario')
                    ->wrap(),

                Tables\Columns\TextColumn::make('campaign.name')
                    ->label('Campaña')
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('gift_redeemed')
                    ->label('Regalo')
                    ->formatStateUsing(fn ($state): string => $state ? '✅ Canjeado' : '⏳ Pendiente')
                    ->badge()
                    ->color(fn ($state): string => $state ? 'success' : 'gray'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha de visita')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([5, 10, 25]);
    }
}
