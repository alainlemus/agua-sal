<?php

namespace App\Models;

use App\Jobs\ResolvePageViewLocation;
use Illuminate\Database\Eloquent\Model;

class PageView extends Model
{
    protected $fillable = [
        'type',
        'slug',
        'label',
        'ip_address',
        'user_agent',
        'country',
        'country_code',
        'city',
    ];

    /**
     * Registra una visita desde el request actual y despacha
     * un job para resolver la ubicación geográfica por IP.
     */
    public static function record(string $type, ?string $slug = null, ?string $label = null): void
    {
        $ip = request()->ip() ?? '';

        $view = static::create([
            'type'       => $type,
            'slug'       => $slug,
            'label'      => $label,
            'ip_address' => $ip,
            'user_agent' => substr(request()->userAgent() ?? '', 0, 255),
        ]);

        if ($ip) {
            ResolvePageViewLocation::dispatch($view->id, $ip);
        }
    }
}
