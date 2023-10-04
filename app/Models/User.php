<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\strtotime;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [

      'lastName',
      'firstName',
      'sex',
      'email',
      'emailProf',
      'placeBirth',
      'address',
      'dateBirth',
      'nationality',
      'phone',
      'password',
      'phoneEmergency',
      'FamilySituation',
      'nbChildren',
      'levelStudies',
      'specialty',
    //   'sivp',
    //   'durationSivp',
      'matricule',
      'carteId',
      'numPassport',
      'cin',
      'deliveryDateCin',
      'deliveryPlaceCin',
      'contractType',
      'integrationDate',
      'motivation',
      'image',
      'is_deleted',
      'regimeSocial',
      'text'

    ];


    // public function department()
    // {
    //     return $this->belongsTo(Department::class);
    // }

    public function positions()
    {
        return $this->hasMany(PositionUser::class);
    }

    public function contracts()
    {
        return $this->hasMany(UserContract::class);
    }


    public function conges()
    {
        return $this->hasMany(Conge::class);
    }

    public function teamUser()
    {
        return $this->hasMany(TeamUser::class);
    }

    public function teletravails()
    {
        return $this->hasMany(Teletravail::class);
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'motivation' => 'json'
    ];

    public static function checkPermission($id_user,$id_permission)
    {
        $result = DB::table('users')
                ->leftJoin('position_users', 'position_users.user_id', '=', 'users.id')
                ->leftJoin('positions', 'positions.id', '=', 'position_users.position_id')
                ->leftJoin('roles', 'roles.id', '=', 'positions.role_id')
                ->leftJoin('permission_roles', 'permission_roles.role_id', '=', 'roles.id')
                ->leftJoin('permissions', 'permissions.id', '=', 'permission_roles.permission_id')
                ->select('permissions.*')
                ->where('users.id', '=', $id_user)
                ->pluck("id");
        //return 1 s'il existe else return vide !!!!
        return $result->contains($id_permission);
    }

    // $permisisionsIds = $this->position->role->permissions->pluck('id');
    // return $permisisionsIds->contains('id', $this->id)
     /**
     * Send a password reset notification to the user.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $url = 'http://localhost:8080/resetPassword?token=' . $token ."&email=". $this->email;

        Mail::send('email.forgetPassword', ['url' =>$url], function($message) {
            $message->to($this->email);
            $message->subject('Reset Password');
        });
    }
}
