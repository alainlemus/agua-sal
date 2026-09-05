<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MenuResource\Pages;
use App\Models\Category;
use App\Models\Menu;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms;
use Filament\Schemas\Components;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Actions;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class MenuResource extends Resource
{
    protected static ?string $model = Menu::class;

    protected static ?string $modelLabel       = 'Menú';
    protected static ?string $recordTitleAttribute = 'name';
    protected static ?string $pluralModelLabel = 'Menús';
    protected static ?string $navigationLabel  = 'Menús';
    protected static string | \BackedEnum | null $navigationIcon   = 'heroicon-o-book-open';
    protected static string | \UnitEnum | null $navigationGroup  = 'Menú & Productos';
    protected static ?int    $navigationSort   = 1;

    public static function form(Schema $form): Schema
    {
        $categoryOptions = Category::orderBy('name')->pluck('name', 'id')->toArray();

        return $form->schema([
            Components\Section::make('Información del Menú')->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nombre del menú')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('ej. Menú Ahumado')
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, Set $set) =>
                        $set('slug', Str::slug($state))
                    ),

                Forms\Components\TextInput::make('subtitle')
                    ->label('Subtítulo')
                    ->placeholder('ej. Descubre nuestros platillos')
                    ->helperText('Aparece debajo del título en la página del menú.')
                    ->maxLength(200)
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('slug')
                    ->label('Slug (identificador URL)')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->alphaDash()
                    ->helperText('Se usa como ancla en la URL, ej. /menu#ahumado'),

                Forms\Components\TextInput::make('schedule')
                    ->label('Horario')
                    ->placeholder('ej. Lunes–Sábado 1pm–9pm')
                    ->maxLength(255),

                Forms\Components\Textarea::make('description')
                    ->label('Descripción')
                    ->rows(2)
                    ->nullable()
                    ->columnSpanFull(),

                Forms\Components\Toggle::make('is_active')
                    ->label('Activo (visible en el sitio)')
                    ->default(true)
                    ->inline(false),

                Forms\Components\TextInput::make('sort_order')
                    ->label('Orden')
                    ->numeric()
                    ->default(0)
                    ->helperText('Menor número = aparece primero'),


            ])->columns(2),

            Components\Section::make('Secciones del Menú')
                ->description('Agrega las categorías de productos que pertenecen a este menú, en el orden que quieras mostrarlas.')
                ->schema([
                    Forms\Components\Repeater::make('sections')
                        ->label('')
                        ->relationship()
                        ->schema([
                            Forms\Components\Select::make('category_id')
                                ->label('Categoría de productos')
                                ->options($categoryOptions)
                                ->required()
                                ->searchable(),

                            Forms\Components\TextInput::make('label')
                                ->label('Etiqueta personalizada (opcional)')
                                ->placeholder('Dejar vacío para usar el nombre de la categoría')
                                ->maxLength(100),

                            Forms\Components\TextInput::make('sort_order')
                                ->label('Orden')
                                ->numeric()
                                ->default(0),
                        ])
                        ->columns(3)
                        ->orderColumn('sort_order')
                        ->addActionLabel('+ Agregar sección')
                        ->reorderable('sort_order')
                        ->collapsible(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->columns([
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Activo')
                    ->boolean(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Menú')
                    ->weight('bold')
                    ->searchable(),

                Tables\Columns\TextColumn::make('schedule')
                    ->label('Horario')
                    ->default('—')
                    ->color('gray'),

                Tables\Columns\TextColumn::make('sections_count')
                    ->label('Secciones')
                    ->counts('sections')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Orden')
                    ->alignCenter(),
            ])
            ->actions([
                Actions\EditAction::make(),

                Actions\ActionGroup::make([
                    Actions\Action::make('download_qr_pdf')
                        ->label('PDF QR')
                        ->icon('heroicon-o-document-arrow-down')
                        ->color('success')
                        ->action(function (Menu $record) {
                            $url       = route('menu') . '?tab=' . $record->slug;
                            $qrPng     = QrCode::format('png')->size(400)->margin(1)->generate($url);
                            $qrDataUri = 'data:image/png;base64,' . base64_encode($qrPng);

                            $pdf = Pdf::loadView('pdf.qr-menu', [
                                'qrDataUri' => $qrDataUri,
                            ])->setPaper('a4', 'portrait');

                            return response()->streamDownload(
                                fn () => print($pdf->output()),
                                'qr-menu-' . $record->slug . '.pdf',
                                ['Content-Type' => 'application/pdf']
                            );
                        }),

                    Actions\Action::make('download_qr_png')
                        ->label('Imagen QR (PNG)')
                        ->icon('heroicon-o-photo')
                        ->color('success')
                        ->action(function (Menu $record) {
                            $url   = route('menu') . '?tab=' . $record->slug;
                            $qrPng = QrCode::format('png')->size(600)->margin(2)->generate($url);

                            return response()->streamDownload(
                                fn () => print($qrPng),
                                'qr-menu-' . $record->slug . '.png',
                                ['Content-Type' => 'image/png']
                            );
                        }),

                    Actions\Action::make('download_qr_all_pdf')
                        ->label('PDF QR (todos los menús)')
                        ->icon('heroicon-o-squares-2x2')
                        ->color('info')
                        ->visible(fn (Menu $record) => Menu::active()->count() > 1)
                        ->action(function () {
                            $url       = route('menu');
                            $qrPng     = QrCode::format('png')->size(400)->margin(1)->generate($url);
                            $qrDataUri = 'data:image/png;base64,' . base64_encode($qrPng);

                            $pdf = Pdf::loadView('pdf.qr-menu', [
                                'qrDataUri' => $qrDataUri,
                            ])->setPaper('a4', 'portrait');

                            return response()->streamDownload(
                                fn () => print($pdf->output()),
                                'qr-menu-completo.pdf',
                                ['Content-Type' => 'application/pdf']
                            );
                        }),

                    Actions\Action::make('download_qr_all_png')
                        ->label('Imagen QR (todos los menús)')
                        ->icon('heroicon-o-photo')
                        ->color('info')
                        ->visible(fn (Menu $record) => Menu::active()->count() > 1)
                        ->action(function () {
                            $url   = route('menu');
                            $qrPng = QrCode::format('png')->size(600)->margin(2)->generate($url);

                            return response()->streamDownload(
                                fn () => print($qrPng),
                                'qr-menu-completo.png',
                                ['Content-Type' => 'image/png']
                            );
                        }),
                ])->icon('heroicon-o-qr-code')->color('success')->label('QR'),

                Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListMenus::route('/'),
            'create' => Pages\CreateMenu::route('/create'),
            'edit'   => Pages\EditMenu::route('/{record}/edit'),
        ];
    }
}
