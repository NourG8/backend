<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'role',
        'status',
        'description',
        'is_deleted'

    ];


    public function positions()
    {
        return $this->hasMany(Position::class);
    }

    public function permissions()
    {
        return $this->hasMany(PermissionRole::class);
    }

    // public function permissions_ids()
    // {
    //     return $this->permissions->pluck('permission_id')->toArray();
    // }
}
