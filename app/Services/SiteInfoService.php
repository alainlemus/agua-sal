<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\SiteInfo;
use Illuminate\Support\Facades\Cache;

final readonly class SiteInfoService
{
    private const CACHE_KEY = 'site_info';

    public function __construct()
    {
    }

    public function get(): ?SiteInfo
    {
        return Cache::remember(self::CACHE_KEY, 3600, function (): ?SiteInfo {
            return SiteInfo::first();
        });
    }

    public function getSiteName(): string
    {
        $siteInfo = $this->get();

        return $siteInfo?->site_name ?: config('app.name', 'Mi Restaurante');
    }

    public function invalidateCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}