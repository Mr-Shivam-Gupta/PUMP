<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Tenant extends Authenticatable
{
    protected $fillable = [
        'name',
        'slug',
        'domain',
        'contact',
        'email',
        'password',
        'logo',
        'address',
        'gst_number',
        'license_number',
        'type',
        'plan',
        'isolation',
        'database',
        'db_username',
        'db_password',
        'status'
    ];

    protected $hidden = [
        'password',
        'db_password'
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
