<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon  = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Usuarios';
    protected static ?string $navigationGroup = 'Administración';
    protected static ?int    $navigationSort  = 10;
    protected static ?string $modelLabel      = 'usuario';
    protected static ?string $pluralModelLabel = 'usuarios';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Datos del usuario')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Nombre')
                        ->required()
                        ->maxLength(255),

                    Forms\Components\TextInput::make('email')
                        ->label('Correo electrónico')
                        ->email()
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255),

                    Forms\Components\TextInput::make('password')
                        ->label('Contraseña')
                        ->password()
                        ->revealable()
                        ->dehydrateStateUsing(fn ($state) => filled($state) ? Hash::make($state) : null)
                        ->dehydrated(fn ($state) => filled($state))
                        ->required(fn (string $operation) => $operation === 'create')
                        ->helperText('Deja vacío para no cambiar la contraseña.')
                        ->maxLength(255)
                        ->columnSpanFull(),
                ]),

            Forms\Components\Section::make('Rol y accesos')
                ->schema([
                    Forms\Components\Select::make('roles')
                        ->label('Rol')
                        ->relationship('roles', 'name')
                        ->options(
                            Role::all()->pluck('name', 'id')->map(fn ($name) => match ($name) {
                                'super_admin'  => 'Super Admin',
                                'editor'       => 'Editor',
                                'mesero'       => 'Mesero',
                                'solo_lectura' => 'Solo lectura',
                                default        => ucfirst($name),
                            })
                        )
                        ->preload()
                        ->searchable()
                        ->required()
                        ->helperText('El rol determina qué secciones del panel puede ver y editar.'),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Correo')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('roles.name')
                    ->label('Rol')
                    ->colors([
                        'danger'  => 'super_admin',
                        'warning' => 'editor',
                        'success' => 'mesero',
                        'gray'    => 'solo_lectura',
                    ])
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'super_admin'  => 'Super Admin',
                        'editor'       => 'Editor',
                        'mesero'       => 'Mesero',
                        'solo_lectura' => 'Solo lectura',
                        default        => ucfirst($state),
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('roles')
                    ->label('Rol')
                    ->relationship('roles', 'name')
                    ->options([
                        'super_admin'  => 'Super Admin',
                        'editor'       => 'Editor',
                        'mesero'       => 'Mesero',
                        'solo_lectura' => 'Solo lectura',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->before(function (User $record) {
                        // No se puede eliminar el propio usuario ni al último super_admin
                        if ($record->id === auth()->id()) {
                            \Filament\Notifications\Notification::make()
                                ->danger()
                                ->title('No puedes eliminar tu propio usuario.')
                                ->send();
                            $this->halt();
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit'   => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
