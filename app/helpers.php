<?php

use App\Models\SiteInfo;
use Illuminate\Support\Facades\Cache;

if (! function_exists('siteInfo')) {
    /**
     * Devuelve el registro SiteInfo con caché de 1 hora.
     * Úsalo en cualquier vista, clase Livewire o Mailable.
     */
    function siteInfo(): ?SiteInfo
    {
        return Cache::remember('site_info', 3600, function (): ?SiteInfo {
            return SiteInfo::first();
        });
    }
}

if (! function_exists('siteName')) {
    /**
     * Devuelve el nombre del sitio desde la BD, con fallback a config('app.name').
     */
    function siteName(): string
    {
        return siteInfo()?->site_name ?: config('app.name', 'Mi Restaurante');
    }
}

if (! function_exists('invalidate_site_info_cache')) {
    /**
     * Invalida el caché de SiteInfo. Llama desde observers.
     */
    function invalidate_site_info_cache(): void
    {
        Cache::forget('site_info');
    }
}