<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PositionUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'startDate',
        'endDate',
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function position()
    {
        return $this->belongsTo(Position::class,'position_id','id');
    }
}
