<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;

    protected $fillable = [
        'jobName',
        'status',
        'description',
        'is_deleted',
        'title',
    ];

    public function users()
    {
        return $this->hasMany(PositionUser::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }



}
