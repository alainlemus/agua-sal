<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ContactStatus;
use Illuminate\Database\Eloquent\Model;

class ContactSubmission extends Model
{
    protected $guarded = [];

    protected $casts = [
        'fields_data'  => 'array',
        'is_attended'  => 'boolean',
        'attended_at'  => 'immutable_datetime',
    ];

    public function getStatusAttribute(): ContactStatus
    {
        return ContactStatus::fromIsAttended($this->is_attended);
    }

    /** Scope: no atendidos */
    public function scopeUnattended($query)
    {
        return $query->where('is_attended', false);
    }

    /** Marcar como atendido */
    public function markAsAttended(string $by = 'admin'): void
    {
        $this->update([
            'is_attended'  => true,
            'attended_at'  => now(),
            'attended_by'  => $by,
        ]);
    }
}
