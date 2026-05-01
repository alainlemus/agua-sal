<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteInfo extends Model
{
    protected $guarded = [];

    protected $casts = [
        'schedules'       => 'array',
        'social_links'     => 'array',
        'auto_play_music'  => 'boolean',
        'enable_bubbles'   => 'boolean',
        'enable_salt_effect' => 'boolean',
        'enable_waves'     => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saved(function () {
            Cache::forget('site_info');
        });

        static::deleted(function () {
            Cache::forget('site_info');
        });
    }

    public function getThemeColors(): array
    {
        $theme = $this->theme ?? 'default';
        $themes = config('themes', []);

        if (isset($themes[$theme])) {
            return $themes[$theme]['colors'];
        }

        if (isset($themes['default'])) {
            return $themes['default']['colors'];
        }

        return [];
    }
}
