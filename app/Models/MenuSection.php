<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MenuSection extends Model
{
    protected $fillable = [
        'menu_id', 'category_id', 'label', 'sort_order',
    ];

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /** Nombre a mostrar: etiqueta custom o nombre de la categoría */
    public function getDisplayNameAttribute(): string
    {
        return $this->label ?: ($this->category?->name ?? '');
    }

    /** Productos activos de esta sección */
    public function products()
    {
        return Product::where('category_id', $this->category_id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }
}
