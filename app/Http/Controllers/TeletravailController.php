<?php

namespace App\Http\Controllers;

use App\Models\Teletravail;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTeletravailRequest;
use App\Http\Requests\UpdateTeletravailRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Carbon;
use Illuminate\Support\strtotime;
use App\Models\User;
use App\Models\Team;
use App\Models\TeamUser;
use App\Models\Department;
use App\Models\HistoryRemoteWork;
use Illuminate\Support\Facades\Auth;

class TeletravailController extends Controller
{
    private $tel= null;
    private $user= null;
    private $email= null;
    private $date_actuelle = null;
    private $url = null;
    private $userPassTeletravail= null;

    private $leader = null;
    private $chef_dep = null;
    private $gerants = null;
    private $teletravail = null;
    private $list_responsable = [];
    private $result =null;
    
    public function getAllTeletravils($id_auth)
    {
        $result = [];
        // ken user auth = leader !!
        $test_fonction = CongeController::test_Leader_ChefDep_Gerant($id_auth);

        if($test_fonction['leader'] == 1){
           $result = TeletravailController::getAllTeletravailLeader($id_auth);
        }
        if($test_fonction['chef_dep'] == 1){
            $result = TeletravailController::getAllTeletravailChefDepartment($id_auth);
        }
        if($test_fonction['gerant'] == 1){
            $result = TeletravailController::getAllTeletravailGerant($id_auth);
        }

        if($test_fonction['leader'] == 1 && $test_fonction['chef_dep'] == 1){
            $result_1 = TeletravailController::getAllTeletravailLeader($id_auth);
            $result_2 = TeletravailController::getAllTeletravailChefDepartment($id_auth);
            $result = array_merge($result_1, $result_2);
         }

         if($test_fonction['gerant'] == 1 && $test_fonction['chef_dep'] == 1){
            $result_1 = TeletravailController::getAllTeletravailGerant($id_auth);
            $result_2 = TeletravailController::getAllTeletravailChefDepartment($id_auth);
            $result = array_merge($result_1, $result_2);
         }

         if($test_fonction['leader'] == 1 && $test_fonction['gerant'] == 1){
            $result_1 = TeletravailController::getAllTeletravailLeader($id_auth);
            $result_2 = TeletravailController::getAllTeletravailGerant($id_auth);
            $result = array_merge($result_1, $result_2);
         }

         if($test_fonction['leader'] == 1 && $test_fonction['gerant'] == 1 && $test_fonction['chef_dep'] == 1){
            $result_1 = TeletravailController::getAllTeletravailLeader($id_auth);
            $result_2 = TeletravailController::getAllTeletravailGerant($id_auth);
            $result_3 = TeletravailController::getAllTeletravailChefDepartment($id_auth);
            $result = array_merge($result_1, $result_2, $result_3);
         }

        return response()->json([
            'teletravails' => $result,
            'success' => true
        ], 200);
    }

    public function ResponsableAddTeletravail($id)
    {
        $this->user = CongeController::getUser($id);
        $this->leader = CongeController::getLeader($id);
        $this->gerants = CongeController::getAllGerants($id);
        $this->chef_dep = array_values(array_unique(CongeController::getChefDepartement($id)));


        if(count($this->chef_dep) == 0){
            $ids_gerants = TeletravailController::get_ids_gerants($id);

            if(in_array($id,$ids_gerants)){
                $tel_history = new HistoryRemoteWork();
                $tel_history->id_responsable = $id;
                $tel_history->status = "Accepter";
                $tel_history->is_rejected_prov = 0;
                $tel_history->level = 3;
                $tel_history->is_archive = 0;
                $tel_history->teletravail_id = $this->teletravail['id'];
                $tel_history->save();

                $gerants = $this->gerants;
                $this->gerants = [];
                foreach($gerants as $g) {
                    if($g['id'] != $id){
                      array_push($this->gerants,$g);
                    }
                }
            }

                $tele = Teletravail::findOrFail($this->teletravail['id']);

                if(count($this->gerants) == 0){
                    $tele->status = "Accepter";
                    $tele->level = 3;
                    $tele->save();
                }else{
                    $tele->level = 3;
                    $tele->save();
                    Mail::send('teletravail.InfoEmail', ['user' => $this->user], function($message) {
                        foreach ($this->gerants as $gerant) {
                            $message->to($gerant['email']);
                        }
                        $message->subject('Remote work request');
                    });
                }
                $this->teletravail = $tele;
            }else{
                $ids_leaders = TeletravailController::get_ids_leaders($id);

                if(in_array($id,$ids_leaders)){
                    $tel_history = new HistoryRemoteWork();
                    $tel_history->id_responsable = $id;
                    $tel_history->status = "Accepter";
                    $tel_history->is_rejected_prov = 0;
                    $tel_history->level = 1;
                    $tel_history->is_archive = 0;
                    $tel_history->teletravail_id = $this->teletravail['id'];
                    $tel_history->save();

                    $leader = $this->leader;
                    $this->leader = [];
                    foreach ($leader as $l) {
                        if($l['id'] != $id){
                        array_push($this->leader,$l);
                        }
                    }
                }

                if(count($this->leader) == 0){
                    $tele = Teletravail::findOrFail($this->teletravail['id']);
                    $tele->level = 2;
                    $tele->save();

                    Mail::send('teletravail.InfoEmail', ['user' => $this->user], function($message) {
                        foreach ($this->chef_dep as $chef_dep) {
                           $message->to($chef_dep['email']);
                        }
                        $message->subject('Remote work request');
                    });
                    $this->teletravail = $tele;
                }else{
                    Mail::send('teletravail.InfoEmail', ['user' => $this->user], function($message) {
                        foreach ($this->leader as $leader) {
                        $message->to($leader['email']);
                        }
                        $message->subject('Remote work request');
                    });
                }
            }
    }

