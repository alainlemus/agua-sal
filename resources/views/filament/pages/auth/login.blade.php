<div class="login-split">
    <style>
        .login-split {
            display: flex;
            min-height: 100vh;
        }

        .login-split__image {
            flex: 1 1 50%;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .login-split__panel {
            flex: 1 1 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem 1.5rem;
            background-color: #0a1628;
        }

        .login-split__form-wrap {
            width: 100%;
            max-width: 24rem;
        }

        .login-split__brand {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-split__logo {
            height: 4.5rem;
            width: auto;
            margin: 0 auto 1.25rem;
        }

        .login-split__heading {
            font-size: 1.5rem;
            font-weight: 700;
            color: #ffffff;
            margin: 0 0 0.375rem;
        }

        .login-split__subheading {
            font-size: 0.875rem;
            color: #a8b2c8;
            margin: 0;
        }

        .login-split__form {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        .login-split__submit {
            width: 100%;
            padding: 0.625rem 1rem;
            border-radius: 0.5rem;
            border: none;
            background-color: #00b4d8;
            color: #ffffff;
            font-weight: 600;
            font-size: 0.875rem;
            cursor: pointer;
            transition: background-color 0.15s ease-in-out;
        }

        .login-split__submit:hover {
            background-color: #0096c7;
        }

        @media (max-width: 1023px) {
            .login-split__image {
                display: none;
            }

            .login-split__panel {
                flex: 1 1 100%;
            }
        }
    </style>

    <div class="login-split__image" style="background-image: url('{{ asset('images/admin-login-cover.webp') }}')"></div>

    <div class="login-split__panel">
        <div class="login-split__form-wrap">
            <div class="login-split__brand">
                <img src="{{ asset('storage/' . (\App\Models\SiteInfo::first()?->site_logo ?? '')) }}" alt="{{ \App\Models\SiteInfo::first()?->site_name }}" class="login-split__logo">
                <h1 class="login-split__heading">{{ $this->getHeading() }}</h1>
                @if ($subheading = $this->getSubheading())
                    <p class="login-split__subheading">{{ $subheading }}</p>
                @endif
            </div>

            {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::AUTH_LOGIN_FORM_BEFORE, scopes: $this->getRenderHookScopes()) }}

            <form wire:submit="authenticate" class="login-split__form">
                {{ $this->form }}

                <button type="submit" class="login-split__submit">
                    {{ __('filament-panels::auth/pages/login.form.actions.authenticate.label') }}
                </button>
            </form>

            {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::AUTH_LOGIN_FORM_AFTER, scopes: $this->getRenderHookScopes()) }}
        </div>
    </div>

    @if (! $this instanceof \Filament\Tables\Contracts\HasTable)
        <x-filament-actions::modals />
    @endif
</div>
