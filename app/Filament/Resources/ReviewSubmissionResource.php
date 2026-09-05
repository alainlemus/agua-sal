<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReviewSubmissionResource\Pages;
use App\Models\ReviewSubmission;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Infolists;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Actions;
use Filament\Tables;
use Filament\Tables\Table;

class ReviewSubmissionResource extends Resource
{
    protected static ?string $model = ReviewSubmission::class;

    protected static ?string $modelLabel       = 'Reseña';
    protected static ?string $pluralModelLabel = 'Reseñas de Clientes';
    protected static ?string $navigationLabel  = 'Reseñas';
    protected static string | \BackedEnum | null $navigationIcon   = 'heroicon-o-star';
    protected static string | \UnitEnum | null $navigationGroup  = 'Reseñas & QR';
    protected static ?int    $navigationSort   = 2;

    /** Badge de regalos sin canjear */
    public static function getNavigationBadge(): ?string
    {
        $count = ReviewSubmission::pending()->count();
        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function form(Schema $form): Schema
    {
        return $form->schema([
            Forms\Components\Placeholder::make('readonly_note')
                ->content('Las reseñas son de solo lectura. Usa las acciones de la tabla para canjear regalos.')
                ->columnSpanFull(),
        ]);
    }

    public static function infolist(Schema $infolist): Schema
    {
        return $infolist->schema([
            \Filament\Schemas\Components\Section::make('Datos del Cliente')->schema([
                \Filament\Schemas\Components\Grid::make(2)->schema([
                    Infolists\Components\TextEntry::make('customer_name')->label('Nombre'),
                    Infolists\Components\TextEntry::make('customer_email')->label('Correo')->copyable(),
                    Infolists\Components\TextEntry::make('campaign.name')->label('Campaña'),
                    Infolists\Components\TextEntry::make('created_at')->label('Enviada el')->dateTime('d/m/Y H:i'),
                ]),
            ]),

            \Filament\Schemas\Components\Section::make('Reseña')->schema([
                Infolists\Components\TextEntry::make('rating')
                    ->label('Calificación')
                    ->formatStateUsing(fn ($state) => str_repeat('⭐', $state) . " ({$state}/5)"),
                Infolists\Components\TextEntry::make('comment')->label('Comentario')->default('Sin comentario.')->columnSpanFull(),
            ]),

            \Filament\Schemas\Components\Section::make('Regalo')->schema([
                \Filament\Schemas\Components\Grid::make(3)->schema([
                    Infolists\Components\TextEntry::make('gift_code')
                        ->label('Código')
                        ->weight('bold')
                        ->copyable(),
                    Infolists\Components\IconEntry::make('gift_redeemed')
                        ->label('¿Canjeado?')
                        ->boolean(),
                    Infolists\Components\TextEntry::make('gift_redeemed_at')
                        ->label('Canjeado el')
                        ->formatStateUsing(fn ($state) => $state ? \Carbon\Carbon::parse($state)->format('d/m/Y H:i') : '—'),
                    Infolists\Components\TextEntry::make('gift_redeemed_by')
                        ->label('Cobrado por')
                        ->placeholder('—'),
                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\IconColumn::make('gift_redeemed')
                    ->label('Regalo')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-gift')
                    ->trueColor('success')
                    ->falseColor('warning')
                    ->width(40),

                Tables\Columns\TextColumn::make('customer_name')
                    ->label('Cliente')
                    ->searchable()
                    ->weight(fn ($record) => ! $record->gift_redeemed ? 'bold' : 'normal'),

                Tables\Columns\TextColumn::make('customer_email')
                    ->label('Correo')
                    ->searchable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('rating')
                    ->label('⭐')
                    ->formatStateUsing(fn ($state) => str_repeat('⭐', $state))
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('gift_code')
                    ->label('Código')
                    ->badge()
                    ->color('warning')
                    ->copyable(),

                Tables\Columns\TextColumn::make('campaign.name')
                    ->label('Campaña')
                    ->color('gray'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('gift_redeemed')
                    ->label('Estado del regalo')
                    ->trueLabel('Solo canjeados')
                    ->falseLabel('Pendientes de canjear')
                    ->placeholder('Todos'),
                Tables\Filters\SelectFilter::make('review_campaign_id')
                    ->label('Campaña')
                    ->relationship('campaign', 'name'),
                Tables\Filters\SelectFilter::make('rating')
                    ->label('Calificación')
                    ->options([1=>'⭐',2=>'⭐⭐',3=>'⭐⭐⭐',4=>'⭐⭐⭐⭐',5=>'⭐⭐⭐⭐⭐']),
            ])
            ->actions([
                Actions\ViewAction::make()->label('Ver'),

                Actions\Action::make('redeem')
                    ->label('Canjear regalo')
                    ->icon('heroicon-o-gift')
                    ->color('success')
                    ->visible(fn ($record) => ! $record->gift_redeemed)
                    ->requiresConfirmation()
                    ->modalHeading('¿Canjear regalo?')
                    ->modalDescription(fn ($record) => "Código: {$record->gift_code} — {$record->customer_name}")
                    ->form([
                        Forms\Components\TextInput::make('redeemed_by')
                            ->label('Nombre del empleado que lo cobra')
                            ->required()
                            ->placeholder('ej. María'),
                    ])
                    ->action(function ($record, array $data) {
                        $record->redeem($data['redeemed_by']);
                        Notification::make()
                            ->title('¡Regalo canjeado! 🎁')
                            ->body("Código {$record->gift_code} registrado como usado.")
                            ->success()
                            ->send();
                    }),

                Actions\Action::make('unredeem')
                    ->label('Deshacer canje')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->color('gray')
                    ->visible(fn ($record) => $record->gift_redeemed)
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->update(['gift_redeemed' => false, 'gift_redeemed_at' => null, 'gift_redeemed_by' => null]);
                        Notification::make()->title('Canje deshecho')->warning()->send();
                    }),

                Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->striped()
            ->poll('30s');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReviewSubmissions::route('/'),
            'view'  => Pages\ViewReviewSubmission::route('/{record}'),
        ];
    }
}
