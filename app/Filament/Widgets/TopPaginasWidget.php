<?php

namespace App\Filament\Widgets;

use App\Models\PageView;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Model;

class TopPaginasWidget extends BaseWidget
{
    protected static ?string $heading    = 'Páginas más visitadas (total)';
    protected static ?int    $sort       = 3;
    protected int | string | array $columnSpan = 'full';

    // Filament necesita una clave única por fila; la generamos del slug compuesto
    public function getTableRecordKey(Model | array $record): string
    {
        return md5($record->type . '|' . $record->slug);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                PageView::query()
                    ->selectRaw('type, slug, label, COUNT(*) as total')
                    ->groupBy('type', 'slug', 'label')
                    ->orderByDesc('total')
                    ->limit(15)
            )
            ->columns([
                Tables\Columns\TextColumn::make('label')
                    ->label('Página / Sección')
                    ->searchable()
                    ->weight('bold'),

                Tables\Columns\BadgeColumn::make('type')
                    ->label('Tipo')
                    ->colors([
                        'danger'  => 'home',
                        'warning' => 'menu',
                        'success' => 'pagina',
                        'primary' => 'resena',
                        'info'    => 'contacto_enviado',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'home'             => 'Inicio',
                        'menu'             => 'Menú',
                        'pagina'           => 'Página',
                        'resena'           => 'Reseña',
                        'contacto_enviado' => 'Contacto',
                        default            => $state,
                    }),

                Tables\Columns\TextColumn::make('total')
                    ->label('Visitas')
                    ->alignEnd()
                    ->sortable(),
            ])
            ->paginated(false)
            ->striped();
    }
}
