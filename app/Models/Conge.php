<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conge extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'raison',
        'dates',
        'status',
        'is_deleted',
        'id_conge_rejet',
        'date'
    ];

    protected $casts = [
        'dates' => 'json'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function histories()
    {
        return $this->hasMany(CongeHistory::class);
    }

    public static function getAllCongeUser($id_user)
    {
        $conges = Conge::where([['is_deleted', '=', 0],['user_id', '=', $id_user],['status', '!=', 'Annuler'],['status', '!=', 'Accepter'],['status', '!=', 'Rejet définitif']])->with([
            'histories' => fn($query) => $query->where([['id_responsable', '!=', $id_user]]),
        ])->get();

        foreach ($conges as $conge) {
            if(count($conge['histories']) != 0){
                foreach ($conge['histories'] as $history) {
                    $responsable = User::findOrFail($history['id_responsable']);
                    $history['fullName'] = $responsable['lastName'] .' '. $responsable['firstName'];
                }
            }

        }

        return $conges;
    }

}
