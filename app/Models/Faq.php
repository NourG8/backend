<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'response',
        'is_deleted',
        'tags'
    ];

    protected $casts = [
        'tags' => 'json'
    ];

    public function departments()
    {
        return $this->hasMany(FaqDepartment::class);
    }

}
