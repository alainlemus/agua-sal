<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ReviewToken extends Model
{
    protected $fillable = [
        'review_campaign_id', 'token', 'created_by', 'expires_at', 'used', 'used_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at'    => 'datetime',
        'used'       => 'boolean',
    ];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(ReviewCampaign::class, 'review_campaign_id');
    }

    public function isValid(): bool
    {
        return ! $this->used && $this->expires_at->isFuture();
    }

    public function markUsed(): void
    {
        $this->update(['used' => true, 'used_at' => now()]);
    }

    /** Genera un token único y lo persiste */
    public static function generate(int $campaignId, string $createdBy, int $expiresInMinutes = 120): self
    {
        return self::create([
            'review_campaign_id' => $campaignId,
            'token'              => Str::random(48),
            'created_by'         => $createdBy,
            'expires_at'         => now()->addMinutes($expiresInMinutes),
        ]);
    }

    public function scopeValid($query)
    {
        return $query->where('used', false)->where('expires_at', '>', now());
    }
}
