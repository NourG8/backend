<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'is_deleted',
        'status'
    ];

    public function teamUser()
    {
        return $this->hasMany(TeamUser::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }


}
