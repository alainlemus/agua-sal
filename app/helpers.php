<?php

use App\Models\SiteInfo;
use Illuminate\Support\Facades\Cache;

if (! function_exists('siteInfo')) {
    function siteInfo(): ?SiteInfo
    {
        return Cache::remember('site_info', 3600, function (): ?SiteInfo {
            return SiteInfo::first();
        });
    }
}

if (! function_exists('siteName')) {
    function siteName(): string
    {
        return siteInfo()?->site_name ?: config('app.name', 'Mi Restaurante');
    }
}

if (! function_exists('invalidate_site_info_cache')) {
    function invalidate_site_info_cache(): void
    {
        Cache::forget('site_info');
    }
}

if (! function_exists('themeColors')) {
    function themeColors(): array
    {
        return siteInfo()?->getThemeColors() ?: [];
    }
}

if (! function_exists('currentTheme')) {
    function currentTheme(): string
    {
        return siteInfo()?->theme ?? 'default';
    }
}

if (! function_exists('getThemeCSS')) {
    function getThemeCSS(): string
    {
        $colors = themeColors();
        if (empty($colors)) {
            return '';
        }

        $css = ':root {';
        foreach ($colors as $key => $value) {
            $css .= "--{$key}:{$value};";
        }
        $css .= '}';

        return $css;
    }
}