    public function get_ids_leaders($id)
    {
       $leader = CongeController::getLeader($id);
        $id_leaders = [];
        foreach ($leader as $l) {
            array_push($id_leaders,$l['id']);
        }
        return $id_leaders;
    }

    public function get_ids_gerants($id)
    {
        $gerant = CongeController::getAllGerants($id);
        $id_gerants = [];
        foreach ($gerant as $g) {
            array_push($id_gerants,$g['id']);
        }
        return $id_gerants;
    }

    
    public function get_ids_chef_dep($id)
    {
        $chef_dep = array_values(array_unique(CongeController::getChefDepartement($id)));
        $id_chef_dep = [];
        foreach ($chef_dep as $c) {
            array_push($id_chef_dep,$c['id']);
        }
        return $id_chef_dep;
    }

    public function AddTeletravail(Request $request)
    {
        $user = Auth::user();

        $this->teletravail = new Teletravail();
        $this->teletravail->raison = $request->input('raison');
        $this->teletravail->date = $request->input('date');
        $this->teletravail->level = 1;
        $this->teletravail->is_deleted = 0;
        $this->teletravail->status = "Envoyé";
        $this->teletravail->user_id = $user->id;
        $this->teletravail->save();

        TeletravailController::ResponsableAddTeletravail($user->id);

        return response()->json([
            'teletravail' => $this->teletravail,
            'success' => true
        ], 200);

    }

    public function getTeletravailsUser()
    {
        $this->user = Auth::user();
        $teletravails = Teletravail::where([
            ['teletravails.is_deleted','=',0],
            ['teletravails.user_id','=', $this->user->id],
            ])
            ->with([
            'user' =>
            fn($query) => $query->where([['is_deleted', '=', 0],['status', '=', "active"]])
            ])->get();
        return response()->json([
            'teletravails' => $teletravails,
            'success' => true
        ], 200);
    }

    public function editTeletravail(Request $request,$id)
    {
        $this->teletravail = Teletravail::findOrFail($id);  
        $tels_history = HistoryRemoteWork::where('teletravail_id','=',$id)->update(['is_rejected_prov' =>1,'is_archive'=> 1]);
        // $tels_history = HistoryRemoteWork::where('teletravail_id','=',$id)->update(['is_rejected_prov' =>1,]);

        $this->teletravail->raison= $request->raison;
        $this->teletravail->date= $request->date;
        $this->teletravail->is_deleted = 0;
        $this->teletravail->level = 1;
        $this->teletravail->status = "Envoyé";
        $this->teletravail->save();

        TeletravailController::ResponsableAddTeletravail($this->teletravail->user_id);

        return response()->json([
            'teletravail' =>$this->teletravail,
            'success' => true
        ], 200);
    }
    public function destroyTeletravail($id)
    {
        $tel = DB::table('teletravails')
        ->leftJoin('users', 'users.id', '=', 'teletravails.user_id')
        ->select('users.*','teletravails.*')
        ->where([
            ['users.is_deleted', '=', null],
            ['teletravails.is_deleted', '=', null],
            ['teletravails.id', '=', $id],
        ])->update(['teletravails.is_deleted' =>1]);
        return response()->json([
            'teletravail' => $tel,
            'success' => true
        ], 200);
    }

    //nbre de leader pour chaque user
    public function getNbLeaders($id)
    {
       $leaders = CongeController::getLeader($id);
       $nbLeaders = [];
       foreach ($leaders as $leader) {
            if($leader['id'] != $id){
                array_push( $nbLeaders,$leader);
            }
       }
        return count($nbLeaders);
    }

    public function getNbChefDep($id)
    {
       $chefDep = CongeController::getChefDepartement($id);
       $nbChefDep = [];
       foreach ($chefDep as $chef) {
            if($chef['id'] != $id){
                array_push( $nbChefDep,$chef);
            }
       }
        return count($nbChefDep);
    }

    public function getNbGerants()
    {
        $gerants = CongeController::getAllGerants();
        return count($gerants);
    }

