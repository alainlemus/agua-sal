<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SiteInfoResource\Pages;
use App\Filament\Resources\SiteInfoResource\RelationManagers;
use App\Models\SiteInfo;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SiteInfoResource extends Resource
{
    protected static ?string $model = SiteInfo::class;

    protected static ?string $modelLabel = 'Configuración General';
    protected static ?string $pluralModelLabel = 'Configuración Global';
    protected static ?string $navigationLabel = 'Configuración General';
    protected static ?string $navigationGroup = 'Sitio Web';
    protected static ?string $navigationIcon = 'heroicon-o-cog-8-tooth';
    protected static ?int    $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información General')
                    ->description('Nombre, identidad y tipo de cocina del restaurante.')
                    ->schema([
                        Forms\Components\TextInput::make('site_name')
                            ->label('Nombre del Sitio / Restaurante')
                            ->placeholder('Ej: Mi Restaurante')
                            ->helperText('Este nombre aparece en el título del navegador, el admin y el sitio web.')
                            ->maxLength(100),

                        Forms\Components\TextInput::make('tagline')
                            ->label('Tagline / Eslogan')
                            ->placeholder('Ej: El mejor sabor de la ciudad')
                            ->helperText('Frase corta que aparece debajo del logo en el footer.')
                            ->maxLength(200),

                        Forms\Components\TextInput::make('serves_cuisine')
                            ->label('Tipo de cocina (servesCuisine)')
                            ->placeholder('Ej: BBQ, Mexican, Steakhouse')
                            ->helperText('Usado en datos estructurados (Schema.org). Separar con comas.'),
                    ])
                    ->collapsible()
                    ->collapsed(),

                Forms\Components\Section::make('Logo e Identidad')
                    ->description('Logo del sitio y favicon para el navegador.')
                    ->schema([
                        Forms\Components\FileUpload::make('site_logo')
                            ->label('Logo del Sitio')
                            ->image()
                            ->directory('images'),

                        Forms\Components\FileUpload::make('favicon')
                            ->label('Favicon')
                            ->image()
                            ->directory('favicons')
                            ->helperText('PNG cuadrado de 32×32 o 64×64 px. Aparece en la pestaña del navegador.'),

                        Forms\Components\Select::make('theme')
                            ->label('Tema de Colores')
                            ->options(function () {
                                $themes = config('themes', []);
                                $options = ['default' => 'Océano Profundo (Default)'];
                                foreach ($themes as $key => $theme) {
                                    if ($key !== 'default') {
                                        $options[$key] = $theme['name'] . ' — ' . $theme['description'];
                                    }
                                }
                                return $options;
                            })
                            ->default('default')
                            ->helperText('Selecciona la combinación de colores para el sitio web.'),
                    ])
                    ->collapsible()
                    ->collapsed(),

                Forms\Components\Section::make('Contacto y Horarios')
                    ->description('Dirección, teléfono y horarios del restaurante.')
                    ->schema([
                        Forms\Components\TextInput::make('address')
                            ->label('Dirección del Local'),

                        Forms\Components\TextInput::make('phone')
                            ->label('Teléfono')
                            ->tel(),

                        Forms\Components\TextInput::make('whatsapp')
                            ->label('WhatsApp'),

                        Forms\Components\TextInput::make('email')
                            ->label('Correo Electrónico')
                            ->email(),

                        Forms\Components\Repeater::make('schedules')
                            ->label('Horarios')
                            ->schema([
                                Forms\Components\TextInput::make('days')->label('Días (Ej: Lunes a Jueves)')->required(),
                                Forms\Components\TextInput::make('hours')->label('Horario (Ej: 12:00 PM - 10:00 PM)')->required(),
                            ])
                            ->addActionLabel('+ Agregar horario')
                            ->reorderable()
                            ->collapsible(),
                    ])
                    ->collapsible()
                    ->collapsed(),

                Forms\Components\Section::make('Música de Fondo')
                    ->description('Configura una canción de fondo que se reproducirá en el sitio web.')
                    ->icon('heroicon-o-musical-note')
                    ->schema([
                        Forms\Components\FileUpload::make('background_music')
                            ->label('Archivo de Audio')
                            ->acceptedFileTypes(['audio/mpeg', 'audio/mp3', 'audio/wav', 'audio/ogg', 'audio/*'])
                            ->directory('audio')
                            ->helperText('Formatos aceptados: MP3, WAV, OGG. Máx 10MB.'),

                        Forms\Components\Toggle::make('auto_play_music')
                            ->label('Reproducir automáticamente al cargar el sitio')
                            ->helperText('Si está activado, la música comenzará a reproducirse automáticamente cuando el usuario visite el sitio. El navegador podría bloquear la reproducción automática hasta que el usuario interactúe.')
                            ->default(false),
                    ])
                    ->collapsible(),

                Forms\Components\Section::make('Efectos Visuales')
                    ->description('Configura los efectos animados del sitio (burbujas, sal cayendo, olas).')
                    ->icon('heroicon-o-sparkles')
                    ->schema([
                        Forms\Components\Toggle::make('enable_bubbles')
                            ->label('Mostrar burbujas flotando')
                            ->default(true)
                            ->helperText('Burbujas que suben desde abajo de la pantalla.'),

                        Forms\Components\Toggle::make('enable_salt_effect')
                            ->label('Mostrar efecto de sal cayendo')
                            ->default(true)
                            ->helperText('Partículas de sal que caen desde arriba.'),

                        Forms\Components\Toggle::make('enable_waves')
                            ->label('Mostrar olas animadas')
                            ->default(true)
                            ->helperText('Olas animadas en la parte inferior del sitio.'),

                        Forms\Components\Select::make('bubbles_density')
                            ->label('Densidad de burbujas')
                            ->options([
                                'low'    => 'Pocas',
                                'medium' => 'Medias',
                                'high'   => 'Muchas',
                            ])
                            ->default('medium')
                            ->helperText('Cantidad de burbujas en pantalla.'),

                        Forms\Components\Select::make('salt_density')
                            ->label('Intensidad del efecto de sal')
                            ->options([
                                'soft'   => 'Suave',
                                'normal' => 'Normal',
                                'intense' => 'Intenso',
                            ])
                            ->default('normal')
                            ->helperText('Intensidad de las partículas de sal.'),
                    ])
                    ->collapsible(),

                Forms\Components\Section::make('Acerca de Nosotros')
                    ->description('Texto e imagen para la sección "Acerca de" y valores fallback del hero.')
                    ->schema([
                        Forms\Components\Textarea::make('about_text')
                            ->label('Texto Acerca de Nosotros (Fallback)'),

                        Forms\Components\TextInput::make('hero_heading')
                            ->label('Título Principal (Fallback)'),

                        Forms\Components\TextInput::make('hero_subheading')
                            ->label('Subtítulo (Fallback)'),

                        Forms\Components\FileUpload::make('about_image')
                            ->label('Imagen Nosotros (Fallback)')
                            ->image(),

                        Forms\Components\Textarea::make('map_embed_url')
                            ->label('URL de Google Maps (Iframe)')
                            ->helperText('Copia el código de inserción de Google Maps aquí.'),
                    ])
                    ->collapsible()
                    ->collapsed(),

                Forms\Components\Section::make('Redes Sociales')
                    ->description('Agrega los perfiles de redes sociales que aparecerán en el footer del sitio.')
                    ->schema([
                        Forms\Components\Repeater::make('social_links')
                            ->label('')
                            ->schema([
                                Forms\Components\Select::make('platform')
                                    ->label('Red social')
                                    ->required()
                                    ->options([
                                        'facebook'  => 'Facebook',
                                        'instagram' => 'Instagram',
                                        'tiktok'    => 'TikTok',
                                        'x'         => 'X (Twitter)',
                                        'youtube'   => 'YouTube',
                                        'whatsapp'  => 'WhatsApp Business',
                                        'other'     => 'Otro',
                                    ]),

                                Forms\Components\TextInput::make('label')
                                    ->label('Etiqueta (opcional)')
                                    ->placeholder('ej. @mirestaurante')
                                    ->maxLength(100),

                                Forms\Components\TextInput::make('url')
                                    ->label('URL del perfil')
                                    ->required()
                                    ->url()
                                    ->placeholder('https://www.instagram.com/mirestaurante')
                                    ->columnSpanFull(),
                            ])
                            ->columns(2)
                            ->addActionLabel('+ Agregar red social')
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string =>
                                match ($state['platform'] ?? null) {
                                    'facebook'  => 'Facebook' . ($state['label'] ? ' — ' . $state['label'] : ''),
                                    'instagram' => 'Instagram' . ($state['label'] ? ' — ' . $state['label'] : ''),
                                    'tiktok'    => 'TikTok' . ($state['label'] ? ' — ' . $state['label'] : ''),
                                    'x'         => 'X (Twitter)' . ($state['label'] ? ' — ' . $state['label'] : ''),
                                    'youtube'   => 'YouTube' . ($state['label'] ? ' — ' . $state['label'] : ''),
                                    'whatsapp'  => 'WhatsApp Business' . ($state['label'] ? ' — ' . $state['label'] : ''),
                                    'other'     => $state['label'] ?: 'Otro enlace',
                                    default     => 'Red social',
                                }
                            )
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->collapsed(),

                Forms\Components\Section::make('Aviso de Privacidad')
                    ->description('Contenido que se mostrará en la página /aviso-de-privacidad del sitio.')
                    ->icon('heroicon-o-shield-check')
                    ->schema([
                        Forms\Components\TextInput::make('privacy_policy_title')
                            ->label('Título de la página')
                            ->default('Aviso de Privacidad')
                            ->maxLength(150)
                            ->columnSpanFull(),

                        Forms\Components\RichEditor::make('privacy_policy_content')
                            ->label('Contenido')
                            ->toolbarButtons([
                                'bold', 'italic', 'underline', 'strike',
                                'h2', 'h3',
                                'bulletList', 'orderedList',
                                'blockquote',
                                'link',
                                'undo', 'redo',
                            ])
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->collapsed(),

                Forms\Components\Section::make('SEO & Redes Sociales')
                    ->description('Estos datos controlan cómo aparece el sitio en Google y cuando se comparte en redes sociales (Open Graph / Twitter Card).')
                    ->icon('heroicon-o-magnifying-glass')
                    ->schema([
                        Forms\Components\TextInput::make('seo_title')
                            ->label('Título SEO')
                            ->placeholder('Mi Restaurante — El auténtico sabor')
                            ->maxLength(60)
                            ->helperText('Recomendado: máximo 60 caracteres.')
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('seo_description')
                            ->label('Descripción SEO')
                            ->placeholder('Restaurante y taquería en tu ciudad. Cortes premium y más.')
                            ->maxLength(160)
                            ->helperText('Recomendado: entre 120 y 160 caracteres.')
                            ->rows(3)
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('seo_keywords')
                            ->label('Palabras clave (keywords)')
                            ->placeholder('restaurante, BBQ, cortes, comida')
                            ->helperText('Separadas por comas. Tienen poco impacto en Google pero ayudan en otros motores.')
                            ->columnSpanFull(),

                        Forms\Components\FileUpload::make('og_image')
                            ->label('Imagen para compartir (OG Image)')
                            ->image()
                            ->directory('seo')
                            ->helperText('Recomendado: 1200×630 px. Aparece al compartir el enlace en WhatsApp, Facebook, Twitter, etc.')
                            ->columnSpanFull(),

                        Forms\Components\Select::make('og_type')
                            ->label('Tipo OG')
                            ->options([
                                'website'     => 'Website',
                                'restaurant'  => 'Restaurant',
                                'business'    => 'Business',
                            ])
                            ->default('website'),

                        Forms\Components\Select::make('twitter_card')
                            ->label('Twitter Card')
                            ->options([
                                'summary_large_image' => 'Imagen grande (recomendado)',
                                'summary'             => 'Resumen pequeño',
                            ])
                            ->default('summary_large_image'),

                        Forms\Components\TextInput::make('twitter_site')
                            ->label('Usuario de Twitter/X')
                            ->placeholder('@mirestaurante')
                            ->helperText('Con @. Opcional.'),
                    ])
                    ->columns(2)
                    ->collapsible()
                    ->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('address')
                    ->label('Dirección')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Teléfono')
                    ->searchable(),
                Tables\Columns\TextColumn::make('whatsapp')
                    ->label('WhatsApp')
                    ->searchable(),
                Tables\Columns\TextColumn::make('hero_heading')
                    ->label('Título Hero')
                    ->searchable(),
                Tables\Columns\TextColumn::make('hero_subheading')
                    ->label('Subtítulo')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('about_image'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getNavigationUrl(): string
    {
        $record = \App\Models\SiteInfo::first();
        return static::getUrl('edit', ['record' => $record]);
    }

    public static function getPages(): array
    {
        return [
            'edit' => Pages\EditSiteInfo::route('/{record}/edit'),
        ];
    }
}
