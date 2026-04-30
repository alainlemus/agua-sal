<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class ReviewCampaign extends Model
{
    protected $fillable = [
        'name', 'slug', 'gift_title', 'gift_description',
        'gift_code_prefix', 'is_active', 'max_uses',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'max_uses'  => 'integer',
    ];

    public function submissions(): HasMany
    {
        return $this->hasMany(ReviewSubmission::class);
    }

    public function getReviewUrl(): string
    {
        return route('review.form', $this->slug);
    }

    public function getUsedCount(): int
    {
        return $this->submissions()->count();
    }

    public function isAvailable(): bool
    {
        if (! $this->is_active) return false;
        if ($this->max_uses !== null && $this->getUsedCount() >= $this->max_uses) return false;
        return true;
    }

    /** Genera un código de regalo único */
    public function generateGiftCode(): string
    {
        do {
            $code = strtoupper($this->gift_code_prefix . '-' . Str::random(4));
        } while (ReviewSubmission::where('gift_code', $code)->exists());

        return $code;
    }
}