    public function acceptTelChefDep($id)
    {
        $this->user = Auth::user();  
        $teletravail = Teletravail::findOrFail($id);
        $List_teltravail=[];
        $ids_chef_dep = TeletravailController::get_ids_chef_dep($teletravail['user_id']);
        
        if(in_array($this->user['id'],$ids_chef_dep)){
        $telHistory = new HistoryRemoteWork();
        $telHistory->is_rejected_prov = 0;
        $telHistory->level = 2;
        $telHistory->is_archive = 0;
        $telHistory->status = "Accepter";
        $telHistory->teletravail_id = $id;
        $telHistory->id_responsable = $this->user['id'];
        $telHistory->save();

        $teletravail->status="En cours";
        $teletravail->save();
       

        $test=Teletravail::where([['is_deleted', '=', 0],['status','=','Envoyé'],['level','=','2'],['id','=',$id]])->orWhere([['is_deleted', '=', 0],['status','=','En cours'],['level','=','2'],['id','=',$id]])
        ->with(['histories' => fn($query) => $query->where([['is_rejected_prov', '=', 0],['level', '=', 2],['status','=','Accepter'],['teletravail_id', '=', $id]]),
        ])->get();

        if(count($test) > 0){
            foreach ($test as $teletravail) {
                array_push($List_teltravail,$teletravail);
            }
        }
        $chef_dep = CongeController::getChefDepartement($teletravail['user_id']);
        $gerants = CongeController::getAllGerants();
        $this->user = CongeController::getUser($teletravail['user_id']);

        $this->gerants = array_diff($gerants, $chef_dep);

        if(count($List_teltravail)>0){
            if(count($List_teltravail[0]['histories']) == count($chef_dep)){
                $teletravails = DB::table('teletravails')
                ->select('teletravails.*')
                ->where([
                    ['id', '=',$id],
                    ['is_deleted', '=', 0],
                    ['level', '=', 2],
                ])->update(['level' => 3]);

                if(count($this->gerants) != 0){
                    Mail::send('teletravail.InfoEmail', ['user' => $this->user], function($message) {
                        foreach ($this->gerants as $gerant) {
                            $message->to($gerant['email']);
                        }
                        $message->subject('Leave request');
                    });
                }

            }
         }
        }

        return $List_teltravail;
    }

    public function acceptTelGerant($id)
    {
        $leader = Auth::user();

        $telHistory = new HistoryRemoteWork();
        $telHistory->is_rejected_prov = 0;
        $telHistory->level = 3;
        $telHistory->is_archive = 0;
        $telHistory->status = "Accepter";
        $telHistory->teletravail_id = $id;
        $telHistory->id_responsable = $leader['id'];
        $telHistory->save();

        $teletravail = Teletravail::findOrFail($id);
        $teletravail->status="En cours";
        $teletravail->save();

        $this->user = User::findOrFail($teletravail['user_id']);
        $List_teltravail=[];

        $test=Teletravail::where([['is_deleted', '=', 0],['status','=','En cours'],['level','=','3'],['id','=',$id]])
        ->with(['histories' => fn($query) => $query->where([['is_rejected_prov', '=', 0],['level', '=', 3],['status','=','Accepter'],['teletravail_id', '=', $id]]),
        ])->get();
      
        if(count($test) != 0){
            foreach ($test as $teletravail) {
                array_push($List_teltravail,$teletravail);
            }
        }  
        
        $gerants = CongeController::getAllGerants();
            if(count($List_teltravail) != 0){
            if(count($List_teltravail[0]['histories']) == count($gerants) ){
                $teletravail = DB::table('teletravails')
                ->select('teletravails.*')
                ->where([
                    ['id', '=',$id],
                    ['is_deleted', '=', 0],
                    ['level', '=', 3],
                ])->update(['status' => "Accepter"]);

                $this->userPassTeletravail = User::findOrFail($List_teltravail[0]['user_id']);
                $this->result = "Accepter";
                $dates=$List_teltravail[0]['date'];


                    Mail::send('teletravail.responseAccepte', ['user' => $this->userPassTeletravail, 'dates' => $dates], function($message) {
                        /** @var User $user */
                      $this->user = Auth::user();
                      $message->from( $this->user['email']);
                      $message->to($this->userPassTeletravail['email']);
                      $message->subject('Acceptance of your remote work request');
                  });
            }
        }

        return $List_teltravail;
    }

    public function acceptTelLeader($id_teletravail)
    {
        $leader = Auth::user();

        $List_teletravails = [];

        $teletravail = Teletravail::findOrFail($id_teletravail);

        $ids_leaders = CongeController::get_ids_leaders($teletravail['user_id']);

        if(in_array($leader['id'],$ids_leaders)){
        $telHistory = new HistoryRemoteWork();
        $telHistory->is_rejected_prov = 0;
        $telHistory->level = 1;
        $telHistory->status = "Accepter";
        $telHistory->is_archive = 0;
        $telHistory->teletravail_id = $id_teletravail;
        $telHistory->id_responsable = $leader['id'];
        $telHistory->save();

        $teletravail->status="En cours";
        $teletravail->save();

        $test=Teletravail::where([['is_deleted', '=', 0],['status','=','Envoyé'],['level','=','1'],['id','=',$id_teletravail]])->orWhere([['is_deleted', '=', 0],['status','=','En cours'],['level','=','1'],['id','=',$id_teletravail]])->with([
            'histories' => fn($query) => $query->where([['is_rejected_prov', '=', 0],['level', '=', 1],['status','=','Accepter'],['teletravail_id', '=', $id_teletravail]]),
        ])->get();

        if(count($test) !=0){
            foreach ($test as $teletravail) {
                array_push($List_teletravails,$teletravail);
            }
        }
        
        $Leaders = CongeController::getLeader($teletravail['user_id']);
        $chef_dep = CongeController::getChefDepartement($teletravail['user_id']);
        $this->user = CongeController::getUser($teletravail['user_id']);

        $this->chef_dep = array_diff($chef_dep, $Leaders);

        if(count($List_teletravails) != 0){
            if(count($List_teletravails[0]['histories']) == count($Leaders) ){
                $teletravail = DB::table('teletravails')
                ->select('teletravails.*')
                ->where([
                    ['id', '=',$id_teletravail],
                    ['is_deleted', '=', 0],
                    ['level', '=', 1],
                ])->update(['level' => 2]);

                    if(count($this->chef_dep) != 0){
                        Mail::send('teletravail.InfoEmail', ['user' => $this->user], function($message) {
                            foreach ($this->chef_dep as $chef) {
                                $message->to($chef['email']);
                            }
                            $message->subject('Leave request');
                        });
                    }
            }
        }
        }
        return $List_teletravails;
    }

