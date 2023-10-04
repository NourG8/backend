<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teletravail extends Model
{
    use HasFactory;
    protected $fillable = [
        'raison',
        'date',
        'is_deleted',
        'rep_leader',
        'rep_chefDep',
        'rep_gerant',
        'status',
        'raison_reject',
        'user_reject',
        'level',
        // 'startTime'
    ];

    protected $casts = [
        'date' => 'json'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function histories()
    {
        return $this->hasMany(HistoryRemoteWork::class);
    }

   
}
