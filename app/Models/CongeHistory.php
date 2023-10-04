<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CongeHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_responsable',
        'status',
        'is_rejected_prov',
        'is_archive',
        'level',
        'raison_reject'
    ];

    public function conge()
    {
        return $this->belongsTo(Conge::class);
    }

    public function AllConge()
    {
        return $this->belongsTo(Conge::class)->with(['histories']);
    }
}