    public function accepter($id)
    {
        $result = [];

        $this->user = Auth::user();
        //l user eli authentifié 9ade 3andou men leader w gerant w chef
        $test_fonction = CongeController::test_Leader_ChefDep_Gerant($this->user['id']);

        if($test_fonction['leader'] == 1  && $test_fonction['gerant'] == 0 && $test_fonction['chef_dep'] == 0){
           $result = TeletravailController::acceptTelLeader($id);
        }
        if($test_fonction['chef_dep'] == 1 && $test_fonction['gerant'] == 0 && $test_fonction['leader'] == 0){
            $result = TeletravailController::acceptTelChefDep($id);
        }
        if($test_fonction['gerant'] == 1 && $test_fonction['leader'] == 0 && $test_fonction['chef_dep'] == 0){
            $result = TeletravailController::acceptTelGerant($id);
        }

        if($test_fonction['leader'] == 1 && $test_fonction['chef_dep'] == 1 && $test_fonction['gerant'] == 0){
            $result_1 = TeletravailController::acceptTelLeader($id);
            $result_2 = TeletravailController::acceptTelChefDep($id);
            $result = array_merge($result_1, $result_2);
         }

         if($test_fonction['gerant'] == 1 && $test_fonction['chef_dep'] == 1 && $test_fonction['leader'] == 0){
            $result_1 = TeletravailController::acceptTelGerant($id);
            $result_2 = TeletravailController::acceptTelChefDep($id);
            $result = array_merge($result_1, $result_2);
         }

         if($test_fonction['leader'] == 1 && $test_fonction['gerant'] == 1 && $test_fonction['chef_dep'] == 0){
            $result_1 = TeletravailController::acceptTelLeader($id);
            $result_2 = TeletravailController::acceptTelGerant($id);
            $result = array_merge($result_1, $result_2);
         }

         if($test_fonction['leader'] == 1 && $test_fonction['gerant'] == 1 && $test_fonction['chef_dep'] == 1){
            $result_1 = TeletravailController::acceptTelLeader($id);
            $result_2 = TeletravailController::acceptTelGerant($id);
            $result_3 = TeletravailController::acceptTelChefDep($id);
            $result = array_merge($result_1, $result_2, $result_3);
         }

        return response()->json([
            'teletravails' => $result,
            'test_fonction' => $test_fonction,
            'success' => true
        ], 200);
    }

    public function refuseTeletravail(Request $request,$id)
    {
        $tel = DB::table('teletravails')
        ->leftJoin('users', 'users.id', '=', 'teletravails.user_id')
        ->select('users.*','teletravails.*')
        ->where([
            ['users.is_deleted', '=', null],
            ['teletravails.is_deleted', '=', null],
            ['teletravails.id', '=', $id],
        ])->update(['teletravails.status' => 0]);
        $this->email=$request->email;
 
        Mail::send('email.responseRefuse', ['tel' =>$tel, 'email',$this->email], function($message) {
            /** @var User $user */
          $this->user = Auth::user();
          $message->from( $this->user['email']);
          $message->to($this->email);
          $message->subject('Refusal of your remote work request');
      });
        return response()->json([
            'teletravail' => $tel,
            'success' => true
        ], 200);
    }

    public function getUsersFonct($id_user)
    {
       $userAuth=teletravailController::getUser($id_user);
       $leader=teletravailController::getLeader($id_user);
       $chefDep=teletravailController::getChefDepartement($id_user);
       $gerants=teletravailController::getAllGerants();
       $fonction_User_Auth=teletravailController::test_Leader_ChefDep_Gerant($id_user);
       return $fonction_User_Auth['leader'];
    }

