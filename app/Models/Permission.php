<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'namePermission',
        'description',
        'code',
        'is_deleted',
        'status'
    ];

    public function roles()
    {
        return $this->hasMany(PermissionRole::class);
    }
}
