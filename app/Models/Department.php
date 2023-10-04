<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'departmentName',
        'description',
        'status',
        'is_deleted',
        'chef_dep'
    ];

    public function teams()
    {
        return $this->hasMany(Team::class);
    }

    public function faqs()
    {
        return $this->hasMany(FaqDepartment::class);
    }


}