    public function getAllTeletravailLeader($id_auth)
    {
        // TeletravailControlle::getNbLeaders()
        $List_teletravails = [];
        // afficher list pour le responsable !!!!
        $List_team = TeamUser::where([['user_id','=',$id_auth],['is_deleted','=',0],['is_leader','=',1]])->get();
        $team_id = [];
        // return id team eli appartient liha el id_auth kenou leader ala equipe !!!
        if(count($List_team) != 0){
            foreach ($List_team as $team) {
                array_push($team_id,$team['team_id']);
            }
        }else{
            $team_id =  null;
        }

        $user = Auth::user();

        $teletravails=Teletravail::where([['is_deleted', '=', 0],['level','=','1'],['status','=','En cours']])
                               ->orwhere([['is_deleted', '=', 0],['level','=','1'],['status','=','Envoyé']])
                                  ->with([
                        'histories' => fn($query) => $query->where([['is_rejected_prov', '=', 0],['level', '=', 1],['id_responsable','=',$user->id]]),
                        'user'=> fn($query) => $query->where([['is_deleted', '=', 0]])->with('teamUser')
        ])->get();

       $user_team = [];
        foreach ($teletravails as $teletravail) {
            foreach ($teletravail['user']['teamUser'] as $team) {
                if($team_id != null){
                    if(in_array($team['team_id'],$team_id)){
                        array_push($List_teletravails,$teletravail);
                        $user_team = array_values(array_unique($List_teletravails));
                    }
                }
            }
        }
        $rep_responsable = false;
        $List_teletravails_final = [];
        foreach ($user_team as $teletravail) {
            if(count($teletravail['histories']) == 0){
                array_push($List_teletravails_final,$teletravail);
            }else{
                foreach ($teletravail['histories'] as $history) {
                    if($history['id_responsable'] == $id_auth){
                            $rep_responsable = true;
                    }else{
                        array_push($List_teletravails_final,$teletravail);
                    }
                }
            }
        }
        return $result = array_values(array_unique($List_teletravails_final));
    }

    public function getAllTeletravailChefDepartment($id_auth)
    {
      
        $user = Auth::user();
        $List_teletravails = [];
        $List_department = Department::where([['chef_dep','=',$id_auth],['is_deleted','=',0]])->get();
        $List_id_department = [];

        // return id team eli appartient liha el id_auth kenou leader ala equipe !!!
        if(count($List_department) != 0){
            foreach ($List_department as $dep) {
                array_push($List_id_department,$dep['id']);
            }
        }else{
            $List_id_department =  null;
        }  

        $teletravails = Teletravail::where([['is_deleted', '=', 0],['level','=',2],['status','!=','Annuler']])->with([
            'histories' => fn($query) => $query->where([['is_rejected_prov', '=', 0],['level', '=', 2],['id_responsable','=',$user->id]]),
            'user'=> fn($query) => $query->where('is_deleted', '=', 0)->with([
                'teamUser'=> ([
                    'team'=> fn($query) => $query->where([['is_deleted', '=', 0],['status','=','active']])->with([
                        'department'=> fn($query) => $query->where([['is_deleted', '=', 0],['status','=','active']])->pluck('id')
                        ])
                ])
            ])
            ])->get();

            $user_dep = [];

            foreach ($teletravails as $teletravail) {
                foreach ($teletravail['user']['teamUser'] as $team) {
                    if($team['team'] != null && $List_id_department != [] ){
                        if(in_array($team['team']['department']['id'],$List_id_department)){
                            array_push($List_teletravails,$teletravail);
                            $user_dep = array_values(array_unique($List_teletravails));
                        }
                    }
                }
            }
        $rep_responsable = false;
        $List_teletravails_final = [];

        foreach($user_dep as $teletravail) {
            if(count($teletravail['histories']) == 0){
                array_push($List_teletravails_final,$teletravail);
            }else{
                foreach ($teletravail['histories'] as $history) {
                    if($history['id_responsable'] == $id_auth){
                            $rep_responsable = true;
                    }else{
                        array_push($List_teletravails_final,$teletravail);
                    }
                }
            }
        }
        return $result = array_values(array_unique($List_teletravails_final));
    }

    public function getAllTeletravailGerant($id_auth)
    { 
        $user = Auth::user();
        // afficher list pour le gerant !!!!
            $result_gerants = Teletravail::where([['is_deleted', '=', 0],['level','=',3],['status','!=','Annuler']])->with([
                'histories' => fn($query) => $query->where([['is_rejected_prov', '=', 0],['level', '=', 3],['id_responsable','=',$user->id]]),
                'user'=> fn($query) => $query->where('is_deleted', '=', 0)
            ])->get();

        $rep_responsable = false;

        $List_teletravails_final = [];

        foreach($result_gerants as $teletravail) {
            if(count($teletravail['histories']) == 0){
                array_push($List_teletravails_final,$teletravail);
            }else{
                foreach ($teletravail['histories'] as $history) {
                    if($history['id_responsable'] == $id_auth){
                            $rep_responsable = true;
                    }else{
                        array_push($List_teletravails_final,$teletravail);
                    }
                }
            }
        }
        return $result = array_values(array_unique($List_teletravails_final));
    }

    public function deleteTeletravail($id)
    {
        $teletravail = Teletravail::findOrFail($id);
        $teletravail->is_deleted = 1;
        $teletravail->save();
        return response()->json([
            'teletravail' => $teletravail,
            'success' => true
        ], 200);
    }

    public function AnnulerTeletravail($id)
    {
        $teletravail = Teletravail::findOrFail($id);
        $teletravail->status = "Annuler";
        $teletravail->save();

        $this->result = "demande annuler";
        $this->user = User::findOrFail($teletravail->user_id);

        if($teletravail->level == 1){
            $this->list_responsable = CongeController::getLeader($teletravail['user_id']);
        }else if($teletravail->level == 2){
            $this->list_responsable = CongeController::getChefDepartement($teletravail['user_id']);
        }else if ($teletravail->level == 3){
            $this->list_responsable = CongeController::getAllGerants();
        }

        if(count($this->list_responsable) != 0){
            Mail::send('teletravail.AnnulerTeletravail', ['teletravail' => $teletravail, 'user' =>  $this->user], function($message) {
                foreach ($this->list_responsable as $resp) {
                    $message->to($resp['email']);
                }
                $message->subject('Response following the cancellation of teleworking');
            });
        }
        return response()->json([
            'teletravail' => $teletravail,
            'success' => true
        ], 200);
    }

