<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PageResource\Pages;
use App\Filament\Resources\PageResource\RelationManagers;
use App\Models\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Guava\FilamentIconPicker\Forms\IconPicker;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\URL;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static ?string $modelLabel = 'Página Dinámica (CMS)';
    protected static ?string $pluralModelLabel = 'Constructor de Páginas';
    protected static ?string $navigationLabel = 'Páginas';
    protected static ?string $navigationGroup = 'Sitio Web';
    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';
    protected static ?int    $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // ── Columna izquierda: todos los campos ──────────────────
                Forms\Components\Group::make()->schema([

                        Forms\Components\Group::make()->schema([
                            Forms\Components\TextInput::make('title')
                                ->label('Título de la Página')
                                ->required()
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn (string $operation, $state, Forms\Set $set) => $operation === 'create' ? $set('slug', \Illuminate\Support\Str::slug($state)) : null),

                            Forms\Components\TextInput::make('slug')
                                ->label('Slug / Enlace URL')
                                ->required()
                                ->unique(ignoreRecord: true),

                            Forms\Components\Toggle::make('is_published')
                                ->label('¿Publicar inmediatamente?')
                                ->default(true),
                        ])->columns(3),

                        Forms\Components\Section::make('Configuración de Navegación')
                            ->description('Controla si esta página aparece en el menú principal del sitio.')
                            ->icon('heroicon-o-bars-3')
                            ->schema([
                                Forms\Components\Toggle::make('show_in_nav')
                                    ->label('Mostrar en el Menú de Navegación')
                                    ->helperText('Si está activo y la página está publicada, aparecerá en el nav automáticamente.')
                                    ->default(false),

                                Forms\Components\TextInput::make('nav_label')
                                    ->label('Etiqueta en el menú (opcional)')
                                    ->helperText('Si se deja vacío, se usará el Título de la Página.')
                                    ->placeholder('Ej. Tostadas y Mariscos')
                                    ->maxLength(60),

                                IconPicker::make('nav_icon')
                                    ->label('Ícono (opcional)')
                                    ->helperText('Aparecerá junto al link en el menú de navegación.')
                                    ->sets(['heroicons'])
                                    ->columns(5),

                                Forms\Components\TextInput::make('nav_order')
                                    ->label('Orden en el menú')
                                    ->helperText('Número más bajo = aparece primero. Ej: 1, 2, 3...')
                                    ->numeric()
                                    ->default(0)
                                    ->minValue(0),
                            ])->columns(2)->collapsible(),

                        Forms\Components\Section::make('Modal al cargar la página')
                            ->description('Muestra un modal emergente cuando el visitante abre esta página.')
                            ->icon('heroicon-o-chat-bubble-bottom-center-text')
                            ->collapsible()
                            ->collapsed()
                            ->schema([
                                Forms\Components\Toggle::make('modal_enabled')
                                    ->label('Activar modal')
                                    ->helperText('Si está activo, el modal aparecerá al entrar a esta página.')
                                    ->default(false)
                                    ->live()
                                    ->columnSpanFull(),

                                Forms\Components\TextInput::make('modal_title')
                                    ->label('Título del modal')
                                    ->placeholder('Ej. ¡Oferta especial!')
                                    ->maxLength(120)
                                    ->visible(fn ($get) => $get('modal_enabled')),

                                Forms\Components\Select::make('modal_delay')
                                    ->label('Mostrar después de...')
                                    ->options([
                                        '0'  => 'Inmediatamente',
                                        '2'  => '2 segundos',
                                        '5'  => '5 segundos',
                                        '10' => '10 segundos',
                                    ])
                                    ->default('0')
                                    ->visible(fn ($get) => $get('modal_enabled')),

                                Forms\Components\Textarea::make('modal_body')
                                    ->label('Contenido / Mensaje')
                                    ->rows(4)
                                    ->placeholder('Escribe el mensaje que verá el visitante...')
                                    ->columnSpanFull()
                                    ->visible(fn ($get) => $get('modal_enabled')),

                                Forms\Components\TextInput::make('modal_button_label')
                                    ->label('Texto del botón (opcional)')
                                    ->placeholder('Ej. Ver promoción')
                                    ->maxLength(60)
                                    ->visible(fn ($get) => $get('modal_enabled')),

                                Forms\Components\TextInput::make('modal_button_url')
                                    ->label('URL del botón (opcional)')
                                    ->placeholder('https://... o /ruta')
                                    ->url()
                                    ->maxLength(255)
                                    ->visible(fn ($get) => $get('modal_enabled')),

                                Forms\Components\Toggle::make('modal_show_carousel')
                                    ->label('Mostrar carrusel de imágenes')
                                    ->helperText('Si está activo, el modal mostrará un carrusel de imágenes/promociones.')
                                    ->default(false)
                                    ->live()
                                    ->visible(fn ($get) => $get('modal_enabled'))
                                    ->columnSpanFull(),

                                Forms\Components\Repeater::make('modal_carousel_items')
                                    ->label('Imágenes del Carrusel')
                                    ->helperText('Usa imágenes de al menos 1200x675px (relación 16:9) para mejor calidad. Formatos: JPG, PNG.')
                                    ->visible(fn ($get) => $get('modal_enabled') && $get('modal_show_carousel'))
                                    ->schema([
                                        Forms\Components\FileUpload::make('image')
                                            ->label('Imagen')
                                            ->image()
                                            ->directory('modal-carousel')
                                            ->required(),
                                        Forms\Components\TextInput::make('caption')
                                            ->label('Pie de foto (opcional)')
                                            ->maxLength(100),
                                        Forms\Components\TextInput::make('link')
                                            ->label('Link (opcional)')
                                            ->url()
                                            ->maxLength(255),
                                    ])
                                    ->collapsible()
                                    ->addActionLabel('Agregar imagen')
                                    ->columnSpanFull(),

                                Forms\Components\Toggle::make('modal_show_video')
                                    ->label('Mostrar video')
                                    ->helperText('Si está activo, el modal mostrará un video de YouTube o Vimeo.')
                                    ->default(false)
                                    ->live()
                                    ->visible(fn ($get) => $get('modal_enabled'))
                                    ->columnSpanFull(),

                                Forms\Components\TextInput::make('modal_video_url')
                                    ->label('URL del video (YouTube o Vimeo)')
                                    ->placeholder('https://www.youtube.com/watch?v=...')
                                    ->url()
                                    ->maxLength(500)
                                    ->visible(fn ($get) => $get('modal_enabled') && $get('modal_show_video')),

                                Forms\Components\Toggle::make('modal_video_autoplay')
                                    ->label('Autoplay del video')
                                    ->default(false)
                                    ->visible(fn ($get) => $get('modal_enabled') && $get('modal_show_video')),

                                Forms\Components\Toggle::make('modal_intrusive')
                                    ->label('Modal intrusivo')
                                    ->helperText('Si está activo, el modal se mostrará cada vez que el usuario visite la página. Si está desactivado, solo se muestra una vez por sesión.')
                                    ->default(false)
                                    ->visible(fn ($get) => $get('modal_enabled')),
                            ])->columns(2),

                        Forms\Components\Builder::make('builder_content')
                            ->label('Contenido de la Página')
                            ->live()
                            ->collapsible()
                            ->blockPickerColumns(2)
                            ->blockPickerWidth('3xl')
                            ->blocks([

                                // ══════════════════════════════════════════════
                                // 🏠 ESTRUCTURA DE PÁGINA
                                // ══════════════════════════════════════════════

                                Forms\Components\Builder\Block::make('hero')
                                    ->label('Hero / Portada Principal')
                                    ->icon('heroicon-o-home')
                                    ->schema([
                                        Forms\Components\TextInput::make('hero_heading')->label('Título Fuerte'),
                                        Forms\Components\TextInput::make('hero_subheading')->label('Subtítulo'),
                                        Forms\Components\FileUpload::make('hero_image')->label('Imagen o Placeholder del Hero')->image(),
                                        Forms\Components\FileUpload::make('hero_video')
                                            ->label('Video de Fondo (Reemplaza a imagen por defecto)')
                                            ->acceptedFileTypes(['video/mp4', 'video/webm', 'video/ogg'])
                                            ->maxSize(20 * 1024)
                                            ->helperText('Máximo 20MB. Comprime el video antes de subirlo (ej. con HandBrake) para que la página cargue rápido — no se optimiza automáticamente.'),
                                        Forms\Components\FileUpload::make('hero_side_image')->label('Imagen Lateral Flotante')->image(),
                                        Forms\Components\TextInput::make('hero_badge_text_1')->label('Texto Superior Pestaña')->default('CALIDAD Y SABOR'),
                                        Forms\Components\TextInput::make('hero_badge_text_2')->label('Texto Resaltado Pestaña')->default('EN CADA CORTE'),
                                    ]),

                                Forms\Components\Builder\Block::make('about_section')
                                    ->label('Nosotros / Historia')
                                    ->icon('heroicon-o-information-circle')
                                    ->schema([
                                        Forms\Components\TextInput::make('heading')->label('Título')->default('Nuestra Historia'),
                                        Forms\Components\Textarea::make('description')->label('Texto'),
                                        Forms\Components\FileUpload::make('image')->label('Imagen Lateral')->image(),
                                        Forms\Components\Repeater::make('features')
                                            ->label('Características (cajas con ícono)')
                                            ->schema([
                                                Forms\Components\TextInput::make('icon')->label('Ícono Heroicon (ej: heroicon-o-fire)')->placeholder('heroicon-o-sparkles'),
                                                Forms\Components\TextInput::make('title')->label('Título de la caja')->placeholder('Fresco y Natural'),
                                            ])
                                            ->collapsible()
                                            ->defaultItems(3)
                                            ->addActionLabel('+ Agregar característica'),
                                    ]),

                                Forms\Components\Builder\Block::make('cta_banner')
                                    ->label('Banner CTA (Llamada a la acción)')
                                    ->icon('heroicon-o-megaphone')
                                    ->schema([
                                        Forms\Components\TextInput::make('heading')->label('Título')->required(),
                                        Forms\Components\Textarea::make('text')->label('Texto de apoyo')->rows(2),
                                        Forms\Components\TextInput::make('button_label')->label('Texto del botón')->default('Ver más'),
                                        Forms\Components\TextInput::make('button_url')->label('URL del botón')->url()->nullable(),
                                        Forms\Components\Toggle::make('button_new_tab')->label('Abrir en nueva pestaña')->default(false),
                                        Forms\Components\ColorPicker::make('bg_color')->label('Color de fondo')->default('#E52B2B'),
                                        Forms\Components\ColorPicker::make('text_color')->label('Color del texto')->default('#ffffff'),
                                    ]),

                                Forms\Components\Builder\Block::make('rich_text')
                                    ->label('Rich Text / Contenido Editorial')
                                    ->icon('heroicon-o-document-text')
                                    ->schema([
                                        Forms\Components\TextInput::make('heading')->label('Título (opcional)')->nullable(),
                                        Forms\Components\RichEditor::make('content')->label('Contenido')->required()->toolbarButtons(['bold', 'italic', 'underline', 'strike', 'h2', 'h3', 'bulletList', 'orderedList', 'link', 'blockquote', 'redo', 'undo'])->columnSpanFull(),
                                        Forms\Components\Select::make('max_width')->label('Ancho máximo del contenido')->options(['max-w-2xl' => 'Estrecho (lectura)', 'max-w-4xl' => 'Medio', 'max-w-6xl' => 'Ancho', 'max-w-full' => 'Completo'])->default('max-w-4xl'),
                                    ]),

                                // ══════════════════════════════════════════════
                                // 🍽️ PRODUCTOS Y SERVICIOS
                                // ══════════════════════════════════════════════

                                Forms\Components\Builder\Block::make('featured_products')
                                    ->label('Platillos Destacados')
                                    ->icon('heroicon-o-star')
                                    ->schema([
                                        Forms\Components\TextInput::make('heading')
                                            ->label('Título de la sección')
                                            ->default('Platillos Destacados'),
                                        Forms\Components\TextInput::make('subtitle')
                                            ->label('Subtítulo')
                                            ->placeholder('Ej: Descubre lo mejor de nuestra cocina')
                                            ->default('Descubre lo mejor de nuestra cocina'),
                                        Forms\Components\Select::make('product_ids')
                                            ->label('Platillos a mostrar')
                                            ->multiple()
                                            ->options(fn () => \App\Models\Product::where('is_active', true)->orderBy('name')->pluck('name', 'id')->toArray())
                                            ->helperText('Selecciona los platillos que quieres mostrar. Si no seleccionas ninguno, se mostrarán los marcados como "Destacado" en el catálogo.')
                                            ->columnSpanFull(),
                                    ]),

                                Forms\Components\Builder\Block::make('bbq_section')
                                    ->label('Categoría Principal (Grid de Productos)')
                                    ->icon('heroicon-o-squares-2x2')
                                    ->schema([
                                        Forms\Components\TextInput::make('heading')->label('Título')->default('Nuestra Especialidad'),
                                        Forms\Components\TextInput::make('subheading')->label('Subtítulo'),
                                        Forms\Components\Textarea::make('description')->label('Descripción')->rows(3),
                                        Forms\Components\FileUpload::make('background_image')->label('Imagen de Fondo')->image(),
                                        Forms\Components\Repeater::make('items')
                                            ->label('Productos')
                                            ->schema([
                                                Forms\Components\FileUpload::make('image')->label('Imagen')->image()->required(),
                                                Forms\Components\TextInput::make('name')->label('Nombre')->required(),
                                                Forms\Components\TextInput::make('description')->label('Descripción Corta'),
                                                Forms\Components\TextInput::make('badge')->label('Etiqueta (ej. Estrella, Nuevo)'),
                                            ])->collapsible()->grid(2),
                                    ]),

                                Forms\Components\Builder\Block::make('taqueria_section')
                                    ->label('Categoría Secundaria (Grid de Productos)')
                                    ->icon('heroicon-o-rectangle-stack')
                                    ->schema([
                                        Forms\Components\TextInput::make('heading')->label('Título')->default('Segunda Categoría'),
                                        Forms\Components\TextInput::make('subheading')->label('Subtítulo'),
                                        Forms\Components\Textarea::make('description')->label('Descripción')->rows(3),
                                        Forms\Components\TextInput::make('schedule')->label('Horario / Badge (opcional)'),
                                        Forms\Components\FileUpload::make('background_image')->label('Imagen de Fondo')->image(),
                                        Forms\Components\Repeater::make('tacos')
                                            ->label('Productos')
                                            ->schema([
                                                Forms\Components\FileUpload::make('image')->label('Imagen')->image()->required(),
                                                Forms\Components\TextInput::make('name')->label('Nombre')->required(),
                                                Forms\Components\TextInput::make('description')->label('Descripción Corta'),
                                                Forms\Components\TextInput::make('price')->label('Precio (opcional)'),
                                            ])->collapsible()->grid(2),
                                    ]),

                                Forms\Components\Builder\Block::make('ahumado_section')
                                    ->label('Sección Proceso / Técnica (Video + Mosaico)')
                                    ->icon('heroicon-o-fire')
                                    ->schema([
                                        Forms\Components\TextInput::make('heading')->label('Título')->default('Nuestro Proceso'),
                                        Forms\Components\Textarea::make('description')->label('Descripción principal'),
                                        Forms\Components\Textarea::make('video_url')->label('Código de Embebido (Iframe) de YouTube')->rows(4),
                                        Forms\Components\Repeater::make('mosaic_items')
                                            ->label('Imágenes del Mosaico')
                                            ->schema([
                                                Forms\Components\FileUpload::make('image')->label('Imagen')->image()->required(),
                                            ])->grid(2),
                                    ]),

                                Forms\Components\Builder\Block::make('pricing')
                                    ->label('Precios / Planes')
                                    ->icon('heroicon-o-credit-card')
                                    ->schema([
                                        Forms\Components\TextInput::make('heading')->label('Título de la sección')->default('Nuestros Precios'),
                                        Forms\Components\Textarea::make('intro')->label('Introducción (opcional)')->rows(2),
                                        Forms\Components\Repeater::make('plans')->label('Planes')->schema([
                                            Forms\Components\TextInput::make('name')->label('Nombre del plan')->required(),
                                            Forms\Components\TextInput::make('price')->label('Precio (ej: $99/mes)')->required(),
                                            Forms\Components\Textarea::make('description')->label('Descripción corta')->rows(2),
                                            Forms\Components\Textarea::make('features')->label('Características (una por línea)')->rows(5),
                                            Forms\Components\TextInput::make('button_label')->label('Texto del botón')->default('Elegir plan'),
                                            Forms\Components\TextInput::make('button_url')->label('URL del botón')->nullable(),
                                            Forms\Components\Toggle::make('highlighted')->label('¿Destacado?')->default(false),
                                            Forms\Components\ColorPicker::make('accent_color')->label('Color de acento')->default('#E52B2B'),
                                        ])->collapsible()->addActionLabel('+ Agregar plan')->columnSpanFull(),
                                    ]),

                                // ══════════════════════════════════════════════
                                // 💬 SOCIAL Y CONFIANZA
                                // ══════════════════════════════════════════════

                                Forms\Components\Builder\Block::make('reviews_section')
                                    ->label('Reseñas de Clientes (desde BD, automático)')
                                    ->icon('heroicon-o-star')
                                    ->schema([
                                        Forms\Components\TextInput::make('heading')->label('Título')->default('Lo Que Dicen Nuestros Clientes'),
                                    ]),

                                Forms\Components\Builder\Block::make('testimonials')
                                    ->label('Testimonios Manuales')
                                    ->icon('heroicon-o-chat-bubble-left-right')
                                    ->schema([
                                        Forms\Components\TextInput::make('heading')->label('Título de la sección')->default('Lo que dicen nuestros clientes'),
                                        Forms\Components\Repeater::make('items')->label('Testimonios')->schema([
                                            Forms\Components\Textarea::make('quote')->label('Cita / Testimonio')->required()->rows(3),
                                            Forms\Components\TextInput::make('author')->label('Nombre del autor')->required(),
                                            Forms\Components\TextInput::make('role')->label('Cargo / Empresa (opcional)')->nullable(),
                                            Forms\Components\FileUpload::make('photo')->label('Foto (opcional)')->image()->nullable(),
                                            Forms\Components\Select::make('stars')->label('Estrellas')->options([5 => '⭐⭐⭐⭐⭐', 4 => '⭐⭐⭐⭐', 3 => '⭐⭐⭐'])->default(5),
                                        ])->collapsible()->addActionLabel('+ Agregar testimonio')->columnSpanFull(),
                                    ]),

                                Forms\Components\Builder\Block::make('social_feed')
                                    ->label('Muro de Redes Sociales')
                                    ->icon('heroicon-o-share')
                                    ->schema([
                                        Forms\Components\TextInput::make('heading')->label('Título de la sección')->default('Síguenos en Redes'),
                                        Forms\Components\Textarea::make('description')->label('Descripción (opcional)')->rows(2)->nullable()->columnSpanFull(),
                                        Forms\Components\Repeater::make('posts')
                                            ->label('Posts / Videos')
                                            ->schema([
                                                Forms\Components\Select::make('platform')->label('Plataforma')->required()->options(['tiktok' => 'TikTok', 'instagram' => 'Instagram', 'youtube' => 'YouTube', 'facebook' => 'Facebook'])->live(),
                                                Forms\Components\TextInput::make('url')->label('URL del post / video')->required()->url()->placeholder('https://www.tiktok.com/@usuario/video/123...')->columnSpanFull(),
                                                Forms\Components\TextInput::make('caption')->label('Pie de foto / descripción (opcional)')->nullable()->columnSpanFull(),
                                            ])
                                            ->columns(2)
                                            ->addActionLabel('+ Agregar post')
                                            ->reorderable()
                                            ->collapsible()
                                            ->itemLabel(function (array $state): ?string {
                                                $icons = ['tiktok' => '🎵', 'instagram' => '📸', 'youtube' => '▶️', 'facebook' => '📘'];
                                                $icon  = $icons[$state['platform'] ?? ''] ?? '🔗';
                                                $url   = $state['url'] ?? '';
                                                $short = $url ? (' — ' . parse_url($url, PHP_URL_HOST)) : '';
                                                return $icon . ' ' . ucfirst($state['platform'] ?? 'Post') . $short;
                                            })
                                            ->columnSpanFull(),
                                    ]),

                                Forms\Components\Builder\Block::make('features')
                                    ->label('Características / ¿Por qué elegirnos?')
                                    ->icon('heroicon-o-sparkles')
                                    ->schema([
                                        Forms\Components\TextInput::make('heading')->label('Título de la sección')->default('¿Por qué elegirnos?'),
                                        Forms\Components\Textarea::make('intro')->label('Introducción (opcional)')->rows(2),
                                        Forms\Components\Select::make('columns')->label('Columnas en desktop')->options([2 => '2 columnas', 3 => '3 columnas', 4 => '4 columnas'])->default(3),
                                        Forms\Components\Repeater::make('items')->label('Características')->schema([
                                            Forms\Components\TextInput::make('icon')->label('Ícono Heroicon (ej: heroicon-o-fire)')->nullable(),
                                            Forms\Components\TextInput::make('title')->label('Título')->required(),
                                            Forms\Components\Textarea::make('description')->label('Descripción')->rows(2)->required(),
                                            Forms\Components\ColorPicker::make('icon_color')->label('Color del ícono')->default('#E52B2B'),
                                        ])->collapsible()->addActionLabel('+ Agregar característica')->columnSpanFull(),
                                    ]),

                                Forms\Components\Builder\Block::make('stats')
                                    ->label('Números / Estadísticas Destacadas')
                                    ->icon('heroicon-o-chart-bar')
                                    ->schema([
                                        Forms\Components\TextInput::make('heading')->label('Título de la sección (opcional)')->nullable(),
                                        Forms\Components\ColorPicker::make('bg_color')->label('Color de fondo')->default('#1c1c1c'),
                                        Forms\Components\Repeater::make('items')->label('Estadísticas')->schema([
                                            Forms\Components\TextInput::make('value')->label('Valor (ej: +500, 10 años, 98%)')->required(),
                                            Forms\Components\TextInput::make('label')->label('Etiqueta')->required(),
                                            Forms\Components\TextInput::make('icon')->label('Ícono Heroicon (opcional, ej: heroicon-o-users)')->nullable(),
                                            Forms\Components\ColorPicker::make('value_color')->label('Color del valor')->default('#E52B2B'),
                                        ])->collapsible()->addActionLabel('+ Agregar stat')->columnSpanFull(),
                                    ]),

                                Forms\Components\Builder\Block::make('faq')
                                    ->label('FAQ / Preguntas Frecuentes')
                                    ->icon('heroicon-o-question-mark-circle')
                                    ->schema([
                                        Forms\Components\TextInput::make('heading')->label('Título de la sección')->default('Preguntas Frecuentes'),
                                        Forms\Components\Textarea::make('intro')->label('Introducción (opcional)')->rows(2),
                                        Forms\Components\Repeater::make('items')->label('Preguntas')->schema([
                                            Forms\Components\TextInput::make('question')->label('Pregunta')->required(),
                                            Forms\Components\Textarea::make('answer')->label('Respuesta')->required()->rows(3),
                                        ])->collapsible()->addActionLabel('+ Agregar pregunta')->columnSpanFull(),
                                    ]),

                                // ══════════════════════════════════════════════
                                // 📸 MEDIA
                                // ══════════════════════════════════════════════

                                Forms\Components\Builder\Block::make('gallery')
                                    ->label('Galería de Imágenes')
                                    ->icon('heroicon-o-photo')
                                    ->schema([
                                        Forms\Components\TextInput::make('heading')->label('Título (opcional)')->nullable(),
                                        Forms\Components\Select::make('columns')->label('Columnas en desktop')->options([2 => '2 columnas', 3 => '3 columnas', 4 => '4 columnas'])->default(3),
                                        Forms\Components\Toggle::make('lightbox')->label('¿Activar lightbox al hacer clic?')->default(true),
                                        Forms\Components\Repeater::make('images')->label('Imágenes')->schema([
                                            Forms\Components\FileUpload::make('src')->label('Imagen')->image()->required(),
                                            Forms\Components\TextInput::make('caption')->label('Pie de foto (opcional)')->nullable(),
                                            Forms\Components\TextInput::make('alt')->label('Texto alternativo (SEO)')->nullable(),
                                        ])->collapsible()->addActionLabel('+ Agregar imagen')->columnSpanFull(),
                                    ]),

                                Forms\Components\Builder\Block::make('promotions_carousel')
                                    ->label('Carrusel de Imágenes / Promociones')
                                    ->icon('heroicon-o-rectangle-group')
                                    ->schema([
                                        Forms\Components\TextInput::make('heading')->label('Título de la Sección')->default('Novedades'),
                                        Forms\Components\Repeater::make('items')
                                            ->label('Elementos del Carrusel')
                                            ->schema([
                                                Forms\Components\FileUpload::make('image')->label('Imagen Banner')->image()->required(),
                                                Forms\Components\TextInput::make('title')->label('Título (opcional)')->nullable(),
                                                Forms\Components\TextInput::make('link')->label('Link de redirección (opcional)')->url()->nullable(),
                                            ])->collapsible(),
                                    ]),

                                Forms\Components\Builder\Block::make('video_embed')
                                    ->label('Video Embed (YouTube / Vimeo)')
                                    ->icon('heroicon-o-play-circle')
                                    ->schema([
                                        Forms\Components\TextInput::make('heading')->label('Título (opcional)')->nullable(),
                                        Forms\Components\Textarea::make('description')->label('Descripción (opcional)')->rows(2)->nullable(),
                                        Forms\Components\TextInput::make('video_url')->label('URL del video (YouTube o Vimeo)')->required()->helperText('Ej: https://www.youtube.com/watch?v=XXX o https://vimeo.com/XXX'),
                                        Forms\Components\Toggle::make('autoplay')->label('Autoplay')->default(false),
                                        Forms\Components\Toggle::make('full_width')->label('Ancho completo')->default(true),
                                    ]),

                                // ══════════════════════════════════════════════
                                // 📍 CONTACTO Y EQUIPO
                                // ══════════════════════════════════════════════

                                Forms\Components\Builder\Block::make('contact_form')
                                    ->label('Formulario de Contacto')
                                    ->icon('heroicon-o-envelope')
                                    ->schema([
                                        Forms\Components\TextInput::make('heading')->label('Título del formulario')->default('Contáctanos'),
                                        Forms\Components\Textarea::make('description')->label('Descripción / subtítulo (opcional)')->rows(2),
                                        Forms\Components\TextInput::make('submit_label')->label('Texto del botón de envío')->default('Enviar Mensaje'),
                                        Forms\Components\TextInput::make('success_message')->label('Mensaje de éxito tras enviar')->default('¡Mensaje enviado! Te responderemos pronto.'),
                                        Forms\Components\TextInput::make('notification_email')->label('Email donde llegan las notificaciones')->email()->helperText('Si se deja vacío, se usará MAIL_FROM_ADDRESS del .env')->nullable(),
                                        Forms\Components\Toggle::make('show_captcha')->label('Activar captcha matemático anti-bot')->default(true),
                                        Forms\Components\Repeater::make('fields')
                                            ->label('Campos del formulario')
                                            ->schema([
                                                Forms\Components\TextInput::make('label')->label('Etiqueta del campo')->required()->placeholder('Ej: Tu nombre, Email, Teléfono...'),
                                                Forms\Components\Select::make('type')->label('Tipo de input')->options(['text' => 'Texto corto', 'email' => 'Email', 'tel' => 'Teléfono', 'textarea' => 'Texto largo', 'select' => 'Lista desplegable', 'number' => 'Número'])->default('text')->required()->live(),
                                                Forms\Components\TextInput::make('placeholder')->label('Placeholder (opcional)')->nullable(),
                                                Forms\Components\Textarea::make('options')->label('Opciones (solo para Lista desplegable)')->helperText('Una opción por línea')->rows(3)->nullable()->visible(fn (Forms\Get $get) => $get('type') === 'select'),
                                                Forms\Components\Toggle::make('required')->label('¿Campo obligatorio?')->default(true),
                                                Forms\Components\Toggle::make('is_name')->label('¿Es el campo "Nombre"?')->helperText('Se usará para identificar al remitente en el admin')->default(false),
                                                Forms\Components\Toggle::make('is_email')->label('¿Es el campo "Email"?')->helperText('Se usará para responder directamente al remitente')->default(false),
                                            ])
                                            ->collapsible()
                                            ->defaultItems(3)
                                            ->addActionLabel('+ Agregar campo')
                                            ->columnSpanFull(),
                                    ]),

                                Forms\Components\Builder\Block::make('contact_map_section')
                                    ->label('Ubicación y Contacto (con Mapa)')
                                    ->icon('heroicon-o-map-pin')
                                    ->schema([
                                        Forms\Components\TextInput::make('heading')
                                            ->label('Título de la sección')
                                            ->default('Encuéntranos')
                                            ->helperText('Dirección, teléfono, WhatsApp, horario y mapa se toman automáticamente de Configuración General.'),
                                        Forms\Components\Textarea::make('description')
                                            ->label('Descripción (opcional)')
                                            ->rows(2)
                                            ->nullable(),
                                    ]),

                                Forms\Components\Builder\Block::make('team')
                                    ->label('Equipo / Staff')
                                    ->icon('heroicon-o-user-group')
                                    ->schema([
                                        Forms\Components\TextInput::make('heading')->label('Título de la sección')->default('Nuestro Equipo'),
                                        Forms\Components\Textarea::make('intro')->label('Introducción (opcional)')->rows(2),
                                        Forms\Components\Repeater::make('members')->label('Miembros')->schema([
                                            Forms\Components\TextInput::make('name')->label('Nombre')->required(),
                                            Forms\Components\TextInput::make('role')->label('Cargo / Rol')->required(),
                                            Forms\Components\FileUpload::make('photo')->label('Foto')->image()->nullable(),
                                            Forms\Components\Textarea::make('bio')->label('Biografía corta')->rows(2)->nullable(),
                                            Forms\Components\TextInput::make('linkedin')->label('LinkedIn (URL)')->url()->nullable(),
                                            Forms\Components\TextInput::make('twitter')->label('Twitter/X (URL)')->url()->nullable(),
                                            Forms\Components\TextInput::make('instagram')->label('Instagram (URL)')->url()->nullable(),
                                        ])->collapsible()->addActionLabel('+ Agregar miembro')->columnSpanFull(),
                                    ]),

                            ]),

                ])->columnSpan(['default' => 1, 'lg' => 2]),

                // ── Columna derecha: esqueleto visual ────────────────────
                Forms\Components\Section::make('Esqueleto de la página')
                    ->description('Se actualiza al agregar o quitar bloques.')
                    ->icon('heroicon-o-squares-2x2')
                    ->schema([
                        Forms\Components\Placeholder::make('skeleton_preview')
                            ->label('')
                            ->content(function (Forms\Get $get): \Illuminate\Support\HtmlString {
                                $blocks = $get('builder_content') ?? [];
                                return new \Illuminate\Support\HtmlString(
                                    view('filament.page-skeleton-preview', ['blocks' => $blocks])->render()
                                );
                            }),
                    ])
                    ->columnSpan(['default' => 1, 'lg' => 1])
                    ->extraAttributes(['style' => 'position: sticky; top: 80px;']),

            ])->columns(['default' => 1, 'lg' => 3]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->label('Título')->searchable(),
                Tables\Columns\TextColumn::make('slug')->label('Slug / Enlace'),
                Tables\Columns\IconColumn::make('is_published')->label('¿Publicada?')->boolean(),
                Tables\Columns\TextColumn::make('updated_at')->label('Última Actualización')->dateTime()->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('preview')
                    ->label('Vista previa')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->url(fn (Page $record) => URL::signedRoute(
                        'page.preview',
                        ['slug' => $record->slug],
                        now()->addMinutes(30),
                    ))
                    ->openUrlInNewTab(),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }
}
