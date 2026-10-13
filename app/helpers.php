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
if (! function_exists('openStatus')) {
    /**
     * Estado "abierto ahora" calculado desde SiteInfo::schedules
     * (formato: days "Lunes a Domingo" / "Lunes, Martes", hours "11:00 AM - 10:00 PM").
     *
     * @return array{known: bool, open: bool, label: string}
     */
    function openStatus(): array
    {
        $schedules = siteInfo()?->schedules;
        if (! is_array($schedules) || $schedules === []) {
            return ['known' => false, 'open' => false, 'label' => ''];
        }

        $order = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'];
        $norm = static fn (string $s): string => strtr(mb_strtolower($s), ['á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u']);
        $toMinutes = static function (string $h, string $m, string $ap): int {
            $hour = ((int) $h) % 12 + (strtolower($ap) === 'pm' ? 12 : 0);

            return $hour * 60 + (int) $m;
        };

        $now = now('America/Mexico_City');
        $today = $now->dayOfWeekIso - 1;
        $nowMin = $now->hour * 60 + $now->minute;

        foreach ($schedules as $row) {
            $days = $norm((string) ($row['days'] ?? ''));
            $hours = (string) ($row['hours'] ?? '');
            if (! preg_match('/(\d{1,2})(?::(\d{2}))?\s*(am|pm)\s*[-–a]+\s*(\d{1,2})(?::(\d{2}))?\s*(am|pm)/i', $hours, $m)) {
                continue;
            }
            $found = [];
            foreach ($order as $i => $d) {
                if (str_contains($days, $d)) {
                    $found[$i] = $d;
                }
            }
            $covered = [];
            if (count($found) >= 2 && preg_match('/\ba\b|-|–/u', $days) && ! str_contains($days, ',')) {
                $keys = array_keys($found);
                $covered = range(min($keys), max($keys));
            } else {
                $covered = array_keys($found);
            }
            if (! in_array($today, $covered, true)) {
                continue;
            }

            $open = $toMinutes($m[1], $m[2] ?: '0', $m[3]);
            $close = $toMinutes($m[4], $m[5] ?: '0', $m[6]);
            $isOpen = $close > $open ? ($nowMin >= $open && $nowMin < $close) : ($nowMin >= $open || $nowMin < $close);
            $fmt = static fn (int $min): string => \Carbon\Carbon::createFromTime(intdiv($min, 60), $min % 60)->format('g:i A');

            return $isOpen
                ? ['known' => true, 'open' => true, 'label' => 'Abierto ahora · cierra a las '.$fmt($close)]
                : ['known' => true, 'open' => false, 'label' => 'Cerrado · abre a las '.$fmt($open)];
        }

        return ['known' => true, 'open' => false, 'label' => 'Cerrado hoy'];
    }
}
