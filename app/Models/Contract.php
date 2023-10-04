<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'file',
        'isDeleted'
    ];

    public function users()
    {
        return $this->hasMany(UserContract::class);
    }

}
