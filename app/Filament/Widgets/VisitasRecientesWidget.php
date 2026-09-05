<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\PageViewResource;
use App\Models\PageView;
use Filament\Actions;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Model;

class VisitasRecientesWidget extends BaseWidget
{
    protected static ?string $heading = 'Visitas recientes';
    protected static ?int    $sort    = 5;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                PageView::query()->latest()->limit(10)
            )
            ->headerActions([
                \Filament\Actions\Action::make('ver_todas')
                    ->label('Ver todas')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(PageViewResource::getUrl('index'))
                    ->color('gray'),
            ])
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('country')
                    ->label('País')
                    ->formatStateUsing(function ($state, PageView $record): string {
                        if (! $state) {
                            return in_array($record->ip_address, ['127.0.0.1', '::1'])
                                ? '— local'
                                : '— pendiente';
                        }
                        $flag = $record->country_code
                            ? implode('', array_map(
                                fn ($c) => mb_chr(ord($c) - ord('A') + 0x1F1E6),
                                str_split(strtoupper($record->country_code))
                              ))
                            : '';
                        return trim($flag . ' ' . $state);
                    }),

                Tables\Columns\TextColumn::make('city')
                    ->label('Ciudad')
                    ->default('—'),

                Tables\Columns\TextColumn::make('ip_address')
                    ->label('IP')
                    ->fontFamily('mono'),

                Tables\Columns\BadgeColumn::make('type')
                    ->label('Tipo')
                    ->colors([
                        'danger'  => 'home',
                        'warning' => 'menu',
                        'success' => 'pagina',
                        'primary' => 'resena',
                        'info'    => 'contacto_enviado',
                        'gray'    => 'pagina_publicada',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'home'             => 'Inicio',
                        'menu'             => 'Menú',
                        'pagina'           => 'Página',
                        'resena'           => 'Reseña',
                        'contacto_enviado' => 'Contacto',
                        'pagina_publicada' => 'Publicación',
                        default            => $state,
                    }),

                Tables\Columns\TextColumn::make('label')
                    ->label('Página')
                    ->default('—'),
            ])
            ->paginated(false)
            ->striped();
    }
}