    public function RejetDefinitive(Request $request,$id_teletravail)
    {
        $id_respons = Auth::user()['id'];
        
        $teletravail = Teletravail::findOrFail($id_teletravail);
        $teletravail->status = "Rejet définitif";
        $teletravail->save();

        $teletravail_history = new HistoryRemoteWork();
        $teletravail_history->id_responsable = $id_respons;
        $teletravail_history->status = "Rejet définitif";
        $teletravail_history->is_archive = 0;
        $teletravail_history->is_rejected_prov = 0;
        $teletravail_history->raison_reject = $request->raison_reject;
        $teletravail_history->level = $teletravail->level;
        $teletravail_history->teletravail_id = $id_teletravail;
        $teletravail_history->save();

        $this->user = CongeController::getUser($teletravail['user_id']);
        $this->result = "Rejet définitive";

        Mail::send('teletravail.RejetDefinitive', ['result' => $teletravail_history->raison_reject, 'teletravail'=> $teletravail, 'user' =>  $this->user[0]], function($message) {
            $message->to($this->user[0]['email']);
            $message->subject('Refusal of your remote work request');
        });

        return response()->json([
            'teletravail' => $teletravail,
            'success' => true
        ], 200);
    }

    public function RejetProvisoire(Request $request,$id_teletravail)
    {
        $id_respons = Auth::user()['id'];
        $teletravail = Teletravail::findOrFail($id_teletravail);
        $teletravail->status = "Rejet provisoire";
        $teletravail->save();

        $teletravail_history = new HistoryRemoteWork();
        $teletravail_history->id_responsable = $id_respons;
        $teletravail_history->status = "Rejet provisoire";
        $teletravail_history->is_rejected_prov = 0;
        $teletravail_history->is_archive = 0;
        $teletravail_history->raison_reject = $request->raison_reject;
        $teletravail_history->level = $teletravail->level;
        $teletravail_history->teletravail_id = $id_teletravail;
        $teletravail_history->save();

        $this->user = User::findOrFail($teletravail->user_id);

        // $teletravails_history = HistoryRemoteWork::where('teletravail_id','=',$id_teletravail)->update(['is_rejected_prov' =>1]);
        $this->result = "Rejet provisoire";

        Mail::send('teletravail.RejetProvisoire', ['result' => $teletravail_history->raison_reject  ,'teletravail'=> $teletravail, 'user' =>  $this->user], function($message) {
            $message->to($this->user['email']);
            $message->subject('Provisional rejection of your telework request');
        });
        return response()->json([
            'teletravail' => $teletravail,
            'teletravail_history' => $teletravail_history,
            'success' => true
        ], 200);
    }

    public function getTeletravailUser($id)
    {    
        $nb_leaders = TeletravailController::get_ids_leaders($id);
        $nb_chef = TeletravailController::get_ids_chef_dep($id);
        $nb_gerants = TeletravailController::get_ids_gerants($id);

        $responsable_list = array_values(array_unique(array_merge($nb_leaders, $nb_chef, $nb_gerants)));
        $responsable = [];

        foreach ($responsable_list as $resp) {
           if($resp != $id){
                array_push($responsable,$resp);
           }
        }
        
        $teletravails = Teletravail::where([['is_deleted', '=', 0],['user_id', '=', $id],['status', '!=', "Rejet définitif"],['status', '!=', "Annuler"],['status', '!=', "Accepter"]])->with([
            'histories' => fn($query) => $query->where([['id_responsable', '!=', $id]]),
            ])->get();

            // return $teletravails->sortBy('histories.created_at')->values()->all();;

            foreach ($teletravails as $tel) {
                if(count($tel['histories']) != 0){
                    foreach ($tel['histories'] as $history) {
                        $resp = User::findOrFail($history['id_responsable']);
                        $history['fullName'] = $resp['lastName'] .' '. $resp['firstName'];
                    }
                }
            }

            foreach ($teletravails as $teletravail) {
                $rest = 0;
                $list = [];
                foreach ($teletravail['histories'] as $c) {
                    if($c['is_rejected_prov'] == 0){
                        if($c['status'] == 'Rejet provisoire'){
                            array_push($list,-1);
                        }else if($c['status'] == 'Rejet définitif'){
                            array_push($list,'x');
                        }else if($c['status'] == 'Accepter'){
                            array_push($list,$c['id_responsable']);
                        }
                    }
                }
           
                $list = array_values(array_unique($list));
                $rest = count($responsable) - count($list);
    
                $teletravail['rest'] = $rest;
                $teletravail['nb_responsable'] = count($responsable);
    
                $teletravail['nb_acceptation'] = $list;
            }
        return $teletravails;
    }

