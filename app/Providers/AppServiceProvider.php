<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\SiteInfoService;
use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(SiteInfoService::class, function () {
            return new SiteInfoService();
        });
    }

    public function boot(): void
    {
        //
    }
}