<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermissionGroup extends Model
{
    protected $fillable = [
        'name',
        'category',
        'status',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at'
    ];

    public function permissions()
    {
        return $this->hasMany(Permission::class, 'permission_group_id');
    }

    public static function getActiveGroups()
    {
        return self::where('status', 1)->get();
    }
}
