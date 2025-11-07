<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    protected $fillable = [
        'plan_id',
        'tenant_id',
        'start_date',
        'end_date',
        'is_trial',
        'subscription_status'
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'is_trial' => 'boolean',
            'subscription_status' => 'integer',
        ];
    }

    // Constants for subscription status
    const STATUS_ACTIVE = 1;
    const STATUS_EXPIRED = 0;
    const STATUS_TRIAL = 2;
    const STATUS_CANCELED = 3;

    // Relationships
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    // Helper methods
    public function isActive(): bool
    {
        return $this->subscription_status === self::STATUS_ACTIVE;
    }

    public function isExpired(): bool
    {
        return $this->subscription_status === self::STATUS_EXPIRED;
    }

    public function isTrial(): bool
    {
        return $this->subscription_status === self::STATUS_TRIAL || $this->is_trial;
    }

    public function isCanceled(): bool
    {
        return $this->subscription_status === self::STATUS_CANCELED;
    }

    public function isValid(): bool
    {
        if ($this->isExpired() || $this->isCanceled()) {
            return false;
        }

        if ($this->end_date && $this->end_date->isPast()) {
            return false;
        }

        return true;
    }

    public function getRemainingDaysAttribute(): int
    {
        if (!$this->end_date || $this->end_date->isPast()) {
            return 0;
        }

        return now()->diffInDays($this->end_date, false);
    }

    public function scopeActive($query)
    {
        return $query->where('subscription_status', self::STATUS_ACTIVE);
    }

    public function scopeExpired($query)
    {
        return $query->where('subscription_status', self::STATUS_EXPIRED);
    }

    public function scopeTrial($query)
    {
        return $query->where('subscription_status', self::STATUS_TRIAL);
    }

    public function scopeValid($query)
    {
        return $query->where(function ($q) {
            $q->where('subscription_status', self::STATUS_ACTIVE)
                ->orWhere('subscription_status', self::STATUS_TRIAL);
        })->where(function ($q) {
            $q->whereNull('end_date')
                ->orWhere('end_date', '>', now());
        });
    }
}
