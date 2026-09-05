<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactSubmissionResource\Pages;
use App\Models\ContactSubmission;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Infolists;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Actions;
use Filament\Tables;
use Filament\Tables\Table;

class ContactSubmissionResource extends Resource
{
    protected static ?string $model = ContactSubmission::class;

    protected static ?string $modelLabel        = 'Mensaje';
    protected static ?string $pluralModelLabel  = 'Mensajes de Contacto';
    protected static ?string $navigationLabel   = 'Mensajes';
    protected static string | \BackedEnum | null $navigationIcon    = 'heroicon-o-envelope';
    protected static string | \UnitEnum | null $navigationGroup   = 'Comunicación';
    protected static ?int    $navigationSort    = 1;

    /** Badge rojo con conteo de no atendidos */
    public static function getNavigationBadge(): ?string
    {
        $count = ContactSubmission::unattended()->count();
        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }

    // Solo lectura en Infolist — no editamos campos del mensaje recibido
    public static function form(Schema $form): Schema
    {
        return $form->schema([
            Forms\Components\Textarea::make('admin_notes')
                ->label('Notas internas del equipo')
                ->rows(4)
                ->columnSpanFull(),
        ]);
    }

    public static function infolist(Schema $infolist): Schema
    {
        return $infolist->schema([
            \Filament\Schemas\Components\Section::make('Información del Mensaje')
                ->icon('heroicon-o-envelope-open')
                ->schema([
                    \Filament\Schemas\Components\Grid::make(3)->schema([
                        Infolists\Components\TextEntry::make('form_title')
                            ->label('Formulario'),
                        Infolists\Components\TextEntry::make('form_page_slug')
                            ->label('Página de origen')
                            ->formatStateUsing(fn ($state) => $state ? "/{$state}" : '—'),
                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Recibido el')
                            ->dateTime('d/m/Y H:i'),
                    ]),
                    \Filament\Schemas\Components\Grid::make(2)->schema([
                        Infolists\Components\TextEntry::make('sender_name')
                            ->label('Nombre del remitente')
                            ->default('—'),
                        Infolists\Components\TextEntry::make('sender_email')
                            ->label('Email del remitente')
                            ->default('—')
                            ->copyable(),
                    ]),
                ]),

            \Filament\Schemas\Components\Section::make('Respuestas del Formulario')
                ->icon('heroicon-o-chat-bubble-left-right')
                ->schema([
                    Infolists\Components\RepeatableEntry::make('fields_data')
                        ->label('')
                        ->schema([
                            Infolists\Components\TextEntry::make('label')
                                ->label('Campo')
                                ->weight('bold'),
                            Infolists\Components\TextEntry::make('value')
                                ->label('Respuesta')
                                ->default('—')
                                ->columnSpan(2),
                        ])
                        ->columns(3)
                        ->columnSpanFull(),
                ]),

            \Filament\Schemas\Components\Section::make('Estado de Atención')
                ->icon('heroicon-o-check-badge')
                ->schema([
                    \Filament\Schemas\Components\Grid::make(3)->schema([
                        Infolists\Components\IconEntry::make('is_attended')
                            ->label('¿Atendido?')
                            ->boolean(),
                        Infolists\Components\TextEntry::make('attended_at')
                            ->label('Atendido el')
                            ->formatStateUsing(fn ($state) => $state ? \Carbon\Carbon::parse($state)->format('d/m/Y H:i') : '—'),
                        Infolists\Components\TextEntry::make('attended_by')
                            ->label('Atendido por')
                            ->placeholder('—'),
                    ]),
                    Infolists\Components\TextEntry::make('admin_notes')
                        ->label('Notas internas')
                        ->default('Sin notas.')
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\IconColumn::make('is_attended')
                    ->label('')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-clock')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->width(40),

                Tables\Columns\TextColumn::make('sender_name')
                    ->label('Remitente')
                    ->default('—')
                    ->searchable()
                    ->weight(fn ($record) => ! $record->is_attended ? 'bold' : 'normal'),

                Tables\Columns\TextColumn::make('sender_email')
                    ->label('Email')
                    ->default('—')
                    ->searchable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('form_title')
                    ->label('Formulario')
                    ->badge()
                    ->color('warning'),

                Tables\Columns\TextColumn::make('form_page_slug')
                    ->label('Página')
                    ->formatStateUsing(fn ($state) => $state ? "/{$state}" : '/')
                    ->color('gray'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Recibido')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_attended')
                    ->label('Estado')
                    ->trueLabel('Solo atendidos')
                    ->falseLabel('Solo pendientes')
                    ->placeholder('Todos'),
                Tables\Filters\SelectFilter::make('form_page_slug')
                    ->label('Página de origen')
                    ->options(
                        fn () => ContactSubmission::query()
                            ->distinct()
                            ->pluck('form_page_slug', 'form_page_slug')
                            ->filter()
                            ->toArray()
                    ),
            ])
            ->actions([
                Actions\ViewAction::make()
                    ->label('Ver'),

                Actions\Action::make('mark_attended')
                    ->label('Marcar atendido')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->visible(fn ($record) => ! $record->is_attended)
                    ->requiresConfirmation()
                    ->modalHeading('¿Marcar como atendido?')
                    ->modalDescription('Esto registrará que el mensaje fue revisado.')
                    ->action(function ($record) {
                        $record->markAsAttended(auth()->user()?->name ?? 'admin');
                        Notification::make()
                            ->title('Mensaje marcado como atendido ✅')
                            ->success()
                            ->send();
                    }),

                Actions\Action::make('mark_unattended')
                    ->label('Marcar pendiente')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->color('warning')
                    ->visible(fn ($record) => $record->is_attended)
                    ->action(function ($record) {
                        $record->update(['is_attended' => false, 'attended_at' => null, 'attended_by' => null]);
                        Notification::make()
                            ->title('Mensaje marcado como pendiente')
                            ->warning()
                            ->send();
                    }),

                Actions\EditAction::make()
                    ->label('Notas')
                    ->icon('heroicon-o-pencil-square'),

                Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\BulkAction::make('bulk_attended')
                        ->label('Marcar seleccionados como atendidos')
                        ->icon('heroicon-o-check')
                        ->color('success')
                        ->action(function ($records) {
                            $records->each(fn ($r) => $r->markAsAttended(auth()->user()?->name ?? 'admin'));
                            Notification::make()->title('Mensajes marcados como atendidos')->success()->send();
                        }),
                    Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->striped()
            ->poll('30s'); // auto-refresh cada 30 segundos
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListContactSubmissions::route('/'),
            'view'   => Pages\ViewContactSubmission::route('/{record}'),
            'edit'   => Pages\EditContactSubmission::route('/{record}/edit'),
        ];
    }
}