    //Historiques de mes demandes 
    //Historiques de chaque demande 
    public function getTeletravailUserHistories($id)
    {    
        $nb_leaders = TeletravailController::get_ids_leaders($id);
        $nb_chef = TeletravailController::get_ids_chef_dep($id);
        $nb_gerants = TeletravailController::get_ids_gerants($id);

        $responsable_list = array_values(array_unique(array_merge($nb_leaders, $nb_chef, $nb_gerants)));
        $responsable = [];

        foreach ($responsable_list as $resp) {
           if($resp != $id){
                array_push($responsable,$resp);
           }
        }
        $teletravails = Teletravail::where([['is_deleted', '=', 0],['user_id', '=', $id],['status', '=', "Rejet définitif"]])
                                 ->orwhere([['is_deleted', '=', 0],['user_id', '=', $id],['status', '=', "Accepter"]])
                                 ->orwhere([['is_deleted', '=', 0],['user_id', '=', $id],['status', '=', "Annuler"]])
                                    ->with(['histories' => fn($query) => $query->where([['id_responsable', '!=', $id]]),
                         ])->get();

            foreach ($teletravails as $tel) {
                if(count($tel['histories']) != 0){
                    foreach ($tel['histories'] as $history) {
                        $resp = User::findOrFail($history['id_responsable']);
                        $history['fullName'] = $resp['lastName'] .' '. $resp['firstName'];
                    }
                }
            }

            foreach ($teletravails as $teletravail) {
                $rest = 0;
                $list = [];
                foreach ($teletravail['histories'] as $c) {
                    if($c['is_rejected_prov'] == 0){
                        if($c['status'] == 'Rejet provisoire'){
                            array_push($list,-1);
                        }else if($c['status'] == 'Rejet définitif'){
                            array_push($list,'x');
                        }else if($c['status'] == 'Accepter'){
                            array_push($list,$c['id_responsable']);
                        }
                    }
                }
           
                $list = array_values(array_unique($list));
                $rest = count($responsable) - count($list);
    
                $teletravail['rest'] = $rest;
                $teletravail['nb_responsable'] = count($responsable);
    
                $teletravail['nb_acceptation'] = $list;
            }
        return $teletravails;
    }

    
    public function getAllTeletravailLeaderHistories($id_auth)
    {
        // TeletravailControlle::getNbLeaders()
        $List_teletravails = [];
        // afficher list pour le responsable !!!!
        $List_team = TeamUser::where([['user_id','=',$id_auth],['is_deleted','=',0],['is_leader','=',1]])->get();
        $team_id = [];
        // return id team eli appartient liha el id_auth kenou leader ala equipe !!!
        if(count($List_team) != 0){
            foreach ($List_team as $team) {
                array_push($team_id,$team['team_id']);
            }
        }else{
            $team_id =  null;
        }

        $user = Auth::user();
        $teletravails = Teletravail::where([['is_deleted', '=', 0],['status','!=','Envoyé'],['user_id', '!=', $id_auth]])->with([
            'histories' => fn($query) => $query->where([['is_rejected_prov', '=', 0]]),
            'user'=> fn($query) => $query->where('is_deleted', '=', 0)->with([
                'teamUser'=> ([
                    'team'=> fn($query) => $query->where([['is_deleted', '=', 0],['status','=','active']])->with([
                        'department'=> fn($query) => $query->where([['is_deleted', '=', 0],['status','=','active']])->pluck('id')
                        ])
                ])
            ])
        ])->get();
        
       $user_team = [];
        foreach ($teletravails as $teletravail) {
            foreach ($teletravail['user']['teamUser'] as $team) {
                if($team_id != null){
                    if(in_array($team['team_id'],$team_id)){
                        array_push($List_teletravails,$teletravail);
                        $user_team = array_values(array_unique($List_teletravails));
                    }
                }
            }
        }
        // return $List_teletravails;
        $rep_responsable = false;
        $List_teletravails_final = [];
        foreach ($user_team as $teletravail) {
            if(count($teletravail['histories']) == 0){
                array_push($List_teletravails_final,$teletravail);
            }else{
                foreach ($teletravail['histories'] as $history) {
                   
                        array_push($List_teletravails_final,$teletravail);
                    
                }
            }

            // echo $teletravail;
        }
        return $result = array_values(array_unique($List_teletravails_final));
    }

    public function getAllTeletravailChefDepartmentHistories($id_auth)
    {
        $user = Auth::user();
        $List_teletravails = [];
        $List_department = Department::where([['chef_dep','=',$id_auth],['is_deleted','=',0]])->get();
        $List_id_department = [];

        // return id team eli appartient liha el id_auth kenou leader ala equipe !!!
        if(count($List_department) != 0){
            foreach ($List_department as $dep) {
                array_push($List_id_department,$dep['id']);
            }
        }else{
            $List_id_department =  null;
        }  

        $teletravails = Teletravail::where([['is_deleted', '=', 0],['status','!=','Envoyé'],['user_id', '!=', $id_auth]])->with([
            'histories' => fn($query) => $query->where([['is_rejected_prov', '=', 0],['level', '=', 2]]),
            'user'=> fn($query) => $query->where('is_deleted', '=', 0)->with([
                'teamUser'=> ([
                    'team'=> fn($query) => $query->where([['is_deleted', '=', 0],['status','=','active']])->with([
                        'department'=> fn($query) => $query->where([['is_deleted', '=', 0],['status','=','active']])->pluck('id')
                        ])
                ])
            ])
            ])->get();

            return 

            $user_dep = [];

            foreach ($teletravails as $teletravail) {
                foreach ($teletravail['user']['teamUser'] as $team) {
                    if($team['team'] != null && $List_id_department != [] ){
                        if(in_array($team['team']['department']['id'],$List_id_department)){
                            array_push($List_teletravails,$teletravail);
                            $user_dep = array_values(array_unique($List_teletravails));
                        }
                    }
                }
            }
        $rep_responsable = false;
        $List_teletravails_final = [];

        // foreach($user_dep as $teletravail) {
        //     if(count($teletravail['histories']) == 0){
        //         array_push($List_teletravails_final,$teletravail);
        //     }else{
        //         foreach ($teletravail['histories'] as $history) {
        //             if($history['id_responsable'] == $id_auth){
        //                     $rep_responsable = true;
        //             }else{
        //                 array_push($List_teletravails_final,$teletravail);
        //             }
        //         }
        //     }
        // }
        
        foreach($teletravails as $teletravail) {
            if(count($teletravail['histories']) == 0){
                }else{
                    foreach ($teletravail['histories'] as $history) {
                        if($history['id_responsable'] == $id_auth){
                            array_push($List_teletravails_final,$teletravail);
                        }
                    }
                }
            }
        return $result = array_values(array_unique($List_teletravails_final));
    }

