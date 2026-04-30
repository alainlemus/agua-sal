<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PageStatus;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $guarded = [];

    protected $casts = [
        'is_published'    => 'boolean',
        'show_in_nav'     => 'boolean',
        'nav_order'       => 'integer',
        'builder_content' => 'array',
        'modal_enabled'   => 'boolean',
        'modal_show_carousel' => 'boolean',
        'modal_show_video'    => 'boolean',
        'modal_video_autoplay' => 'boolean',
        'modal_carousel_items' => 'array',
        'modal_intrusive' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::updated(function (Page $page) {
            invalidate_site_info_cache();

            if ($page->wasChanged('is_published') && $page->is_published) {
                PageView::create([
                    'type'       => 'pagina_publicada',
                    'slug'       => $page->slug,
                    'label'      => $page->title,
                    'ip_address' => '0.0.0.0',
                    'user_agent' => 'system',
                ]);
            }
        });
    }

    public function getStatusAttribute(): PageStatus
    {
        return $this->is_published ? PageStatus::Published : PageStatus::Draft;
    }

    /** Páginas visibles en el menú de navegación, ordenadas por nav_order */
    public static function navItems()
    {
        return static::where('is_published', true)
            ->where('show_in_nav', true)
            ->orderBy('nav_order')
            ->orderBy('title')
            ->get(['id', 'title', 'slug', 'nav_label', 'nav_icon', 'nav_order']);
    }

    /** Devuelve la URL pública correcta para esta página */
    public function getUrlAttribute(): string
    {
        return $this->slug === 'home' ? '/' : '/' . $this->slug;
    }
}

