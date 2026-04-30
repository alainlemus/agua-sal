<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Component;
use Filament\Pages\Auth\Login as BaseLogin;

class Login extends BaseLogin
{
    /**
     * Sobreescribimos el campo de contraseña para agregar el checkbox
     * "Recuérdame" justo debajo de él, dentro del mismo formulario.
     */
    protected function getPasswordFormComponent(): Component
    {
        return parent::getPasswordFormComponent();
    }

    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        $this->getEmailFormComponent(),
                        $this->getPasswordFormComponent(),
                        Checkbox::make('remember')
                            ->label('Recordarme en este dispositivo'),
                    ])
                    ->statePath('data'),
            ),
        ];
    }
}
