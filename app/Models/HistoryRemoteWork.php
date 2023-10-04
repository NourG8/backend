<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryRemoteWork extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_responsable',
        'status',
        'is_rejected_prov',
        'level',
        'raison_reject'
    ];

    public function teletravail()
    {
        return $this->belongsTo(Teletravail::class);
    }

}
