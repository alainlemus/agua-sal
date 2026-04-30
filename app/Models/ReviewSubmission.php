<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ReviewStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReviewSubmission extends Model
{
    protected $fillable = [
        'review_campaign_id', 'customer_name', 'customer_email',
        'rating', 'comment', 'gift_code',
        'gift_redeemed', 'gift_redeemed_at', 'gift_redeemed_by', 'ip_address',
    ];

    protected $casts = [
        'gift_redeemed'    => 'boolean',
        'gift_redeemed_at' => 'immutable_datetime',
        'rating'           => 'integer',
    ];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(ReviewCampaign::class, 'review_campaign_id');
    }

    public function getStatusAttribute(): ReviewStatus
    {
        return ReviewStatus::fromGiftRedeemed($this->gift_redeemed);
    }

    public function redeem(string $redeemedBy): void
    {
        $this->update([
            'gift_redeemed'    => true,
            'gift_redeemed_at' => now(),
            'gift_redeemed_by' => $redeemedBy,
        ]);
    }

    public function scopeRedeemed($query)
    {
        return $query->where('gift_redeemed', true);
    }

    public function scopePending($query)
    {
        return $query->where('gift_redeemed', false);
    }
}