    public function getAllTeletravailGerantHistories($id_auth)
    { 
        $user = Auth::user();
        // afficher list pour le gerant !!!!
            $result_gerants = Teletravail::where([['is_deleted', '=', 0],['status','!=','Envoyé'],['user_id', '!=', $id_auth]])->with([
                'histories' => fn($query) => $query->where([['is_rejected_prov', '=', 0]]),
                'user'=> fn($query) => $query->where('is_deleted', '=', 0)->with([
                    'teamUser'=> ([
                        'team'=> fn($query) => $query->where([['is_deleted', '=', 0],['status','=','active']])->with([
                            'department'=> fn($query) => $query->where([['is_deleted', '=', 0],['status','=','active']])->pluck('id')
                            ])
                    ])
                ])
            ])->get();


        $rep_responsable = false;
        $List_teletravails_final = [];

        foreach($result_gerants as $teletravail) {
           
            if(count($teletravail['histories']) == 0){
                }else{
                    foreach ($teletravail['histories'] as $history) {
                        if($history['id_responsable'] == $id_auth){
                            array_push($List_teletravails_final,$teletravail);
                        }
                    }
                }

            // if(count($teletravail['histories']) == 0){
            //     array_push($List_teletravails_final,$teletravail);
            // }else{
            //     foreach ($teletravail['histories'] as $history) {
            //         if($history['id_responsable'] == $id_auth){
            //                 $rep_responsable = true;
            //         }else{
            //             array_push($List_teletravails_final,$teletravail);
            //         }
            //     }
            // }
            }
        return $result = array_values(array_unique($List_teletravails_final));
    }

    public function getAllTeletravilsHistories($id_auth)
    {
        $result = [];
        // ken user auth = leader !!
        $test_fonction = CongeController::test_Leader_ChefDep_Gerant($id_auth);

        if($test_fonction['leader'] == 1){
           $result = TeletravailController::getAllTeletravailLeaderHistories($id_auth);
        }
        if($test_fonction['chef_dep'] == 1){
            $result = TeletravailController::getAllTeletravailChefDepartmentHistories($id_auth);
        }
        if($test_fonction['gerant'] == 1){
            $result = TeletravailController::getAllTeletravailGerantHistories($id_auth);
        }

        if($test_fonction['leader'] == 1 && $test_fonction['chef_dep'] == 1){
            $result_1 = TeletravailController::getAllTeletravailLeaderHistories($id_auth);
            $result_2 = TeletravailController::getAllTeletravailChefDepartmentHistories($id_auth);
            $result = array_merge($result_1, $result_2);
         }

         if($test_fonction['gerant'] == 1 && $test_fonction['chef_dep'] == 1){
            $result_1 = TeletravailController::getAllTeletravailGerantHistories($id_auth);
            $result_2 = TeletravailController::getAllTeletravailChefDepartmentHistories($id_auth);
            $result = array_merge($result_1, $result_2);
         }

         if($test_fonction['leader'] == 1 && $test_fonction['gerant'] == 1){
            $result_1 = TeletravailController::getAllTeletravailLeaderHistories($id_auth);
            $result_2 = TeletravailController::getAllTeletravailGerantHistories($id_auth);
            $result = array_merge($result_1, $result_2);
         }

         if($test_fonction['leader'] == 1 && $test_fonction['gerant'] == 1 && $test_fonction['chef_dep'] == 1){
            $result_1 = TeletravailController::getAllTeletravailLeaderHistories($id_auth);
            $result_2 = TeletravailController::getAllTeletravailGerantHistories($id_auth);
            $result_3 = TeletravailController::getAllTeletravailChefDepartmentHistories($id_auth);
            $result = array_merge($result_1, $result_2, $result_3);
         }

         foreach ($result as $tel) {
            if(count($tel['histories']) != 0){
                foreach ($tel['histories'] as $history) {
                    $resp = User::findOrFail($history['id_responsable']);
                    $history['fullName'] = $resp['lastName'] .' '. $resp['firstName'];
                }
            }
        }


        return response()->json([
            'teletravails' => array_values(array_unique($result)),
            'success' => true
        ], 200);
    }


}
