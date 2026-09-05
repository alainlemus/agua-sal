<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReviewCampaignResource\Pages;
use App\Models\ReviewCampaign;
use App\Models\ReviewToken;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ReviewCampaignResource extends Resource
{
    protected static ?string $model = ReviewCampaign::class;

    protected static ?string $modelLabel       = 'Campaña de Reseñas';
    protected static ?string $pluralModelLabel = 'Campañas de Reseñas';
    protected static ?string $navigationLabel  = 'Campañas QR';
    protected static string | \BackedEnum | null $navigationIcon   = 'heroicon-o-qr-code';
    protected static string | \UnitEnum | null $navigationGroup  = 'Reseñas & QR';
    protected static ?int    $navigationSort   = 1;

    public static function form(Schema $form): Schema
    {
        return $form->schema([
            Forms\Components\Section::make('Campaña')->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nombre interno')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('ej. Campaña Verano 2026'),

                Forms\Components\TextInput::make('slug')
                    ->label('Slug (URL del QR)')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->alphaDash()
                    ->helperText(fn () => 'URL: ' . url('/resena/') . '/{slug}')
                    ->placeholder('ej. verano-2026'),

                Forms\Components\Toggle::make('is_active')
                    ->label('Activa')
                    ->default(true)
                    ->inline(false),

                Forms\Components\TextInput::make('max_uses')
                    ->label('Máximo de usos')
                    ->numeric()
                    ->nullable()
                    ->helperText('Dejar vacío = sin límite'),
            ])->columns(2),

            Forms\Components\Section::make('Regalo')->schema([
                Forms\Components\TextInput::make('gift_title')
                    ->label('Título del regalo')
                    ->required()
                    ->placeholder('ej. ¡Bebida gratis!')
                    ->maxLength(255),

                Forms\Components\TextInput::make('gift_code_prefix')
                    ->label('Prefijo del código')
                    ->required()
                    ->default('DON')
                    ->maxLength(10)
                    ->helperText('ej. DON → códigos como DON-A3K9'),

                Forms\Components\Textarea::make('gift_description')
                    ->label('Descripción del regalo')
                    ->rows(2)
                    ->placeholder('Válido para una bebida de tu elección, de 20 oz o menor.')
                    ->columnSpanFull(),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Activa')
                    ->boolean(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Campaña')
                    ->searchable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('gift_title')
                    ->label('Regalo')
                    ->badge()
                    ->color('warning'),

                Tables\Columns\TextColumn::make('slug')
                    ->label('URL')
                    ->formatStateUsing(fn ($state) => "/resena/{$state}")
                    ->color('gray'),

                Tables\Columns\TextColumn::make('submissions_count')
                    ->label('Reseñas')
                    ->counts('submissions')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('max_uses')
                    ->label('Límite')
                    ->default('∞')
                    ->alignCenter(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),

                Tables\Actions\Action::make('generate_token')
                    ->label('Generar enlace')
                    ->icon('heroicon-o-link')
                    ->color('warning')
                    ->visible(fn (ReviewCampaign $record) => $record->is_active)
                    ->form([
                        Forms\Components\TextInput::make('created_by')
                            ->label('Tu nombre (personal)')
                            ->required()
                            ->placeholder('ej. María'),
                        Forms\Components\Select::make('expires_minutes')
                            ->label('Válido por')
                            ->options([
                                30  => '30 minutos',
                                60  => '1 hora',
                                120 => '2 horas',
                                240 => '4 horas',
                                480 => '8 horas (turno completo)',
                            ])
                            ->default(120)
                            ->required(),
                    ])
                    ->action(function (ReviewCampaign $record, array $data) {
                        $reviewToken = ReviewToken::generate(
                            campaignId:       $record->id,
                            createdBy:        $data['created_by'],
                            expiresInMinutes: (int) $data['expires_minutes'],
                        );

                        $url   = route('review.form', $reviewToken->token);
                        $qrSvg = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(250)->generate($url);

                        Notification::make()
                            ->title('¡Enlace generado! Muéstrale este QR al cliente.')
                            ->body(
                                '<div style="text-align:center;padding:8px 0">'
                                . $qrSvg
                                . '</div>'
                                . '<p style="font-size:11px;color:#aaa;word-break:break-all;margin-top:6px">' . $url . '</p>'
                                . '<p style="font-size:11px;color:#f97316;margin-top:4px">Expira a las ' . $reviewToken->expires_at->format('H:i') . ' — solo un uso</p>'
                            )
                            ->success()
                            ->persistent()
                            ->send();
                    })
                    ->modalSubmitActionLabel('Generar enlace'),

                Tables\Actions\Action::make('show_permanent_qr')
                    ->label('QR Permanente')
                    ->icon('heroicon-o-qr-code')
                    ->color('info')
                    ->visible(fn (ReviewCampaign $record) => $record->is_active)
                    ->action(function (ReviewCampaign $record) {
                        $url   = route('review.public');
                        $qrSvg = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(260)->generate($url);

                        Notification::make()
                            ->title('QR Permanente — Campaña: ' . $record->name)
                            ->body(
                                '<div style="text-align:center;padding:8px 0">'
                                . $qrSvg
                                . '</div>'
                                . '<p style="font-size:11px;color:#aaa;word-break:break-all;margin-top:6px">' . $url . '</p>'
                                . '<p style="font-size:11px;color:#38bdf8;margin-top:4px">Este QR siempre apunta a la campaña activa. No expira.</p>'
                            )
                            ->info()
                            ->persistent()
                            ->send();
                    }),

                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('download_qr_permanent_pdf')
                        ->label('PDF QR Permanente')
                        ->icon('heroicon-o-document-arrow-down')
                        ->color('info')
                        ->visible(fn (ReviewCampaign $record) => $record->is_active)
                        ->action(function (ReviewCampaign $record) {
                            $url       = route('review.public');
                            $qrPng     = QrCode::format('png')->size(300)->margin(1)->generate($url);
                            $qrDataUri = 'data:image/png;base64,' . base64_encode($qrPng);
                            $logoPath  = public_path('storage/images/logo.png');

                            $pdf = Pdf::loadView('pdf.qr-review', [
                                'campaign'   => $record,
                                'reviewUrl'  => $url,
                                'qrDataUri'  => $qrDataUri,
                                'logoPath'   => $logoPath,
                            ])->setPaper('a4', 'portrait');

                            return response()->streamDownload(
                                fn () => print($pdf->output()),
                                "qr-permanente-{$record->slug}.pdf",
                                ['Content-Type' => 'application/pdf']
                            );
                        }),

                    Tables\Actions\Action::make('download_qr_permanent_png')
                        ->label('PNG QR Permanente')
                        ->icon('heroicon-o-photo')
                        ->color('info')
                        ->visible(fn (ReviewCampaign $record) => $record->is_active)
                        ->action(function (ReviewCampaign $record) {
                            $url   = route('review.public');
                            $qrPng = QrCode::format('png')->size(600)->margin(2)->generate($url);

                            return response()->streamDownload(
                                fn () => print($qrPng),
                                "qr-permanente-{$record->slug}.png",
                                ['Content-Type' => 'image/png']
                            );
                        }),

                    Tables\Actions\Action::make('download_qr_review_pdf')
                        ->label('PDF QR por Token')
                        ->icon('heroicon-o-document-arrow-down')
                        ->color('success')
                        ->action(function (ReviewCampaign $record) {
                            $url        = route('review.form', $record->slug);
                            $qrPng      = QrCode::format('png')->size(300)->margin(1)->generate($url);
                            $qrDataUri  = 'data:image/png;base64,' . base64_encode($qrPng);
                            $logoPath   = public_path('storage/images/logo.png');

                            $pdf = Pdf::loadView('pdf.qr-review', [
                                'campaign'   => $record,
                                'reviewUrl'  => $url,
                                'qrDataUri'  => $qrDataUri,
                                'logoPath'   => $logoPath,
                            ])->setPaper('a4', 'portrait');

                            return response()->streamDownload(
                                fn () => print($pdf->output()),
                                "qr-resenas-{$record->slug}.pdf",
                                ['Content-Type' => 'application/pdf']
                            );
                        }),

                    Tables\Actions\Action::make('download_qr_review_png')
                        ->label('PNG QR por Token')
                        ->icon('heroicon-o-photo')
                        ->color('success')
                        ->action(function (ReviewCampaign $record) {
                            $url   = route('review.form', $record->slug);
                            $qrPng = QrCode::format('png')->size(600)->margin(2)->generate($url);

                            return response()->streamDownload(
                                fn () => print($qrPng),
                                "qr-resenas-{$record->slug}.png",
                                ['Content-Type' => 'image/png']
                            );
                        }),

                ])->icon('heroicon-o-qr-code')->color('gray')->label('QR'),

                Tables\Actions\Action::make('view_submissions')
                    ->label('Ver reseñas')
                    ->icon('heroicon-o-star')
                    ->color('warning')
                    ->url(fn (ReviewCampaign $record) => ReviewSubmissionResource::getUrl('index', [
                        'tableFilters[review_campaign_id][value]' => $record->id,
                    ])),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index'  => Pages\ListReviewCampaigns::route('/'),
            'create' => Pages\CreateReviewCampaign::route('/create'),
            'edit'   => Pages\EditReviewCampaign::route('/{record}/edit'),
        ];
    }
}
