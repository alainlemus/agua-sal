<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteInfo extends Model
{
    protected $guarded = [];

    protected $casts = [
        'schedules'     => 'array',
        'social_links'  => 'array',
        'auto_play_music' => 'boolean',
    ];
}
