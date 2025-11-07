<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = [
        'name',
        'duration_days',
        'isolation',
        'max_users',
        'price'
    ];

    protected function casts(): array
    {
        return [
            'duration_days' => 'integer',
            'max_users' => 'integer',
            'price' => 'decimal:2',
        ];
    }

    // Relationships
    public function tenants()
    {
        return $this->hasMany(Tenant::class, 'plan', 'name');
    }

    public function getFormattedPriceAttribute(): string
    {
        return '₹' . number_format($this->price, 2);
    }
}
