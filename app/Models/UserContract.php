<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserContract extends Model
{
    use HasFactory;

    protected $fillable = [
        'startDate',
        'endDate',
        'salary',
        'placeOfWork',
        'startTimeWork',
        'endTimeWork',
        'trialPeriod',
        'fileContract',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }
}
