<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'country',
        'logo',
        'email',
        'phone',
        'creation_date',
        'status',
        'description',
        'max_cin',
        'min_cin',
        'max_passport',
        'min_passport',
        'is_deleted',
        'nationality',
        'regimeSocial',
        'type',
        'color',
        'color2',
        'typeTeletravail',
        'startTime',
        'endTime',
        'max_teletravail'
       
    ];

    // protected $casts = [
    //     'regimeSocial' => 'array'
    // ];

}
