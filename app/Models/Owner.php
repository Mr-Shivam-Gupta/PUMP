<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Owner extends Model
{
    protected $fillable = ['owner_id', 'owner_password', 'own_tenant_ids', 'status'];

    protected $casts = [
        'own_tenant_ids' => 'array',
    ];
}
