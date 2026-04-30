<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PageViewResource\Pages;
use App\Models\PageView;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PageViewResource extends Resource
{
    protected static ?string $model = PageView::class;

    protected static ?string $modelLabel        = 'Visita';
    protected static ?string $pluralModelLabel  = 'Registro de Visitas';
    protected static ?string $navigationLabel   = 'Registro de Visitas';
    protected static ?string $navigationIcon    = 'heroicon-o-globe-alt';
    protected static ?string $navigationGroup   = 'Estadísticas';
    protected static ?int    $navigationSort    = 1;

    // Solo lectura — no se crean ni editan visitas manualmente
    public static function canCreate(): bool { return false; }

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(PageView::query()->latest())
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('country')
                    ->label('País')
                    ->formatStateUsing(function ($state, PageView $record) {
                        if (! $state) return '—';
                        $flag = $record->country_code
                            ? implode('', array_map(
                                fn ($c) => mb_chr(ord($c) - ord('A') + 0x1F1E6),
                                str_split(strtoupper($record->country_code))
                              ))
                            : '';
                        return $flag . ' ' . $state;
                    })
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('city')
                    ->label('Ciudad')
                    ->searchable()
                    ->sortable()
                    ->default('—'),

                Tables\Columns\TextColumn::make('ip_address')
                    ->label('IP')
                    ->fontFamily('mono')
                    ->searchable(),

                Tables\Columns\BadgeColumn::make('type')
                    ->label('Tipo')
                    ->colors([
                        'danger'   => 'home',
                        'warning'  => 'menu',
                        'success'  => 'pagina',
                        'primary'  => 'resena',
                        'info'     => 'contacto_enviado',
                        'gray'     => 'pagina_publicada',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'home'              => 'Inicio',
                        'menu'             => 'Menú',
                        'pagina'           => 'Página',
                        'resena'           => 'Reseña',
                        'contacto_enviado' => 'Contacto',
                        'pagina_publicada' => 'Publicación',
                        default            => $state,
                    }),

                Tables\Columns\TextColumn::make('label')
                    ->label('Página')
                    ->searchable()
                    ->default('—'),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label('Tipo de página')
                    ->options([
                        'home'             => 'Inicio',
                        'menu'             => 'Menú',
                        'pagina'           => 'Página CMS',
                        'resena'           => 'Reseña',
                        'contacto_enviado' => 'Contacto enviado',
                    ]),

                SelectFilter::make('country')
                    ->label('País')
                    ->options(fn () => PageView::whereNotNull('country')
                        ->distinct()
                        ->orderBy('country')
                        ->pluck('country', 'country')
                        ->toArray()
                    )
                    ->searchable(),

                Filter::make('sin_geolocalizar')
                    ->label('Sin geolocalizar')
                    ->query(fn (Builder $query) => $query->whereNull('country'))
                    ->toggle(),

                Tables\Filters\Filter::make('created_at')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('desde')->label('Desde'),
                        \Filament\Forms\Components\DatePicker::make('hasta')->label('Hasta'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['desde'], fn ($q, $v) => $q->whereDate('created_at', '>=', $v))
                            ->when($data['hasta'], fn ($q, $v) => $q->whereDate('created_at', '<=', $v));
                    }),
            ])
            ->filtersLayout(Tables\Enums\FiltersLayout::AboveContent)
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->paginated([25, 50, 100]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPageViews::route('/'),
        ];
    }
}
