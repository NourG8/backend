<?php

namespace App\Http\Controllers;

use App\Models\Conge;
use App\Models\User;
use DB;
use App\Models\Team;
use App\Models\TeamUser;
use App\Models\Department;
use App\Models\Position;
use App\Models\CongeHistory;

use App\Mail\DailyMail;

use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreCongeRequest;
use App\Http\Requests\UpdateCongeRequest;

use Illuminate\Http\Request;

class CongeController extends Controller
{
    private $user = null;
    private $conge = null;

    private $leader = null;
    private $chef_dep = [];
    private $gerants = [];

    private $date_actuelle = null;
    private $result = null;
    private $list_responsable = [];

    public static function getUser($id_user)
    {
        $user = User::findOrFail($id_user);
        $sex = null;
        $now = Carbon::now();
        $date_actuelle = $now->year;

        //verificaation sex
        if($user['sex'] == 'Women'){
            if($user['FamilySituation'] == 'Single'){
                $sex = "Mlle";
            }else{
                $sex = "Mme";
            }
        }else if($user['sex'] == 'Man'){
            $sex = "Mr";
        }
        return [$user,$sex,$date_actuelle];
    }

    public static function getLeader($id_user)
    {
        $teams = TeamUser::where([['user_id','=',$id_user],['is_deleted','=',0]])->get();
        $team_leaders = [];

        if(count($teams) != 0){
            foreach ($teams as $team) {
                $team_leader = TeamUser::where([['team_id','=',$team['team_id']],['is_leader','=',1],['is_deleted','=',0]])->with([
                        'user'=>
                        fn($query) => $query->where([['is_deleted', '=', 0]])
                    ])->get();

                array_push($team_leaders,$team_leader[0]['user']);
            }
        }
        return array_values(array_unique($team_leaders));
    }

    public static function getChefDepartement($id_user)
    {
        //get cehf departement selon id utilisateur !!!
        $department = User::where([['is_deleted','=',0],['status','=','active'],['id','=',$id_user]])->with([
            'teamUser'=>
            fn($query) => $query->where([['is_deleted', '=', 0]])->with([
                'team'=>
                fn($query) => $query->where([['is_deleted','=',0],['status','=','active']])->with([
                    'department'=>
                    fn($query) => $query->where([['is_deleted','=',0],['status','=','active']])
                    ])
                ])])->get();

        $chef_department=[];
        if(count($department[0]['teamUser']) != 0){
            foreach ($department[0]['teamUser'] as $dep) {
                 $chef_department_id = $dep['team']['department']['chef_dep'];
                 $chef_dep = User::findOrFail($chef_department_id);
                array_push($chef_department,$chef_dep);
            }
        }
        return array_values(array_unique($chef_department));
    }

    public static function getAllGerants()
    {
        //get all gerant in societe !!!
        $gerant_users = Position::where([['is_deleted','=',0],['status','=','active'],['jobName','=','Gerant']])->with([
                    'users' =>([
                        'user' =>
                         fn($query) => $query->where([['is_deleted','=',0],['status','=','active']])
                        ])
        ])->get();
        $List_gerants = [];
        foreach ($gerant_users[0]['users'] as $users) {
            array_push($List_gerants,$users['user']);
        }

        return array_values(array_unique($List_gerants));
    }

    public function getCongeUser($id)
    {
        $nb_leaders = CongeController::get_ids_leaders($id);
        $nb_chef = CongeController::get_ids_chef_dep($id);
        $nb_gerants = CongeController::get_ids_gerants();

        $responsable_list = array_values(array_unique(array_merge($nb_leaders, $nb_chef, $nb_gerants)));

        $responsable = [];

        foreach ($responsable_list as $resp) {
           if($resp != $id){
                array_push($responsable,$resp);
           }
        }

        $conges = Conge::getAllCongeUser($id);

        foreach ($conges as $conge) {
            $rest = 0;
            $list = [];
            foreach ($conge['histories'] as $c) {
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

            $conge['rest'] = $rest;
            $conge['nb_responsable'] = count($responsable);

            $conge['nb_acceptation'] = $list;
        }

        return $conges;
    }

    public function getHistoriqueCongeUser($id_user)
    {
        $conges = Conge::where([['is_deleted', '=', 0],['user_id', '=', $id_user],['status', '!=', 'Envoyé'],['status', '!=', 'En cours'],['status', '!=', 'Rejet provisoire']])->with([
            'histories' => fn($query) => $query->where([['id_responsable', '!=', $id_user]]),
            'user'
        ])->get();

        foreach ($conges as $conge) {
            if(count($conge['histories']) != 0){
                foreach ($conge['histories'] as $history) {
                    $responsable = User::findOrFail($history['id_responsable']);
                    $date = Carbon::parse($history->created_at)->format('d M Y');
                    $history['date'] = $date;
                    $history['fullName'] = $responsable['lastName'] .' '. $responsable['firstName'];
                }
            }

        }
        return $conges;
    }

    public static function test_Leader_ChefDep_Gerant($id_user)
    {
        //retourner l'utilisateur eli authentifier est ce que howa leader wela chef dep wela gerant
        $leaders = CongeController::getLeader($id_user);
        $test_leader = 0;
        foreach ($leaders as $leader) {
            if($leader['id'] == $id_user){
                $test_leader = 1;
            }
        }

        $chef_dep = Department::where('chef_dep','=',$id_user)->get();
        $test_chefDep = 0;
             if(count($chef_dep) != 0){
                $test_chefDep = 1;
            }

        $gerants = CongeController::getAllGerants();
        $test_gerant = 0;
        foreach ($gerants as $gerant) {
            if($gerant['id'] == $id_user){
                $test_gerant = 1;

            }
        }
        return ["leader" => $test_leader,"chef_dep" =>$test_chefDep,"gerant" =>$test_gerant];
    }

    public function getAllCongeLeader($id_auth)
    {
        $List_conges = [];
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

        $conges = Conge::where([['is_deleted', '=', 0],['level','=','1']])->with([
            'histories' => fn($query) => $query->where([['is_rejected_prov', '=', 0],['level', '=', 1],['id_responsable', '=', $id_auth]]),
            'user'=> fn($query) => $query->where('is_deleted', '=', 0)->with('teamUser')
        ])->get();

       $user_team = [];
        foreach ($conges as $conge) {
            foreach ($conge['user']['teamUser'] as $team) {
                if($team_id != null){
                    if(in_array($team['team_id'],$team_id)){
                        array_push($List_conges,$conge);
                        $user_team = array_values(array_unique($List_conges));
                    }
                }
            }
        }
        $rep_responsable = false;
        $List_conges_final = [];
        foreach ($user_team as $conge) {
            if(count($conge['histories']) == 0){
                array_push($List_conges_final,$conge);
            }else{
                foreach ($conge['histories'] as $history) {
                    if($history['id_responsable'] == $id_auth){
                            $rep_responsable = true;
                    }else{
                        array_push($List_conges_final,$conge);
                    }
                }
            }
        }
        return array_values(array_unique($List_conges_final));
    }

    public function getAllCongeChefDepartment($id_auth)
    {
        $List_conges = [];
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

        $conges = Conge::where([['is_deleted', '=', 0],['level','=',2]])->with([
            'histories' => fn($query) => $query->where([['is_rejected_prov', '=', 0],['level', '=', 2],['id_responsable', '=', $id_auth]]),
            'user'=> fn($query) => $query->where('is_deleted', '=', 0)->with([
                'teamUser'=> ([
                    'team'=> fn($query) => $query->where([['is_deleted', '=', 0],['status','=','active']])->with([
                        'department'=> fn($query) => $query->where([['is_deleted', '=', 0],['status','=','active']])->pluck('id')
                        ])
                ])
            ])
            ])->get();

            $user_dep = [];

            foreach ($conges as $conge) {
                foreach ($conge['user']['teamUser'] as $team) {
                    if($team['team'] != null && $List_id_department != [] ){
                        if(in_array($team['team']['department']['id'],$List_id_department)){
                            array_push($List_conges,$conge);
                            $user_dep = array_values(array_unique($List_conges));
                        }
                    }
                }
            }
        $rep_responsable = false;

        $List_conges_final = [];

        foreach($user_dep as $conge) {
            if(count($conge['histories']) == 0){
                array_push($List_conges_final,$conge);
            }else{
                foreach ($conge['histories'] as $history) {
                    if($history['id_responsable'] == $id_auth){
                            $rep_responsable = true;
                    }else{
                        array_push($List_conges_final,$conge);
                    }
                }
            }
        }
        return array_values(array_unique($List_conges_final));
    }

    public function getAllCongeGerant($id_auth)
    {
        // afficher list pour le gerant !!!!
        $result_gerants = Conge::where([['is_deleted', '=', 0],['level','=',3]])->with([
            'histories' => fn($query) => $query->where([['is_rejected_prov', '=', 0],['level', '=', 3],['id_responsable', '=', $id_auth]]),
            'user'=> fn($query) => $query->where('is_deleted', '=', 0)
        ])->get();

        $rep_responsable = false;

        $List_conges_final = [];

        foreach($result_gerants as $conge) {
            if(count($conge['histories']) == 0){
                array_push($List_conges_final,$conge);
            }else{
                foreach ($conge['histories'] as $history) {
                    if($history['id_responsable'] == $id_auth){
                            $rep_responsable = true;
                    }else{
                        array_push($List_conges_final,$conge);
                    }
                }
            }
        }
        return $result = array_values(array_unique($List_conges_final));
    }

    public function getAllConge($id_auth)
    {
        $result = [];
        // ken user auth = leader !!
        $test_fonction = CongeController::test_Leader_ChefDep_Gerant($id_auth);

        if($test_fonction['leader'] == 1 && $test_fonction['chef_dep'] == 0 && $test_fonction['gerant'] == 0){
           $result = CongeController::getAllCongeLeader($id_auth);
        }
        if($test_fonction['leader'] == 0 && $test_fonction['chef_dep'] == 1 && $test_fonction['gerant'] == 0){
            $result = CongeController::getAllCongeChefDepartment($id_auth);
        }
        if($test_fonction['leader'] == 0 && $test_fonction['chef_dep'] == 0 && $test_fonction['gerant'] == 1){
            $result = CongeController::getAllCongeGerant($id_auth);
        }

        if($test_fonction['leader'] == 1 && $test_fonction['chef_dep'] == 1 && $test_fonction['gerant'] == 0){
            $result_1 = CongeController::getAllCongeLeader($id_auth);
            $result_2 = CongeController::getAllCongeChefDepartment($id_auth);
            $result = array_merge($result_1, $result_2);
        }

         if($test_fonction['leader'] == 0 && $test_fonction['chef_dep'] == 1 && $test_fonction['gerant'] == 1){
            $result_1 = CongeController::getAllCongeGerant($id_auth);
            $result_2 = CongeController::getAllCongeChefDepartment($id_auth);
            $result = array_merge($result_1, $result_2);
         }

         if($test_fonction['leader'] == 1 && $test_fonction['chef_dep'] == 0 && $test_fonction['gerant'] == 1){
            $result_1 = CongeController::getAllCongeLeader($id_auth);
            $result_2 = CongeController::getAllCongeGerant($id_auth);
            $result = array_merge($result_1, $result_2);
         }

         if($test_fonction['leader'] == 1 && $test_fonction['gerant'] == 1 && $test_fonction['chef_dep'] == 1){
            $result_1 = CongeController::getAllCongeLeader($id_auth);
            $result_2 = CongeController::getAllCongeGerant($id_auth);
            $result_3 = CongeController::getAllCongeChefDepartment($id_auth);
            $result = array_merge($result_1, $result_2, $result_3);
         }


        return response()->json([
            'conge' => $result,
            'success' => true
        ], 200);
    }

    public function getHistoriqueCongeLeader($id_auth)
    {
        $List_conges = [];
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

        $conges = Conge::where([['is_deleted', '=', 0],['status','!=','Envoyé'],['user_id','!=',$id_auth]])->with([
            'histories',
            'user'=> fn($query) => $query->where('is_deleted', '=', 0)->with([
                'teamUser'=> ([
                    'team'=> fn($query) => $query->where([['is_deleted', '=', 0],['status','=','active']])->with([
                        'department'=> fn($query) => $query->where([['is_deleted', '=', 0],['status','=','active']])->pluck('id')
                        ])
                ])
            ])
        ])->get();

       $user_team = [];

        foreach ($conges as $conge) {

            foreach ($conge['user']['teamUser'] as $team) {
                if($team_id != null){
                    if(in_array($team['team_id'],$team_id)){
                        array_push($List_conges,$conge);
                        $user_team = array_values(array_unique($List_conges));
                    }
                }
            }
        }

        $List_conges_final = [];

        foreach ($user_team as $conge) {
            if(count($conge['histories']) != 0){
                foreach ($conge['histories'] as $history) {
                    $responsable = User::findOrFail($history['id_responsable']);
                    $history['fullName'] = $responsable['lastName'] .' '. $responsable['firstName'];
                    if($history['id_responsable'] == $id_auth){
                        array_push($List_conges_final,$conge);
                    }
                }
            }
        }

        return array_values(array_unique($List_conges_final));
    }

    public function getHistoriqueCongeChefDepartment($id_auth)
    {
        $List_conges = [];
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

        $conges = Conge::where([['is_deleted', '=', 0],['level','!=',1],['status','!=','Envoyé'],['user_id','!=',$id_auth]])->with([
            'histories',
            'user'=> fn($query) => $query->where('is_deleted', '=', 0)->with([
                'teamUser'=> ([
                    'team'=> fn($query) => $query->where([['is_deleted', '=', 0],['status','=','active']])->with([
                        'department'=> fn($query) => $query->where([['is_deleted', '=', 0],['status','=','active']])->pluck('id')
                        ])
                ])
            ])
          ])->get();

            $user_dep = [];

            foreach ($conges as $conge) {
                foreach ($conge['user']['teamUser'] as $team) {
                    if($team['team'] != null && $List_id_department != [] ){
                        if(in_array($team['team']['department']['id'],$List_id_department)){
                            array_push($List_conges,$conge);
                            $user_dep = array_values(array_unique($List_conges));
                        }
                    }
                }
            }

        $List_conges_final = [];
        $rep_responsable = false;

        foreach($user_dep as $conge) {
            if(count($conge['histories']) != 0){
                foreach ($conge['histories'] as $history) {
                        $responsable = User::findOrFail($history['id_responsable']);
                        $history['fullName'] = $responsable['lastName'] .' '. $responsable['firstName'];
                        if($history['id_responsable'] == $id_auth){
                            array_push($List_conges_final,$conge);
                        }
                }
            }
        }
        return array_values(array_unique($List_conges_final));
    }

    public function getHistoriqueCongeGerant($id_auth)
    {
        // afficher list pour le gerant !!!!
        $result_gerants = Conge::where([['is_deleted', '=', 0],['level','!=',1],['level','!=',2],['status','!=','Envoyé'],['user_id','!=',$id_auth]])->orWhere([['is_deleted', '=', 0],['level','!=',2],['status','!=','Envoyé'],['user_id','!=',$id_auth]])->with([
            'histories',
            'user'=> fn($query) => $query->where('is_deleted', '=', 0)->with([
                'teamUser'=> ([
                    'team'=> fn($query) => $query->where([['is_deleted', '=', 0],['status','=','active']])->with([
                        'department'=> fn($query) => $query->where([['is_deleted', '=', 0],['status','=','active']])->pluck('id')
                        ])
                ])
            ])
        ])->get();

        $List_conges_final = [];

        foreach($result_gerants as $conge) {
            if(count($conge['histories']) != 0){
                foreach ($conge['histories'] as $history) {
                        $responsable = User::findOrFail($history['id_responsable']);
                        $history['fullName'] = $responsable['lastName'] .' '. $responsable['firstName'];
                    if($history['id_responsable'] == $id_auth){
                        array_push($List_conges_final,$conge);
                    }
                }
            }
        }
        return array_values(array_unique($List_conges_final));
    }

    public function getHistoriqueConge($id_auth)
    {
        $result = [];
        // ken user auth = leader !!
        $test_fonction = CongeController::test_Leader_ChefDep_Gerant($id_auth);

        if($test_fonction['leader'] == 1 && $test_fonction['chef_dep'] == 0 && $test_fonction['gerant'] == 0){
           $result = CongeController::getHistoriqueCongeLeader($id_auth);
        }
        if($test_fonction['leader'] == 0 && $test_fonction['chef_dep'] == 1 && $test_fonction['gerant'] == 0){
            $result = CongeController::getHistoriqueCongeChefDepartment($id_auth);
        }
        if($test_fonction['leader'] == 0 && $test_fonction['chef_dep'] == 0 && $test_fonction['gerant'] == 1){
            $result = CongeController::getHistoriqueCongeGerant($id_auth);
        }
        if($test_fonction['leader'] == 1 && $test_fonction['chef_dep'] == 1 && $test_fonction['gerant'] == 0){
            $result_1 = CongeController::getHistoriqueCongeLeader($id_auth);
            $result_2 = CongeController::getHistoriqueCongeChefDepartment($id_auth);
            $result = array_values(array_unique(array_merge($result_1, $result_2)));
        }
         if($test_fonction['leader'] == 0 && $test_fonction['chef_dep'] == 1 && $test_fonction['gerant'] == 1){
             $result_1 = CongeController::getHistoriqueCongeGerant($id_auth);
             $result_2 = CongeController::getHistoriqueCongeChefDepartment($id_auth);
             $result = array_values(array_unique(array_merge($result_1, $result_2)));

         }
         if($test_fonction['leader'] == 1 && $test_fonction['chef_dep'] == 0 && $test_fonction['gerant'] == 1){
            $result_1 = CongeController::getHistoriqueCongeLeader($id_auth);
            $result_2 = CongeController::getHistoriqueCongeGerant($id_auth);
            $result = array_values(array_unique(array_merge($result_1, $result_2)));
         }
         if($test_fonction['leader'] == 1 && $test_fonction['gerant'] == 1 && $test_fonction['chef_dep'] == 1){
            $result_1 = CongeController::getHistoriqueCongeLeader($id_auth);
            $result_2 = CongeController::getHistoriqueCongeGerant($id_auth);
            $result_3 = CongeController::getHistoriqueCongeChefDepartment($id_auth);
            $result = array_values(array_unique(array_merge($result_1, $result_2,$result_3)));
         }

        return response()->json([
            'conge' => $result,
            'success' => true
        ], 200);
    }

    public static function get_ids_leaders($id)
    {
        $leader = array_values(array_unique(CongeController::getLeader($id)));
        $id_leaders = [];
        foreach ($leader as $l) {
            array_push($id_leaders,$l['id']);
        }
        return $id_leaders;
    }

    public static function get_ids_chef_dep($id)
    {
        $chef_dep = array_values(array_unique(CongeController::getChefDepartement($id)));
        $id_chef_dep = [];
        foreach ($chef_dep as $c) {
            array_push($id_chef_dep,$c['id']);
        }
        return $id_chef_dep;
    }

    public static function get_ids_gerants()
    {
        $gerant = array_values(array_unique(CongeController::getAllGerants()));
        $id_gerants = [];
        foreach ($gerant as $g) {
            array_push($id_gerants,$g['id']);
        }
        return $id_gerants;
    }

    public function ResponsableAddConge($id)
    {
        $this->user = CongeController::getUser($id);

        $this->leader = CongeController::getLeader($id);

        $this->gerants = CongeController::getAllGerants($id);

        $this->chef_dep = array_values(array_unique(CongeController::getChefDepartement($id)));

        if(count($this->chef_dep) == 0){
            $ids_gerants = CongeController::get_ids_gerants();
            if(in_array($id,$ids_gerants)){
                $conge_history = new CongeHistory();
                $conge_history->id_responsable = $id;
                $conge_history->status = "Accepter";
                $conge_history->is_rejected_prov = 0;
                $conge_history->is_archive = 0;
                $conge_history->level = 3;
                $conge_history->conge_id = $this->conge['id'];
                $conge_history->save();

                $gerants = $this->gerants;
                $this->gerants = [];
                foreach($gerants as $g) {
                    if($g['id'] != $id){
                      array_push($this->gerants,$g);
                    }
                }
            }

                $conge = Conge::findOrFail($this->conge['id']);

                if(count($this->gerants) == 0){
                    $conge->status = "Accepter";
                    $conge->level = 3;
                    $conge->save();
                }else{
                    $conge->level = 3;
                    $conge->save();
                    Mail::send('conge.InfoEmail', ['user' => $this->user], function($message) {
                        foreach ($this->gerants as $gerant) {
                            $message->to($gerant['email']);
                        }
                        $message->subject('Leave request');
                    });
                }
                $this->conge = $conge;
            }else{
                $ids_leaders = CongeController::get_ids_leaders($id);

                if(in_array($id,$ids_leaders)){
                    $conge_history = new CongeHistory();
                    $conge_history->id_responsable = $id;
                    $conge_history->status = "Accepter";
                    $conge_history->is_rejected_prov = 0;
                    $conge_history->is_archive = 0;
                    $conge_history->level = 1;
                    $conge_history->conge_id = $this->conge['id'];
                    $conge_history->save();

                    $leader = $this->leader;
                    $this->leader = [];
                    foreach ($leader as $l) {
                        if($l['id'] != $id){
                        array_push($this->leader,$l);
                        }
                    }
                }

                if(count($this->leader) == 0){
                    $conge = Conge::findOrFail($this->conge['id']);
                    $conge->level = 2;
                    $conge->save();

                    Mail::send('conge.InfoEmail', ['user' => $this->user], function($message) {
                        foreach ($this->chef_dep as $chef_dep) {
                           $message->to($chef_dep['email']);
                        }
                        $message->subject('Leave request');
                    });
                    $this->conge = $conge;
                }else{
                    Mail::send('conge.InfoEmail', ['user' => $this->user], function($message) {
                        foreach ($this->leader as $leader) {
                          $message->to($leader['email']);
                        }
                        $message->subject('Leave request');
                    });
                }
            }
    }

    public function AddConge(Request $request,$id)
    {
        $this->conge = new Conge();
        $this->conge->type = $request->input('type');
        $this->conge->raison = $request->input('raison');
        $this->conge->dates = $request->input('dates');
        $this->conge->level = 1;
        $this->conge->date = Carbon::now();
        $this->conge->is_deleted = 0;
        $this->conge->status = "Envoyé";
        $this->conge->user_id = $id;
        $this->conge->save();

        CongeController::ResponsableAddConge($id);

        return response()->json([
            'conge' => $this->conge,
            'success' => true
        ], 200);

    }

    public function updateConge(Request $request,$id)
    {
        $this->conge = Conge::findOrFail($id);

        $conges_history = CongeHistory::where('conge_id','=',$id)->update(['is_rejected_prov' =>1,'is_archive'=> 1]);

        $this->conge->type = $request->input('type');
        $this->conge->raison = $request->input('raison');
        $this->conge->dates = $request->input('dates');
        $this->conge->status = "Envoyé";
        $this->conge->is_deleted = 0;
        $this->conge->level = 1;
        $this->conge->save();

        CongeController::ResponsableAddConge($this->conge->user_id);

        return response()->json([
            'conge' => $this->conge,
            'success' => true
        ], 200);
    }

    public function deleteConge($id)
    {
        $conge = Conge::findOrFail($id);
        $conge->is_deleted = 1;
        $conge->save();
        return response()->json([
            'conge' => $conge,
            'success' => true
        ], 200);
    }

    public function AnnulerConge($id)
    {
        $conge = Conge::findOrFail($id);
        $conge->status = "Annuler";
        $conge->save();

        $this->result = "demande annuler";

        $this->user = User::findOrFail($conge->user_id);

        if($conge->level == 1){
            $this->list_responsable = CongeController::getLeader($conge['user_id']);
        }else if($conge->level == 2){
            $this->list_responsable = CongeController::getChefDepartement($conge['user_id']);
        }else if ($conge->level == 3){
            $this->list_responsable = CongeController::getAllGerants();
        }

        if(count($this->list_responsable) != 0){
            Mail::send('conge.AnnulerConge', ['conge' => $conge, 'user' =>  $this->user], function($message) {
                foreach ($this->list_responsable as $resp) {
                    $message->to($resp['email']);
                }
                $message->subject('Response following the cancellation of leaving');
            });
        }

        return response()->json([
            'conge' => $conge,
            'success' => true
        ], 200);
    }

    public function RejetDefinitive(Request $request,$id_conge)
    {
        $responsable = Auth::user();

        $conge = Conge::findOrFail($id_conge);
        $conge->status = "Rejet définitif";
        $conge->save();

        $conge_history = new CongeHistory();
        $conge_history->id_responsable = $responsable['id'];
        $conge_history->status = "Rejet définitif";
        $conge_history->is_rejected_prov = 0;
        $conge_history->is_archive = 0;
        $conge_history->raison_reject = $request->raison_reject;
        $conge_history->level = $conge->level;
        $conge_history->conge_id = $id_conge;
        $conge_history->save();

        $this->user = CongeController::getUser($conge['user_id']);

        $this->result = "Rejet définitive";

        Mail::send('conge.RejetDefinitive', ['result' => $conge_history->raison_reject, 'conge'=> $conge, 'user' =>  $this->user[0]], function($message) {
            $message->to($this->user[0]['email']);
            $message->subject('Request rejected');
        });

        return response()->json([
            'conge' => $conge,
            'success' => true
        ], 200);
    }

    public function RejetProvisoire(Request $request,$id_conge)
    {
        $responsable = Auth::user();
        $conge = Conge::findOrFail($id_conge);
        $conge->status = "Rejet provisoire";
        $conge->save();

        $conge_history = new CongeHistory();
        $conge_history->id_responsable = $responsable['id'];
        $conge_history->status = "Rejet provisoire";
        $conge_history->is_archive = 0;
        $conge_history->is_rejected_prov = 0;
        $conge_history->raison_reject = $request->raison_reject;
        $conge_history->level = $conge->level;
        $conge_history->conge_id = $id_conge;
        $conge_history->save();

        $this->user = User::findOrFail($conge->user_id);

        $this->result = "Rejet provisoire";

        Mail::send('conge.RejetProvisoire', ['result' => $conge_history->raison_reject, 'conge'=> $conge, 'user' =>  $this->user], function($message) {
            $message->to($this->user['email']);
            $message->subject('Provisionally refusal of your leave request');
        });

        return response()->json([
            'conge' => $conge,
            'success' => true
        ], 200);
    }

    public function acceptCongeLeader($id_conge)
    {
        $leader = Auth::user();

        $List_conges = [];

        $conge = Conge::findOrFail($id_conge);

        $ids_leaders = CongeController::get_ids_leaders($conge['user_id']);

        if(in_array($leader['id'],$ids_leaders)){
            $conge_history = new CongeHistory();
            $conge_history->id_responsable = $leader['id'];
            $conge_history->status = "Accepter";
            $conge_history->is_rejected_prov = 0;
            $conge_history->is_archive = 0;
            $conge_history->level = 1;
            $conge_history->conge_id = $id_conge;
            $conge_history->save();

            $conge->status="En cours";
            $conge->save();

            $allConges = Conge::where([['is_deleted', '=', 0],['status','=','Envoyé'],['level','=','1'],['id','=',$id_conge]])->orWhere([['is_deleted', '=', 0],['status','=','En cours'],['level','=','1'],['id','=',$id_conge]])->with([
                'histories' => fn($query) => $query->where([['is_rejected_prov', '=', 0],['level', '=', 1],['status','=','Accepter'],['conge_id', '=', $id_conge]]),
            ])->get();

            if(count($allConges) != 0){
                foreach($allConges as $conge) {
                    array_push($List_conges,$conge);
                }
            }

            $Leaders = CongeController::getLeader($conge['user_id']);
            $chef_dep = CongeController::getChefDepartement($conge['user_id']);
            $this->user = CongeController::getUser($conge['user_id']);

            $this->chef_dep = array_diff($chef_dep, $Leaders);

            if(count($List_conges) != 0){
                if(count($List_conges[0]['histories']) == count($Leaders) ){
                    $now = Carbon::now();
                    $conge = DB::table('conges')
                    ->select('conges.*')
                    ->where([
                        ['id', '=',$id_conge],
                        ['is_deleted', '=', 0],
                        ['level', '=', 1],
                    ])->update(['level' => 2,'date' => $now]);

                        if(count($this->chef_dep) != 0){
                            Mail::send('conge.InfoEmail', ['user' => $this->user], function($message) {
                                foreach ($this->chef_dep as $chef) {
                                    $message->to($chef['email']);
                                }
                                $message->subject('Leave request');
                            });
                        }
                }
            }
        }
        return $List_conges;
    }

    public function acceptCongeChefDep($id_conge)
    {
        $this->user = Auth::user();

        $conge = Conge::findOrFail($id_conge);

        $List_conges = [];

        $ids_chef_dep = CongeController::get_ids_chef_dep($conge['user_id']);

        if(in_array($this->user['id'],$ids_chef_dep)){
            $conge_history = new CongeHistory();
            $conge_history->id_responsable = $this->user['id'];
            $conge_history->status = "Accepter";
            $conge_history->is_rejected_prov = 0;
            $conge_history->is_archive = 0;
            $conge_history->level = 2;
            $conge_history->conge_id = $id_conge;
            $conge_history->save();

            $conge->status="En cours";
            $conge->save();

            $allConges = Conge::where([['is_deleted', '=', 0],['status','=','En cours'],['level','=','2'],['id','=',$id_conge]])->with([
                'histories' => fn($query) => $query->where([['is_rejected_prov', '=', 0],['level', '=', 2],['status','=','Accepter'],['conge_id', '=', $id_conge]]),
            ])->get();

            if(count($allConges) != 0){
                foreach($allConges as $conge) {
                    array_push($List_conges,$conge);
                }
            }
            $chef_dep = CongeController::getChefDepartement($conge['user_id']);
            $gerants = CongeController::getAllGerants();
            $this->user = CongeController::getUser($conge['user_id']);

            $this->gerants = array_diff($gerants, $chef_dep);

            if(count($List_conges) != 0){
                if(count($List_conges[0]['histories']) == count($chef_dep)){
                    $now = Carbon::now();
                    $conge = DB::table('conges')
                    ->select('conges.*')
                    ->where([
                        ['id', '=',$id_conge],
                        ['is_deleted', '=', 0],
                        ['level', '=', 2],
                    ])->update(['level' => 3,'date' => $now]);

                    if(count($this->gerants) != 0){
                        Mail::send('conge.InfoEmail', ['user' => $this->user], function($message) {
                            foreach ($this->gerants as $gerant) {
                                $message->to($gerant['email']);
                            }
                            $message->subject('Leave request');
                        });
                    }

                }
            }
        }

        return $List_conges;
    }

    public function acceptCongeGerant($id_conge)
    {
        $leader = Auth::user();

        $conge_history = new CongeHistory();
        $conge_history->id_responsable = $leader['id'];
        $conge_history->status = "Accepter";
        $conge_history->is_rejected_prov = 0;
        $conge_history->is_archive = 0;
        $conge_history->level = 3;
        $conge_history->conge_id = $id_conge;
        $conge_history->save();


        $conge = Conge::findOrFail($id_conge);
        $conge->status="En cours";
        $conge->save();

        $this->user = User::findOrFail($conge['user_id']);

        $List_conges = [];

        $allConges = Conge::where([['is_deleted', '=', 0],['status','=','En cours'],['level','=',3],['id','=',$id_conge]])->with([
            'histories' => fn($query) => $query->where([['is_rejected_prov', '=', 0],['level', '=', 3],['status','=','Accepter'],['conge_id', '=', $id_conge]]),
        ])->get();

        if(count($allConges) != 0){
            foreach($allConges as $conge) {
                array_push($List_conges,$conge);
            }
        }

        $gerants = CongeController::getAllGerants();

        if(count($List_conges) != 0){
            if(count($List_conges[0]['histories']) == count($gerants) ){
                $conge = DB::table('conges')
                ->select('conges.*')
                ->where([
                    ['id', '=',$id_conge],
                    ['is_deleted', '=', 0],
                    ['level', '=', 3],
                ])->update(['status' => "Accepter"]);

                $this->userPassConge = User::findOrFail($List_conges[0]['user_id']);
                $this->result = "Accepter";

                Mail::send('conge.Acceptation', ['user' => $this->userPassConge, 'dates' => $List_conges[0]['dates']], function($message) {
                    $message->to($this->user['email']);
                    $message->subject('Acceptance of your leave request');
                });
            }
        }

        return $List_conges;
    }

    public function accepterConge($id_conge)
    {
        $result = [];

        $this->user = Auth::user();
        $conge = Conge::findOrFail($id_conge);

        $test_fonction = CongeController::test_Leader_ChefDep_Gerant($this->user['id']);

        if($test_fonction['leader'] == 1 && $test_fonction['chef_dep'] == 0 && $test_fonction['gerant'] == 0){
           $result = CongeController::acceptCongeLeader($id_conge);
        }
        if($test_fonction['leader'] == 0 && $test_fonction['chef_dep'] == 1 && $test_fonction['gerant'] == 0){
            $result = CongeController::acceptCongeChefDep($id_conge);
        }
        if($test_fonction['leader'] == 0 && $test_fonction['chef_dep'] == 0 && $test_fonction['gerant'] == 1){
            $result = CongeController::acceptCongeGerant($id_conge);
        }
        if($test_fonction['leader'] == 1 && $test_fonction['chef_dep'] == 1 && $test_fonction['gerant'] == 0){
            $result_1 = CongeController::acceptCongeLeader($id_conge);
            $result_2 = CongeController::acceptCongeChefDep($id_conge);
            $result = array_merge($result_1, $result_2);
         }

         if($test_fonction['leader'] == 0 && $test_fonction['chef_dep'] == 1 && $test_fonction['gerant'] == 1 ){
                $result_1 = CongeController::acceptCongeChefDep($id_conge);
                $result_2 = CongeController::acceptCongeGerant($id_conge);
                $result = array_merge($result_1, $result_2);
         }

         if($test_fonction['leader'] == 1 && $test_fonction['chef_dep'] == 0 && $test_fonction['gerant'] == 1){
            $result_1 = CongeController::acceptCongeLeader($id_conge);
            $result_2 = CongeController::acceptCongeGerant($id_conge);
            $result = array_merge($result_1, $result_2);
         }

         if($test_fonction['leader'] == 1 && $test_fonction['chef_dep'] == 1 && $test_fonction['gerant'] == 1){
            $result_1 = CongeController::acceptCongeLeader($id_conge);
            $result_2 = CongeController::acceptCongeChefDep($id_conge);
            $result_3 = CongeController::acceptCongeGerant($id_conge);
            $result = array_merge($result_1, $result_2, $result_3);
         }

        return response()->json([
            'conge' => $result,
            'success' => true
        ], 200);
    }

    public static function SendMailDaily(){
        // get list conges !!
        $conges = Conge::where([['is_deleted', '=', 0],['status', '=', 'Envoyé']])->orWhere([['is_deleted', '=', 0],['status', '=', 'En cours']])->with([
            'histories' => fn($query) => $query->where([['is_rejected_prov', '=', 0],['is_archive', '=', 0]])
        ])->get();

        foreach ($conges as $conge) {
            $conge['List_responsable'] = [];
            $new_date = date('Y-m-d',strtotime('+1 day',strtotime($conge['date'])));
            $date_now = date('Y-m-d',strtotime(Carbon::now()));

            // tester si la date du demande envoyé depasse 24h !!
                if($new_date === $date_now){
                    $id_leaders = CongeController::get_ids_leaders($conge['user_id']);
                    $id_chef_dep = CongeController::get_ids_chef_dep($conge['user_id']);
                    $id_gerants = CongeController::get_ids_gerants();

                    $List_ids_responsable = [];

                    foreach($conge['histories'] as $history) {
                        array_push($List_ids_responsable,$history['id_responsable']);
                    }

                    // tester si un responsable ne repond pas a une demande de conge
                    if($conge['level'] == 1){
                        $list_responsables = array_diff($id_leaders, $List_ids_responsable);
                    }else if($conge['level'] == 2){
                        $list_responsables = array_diff($id_chef_dep, $List_ids_responsable);
                    }else{
                        $list_responsables = array_diff($id_gerants, $List_ids_responsable);
                    }

                    $List_responsable = [];

                    foreach ($list_responsables as $resp) {
                        $responsable = User::findOrFail($resp);
                        $user = CongeController::getUser($conge['user_id']);

                        $mailData = [
                            'lastName' => $user[0]['lastName'],
                            'firstName' => $user[0]['firstName'],
                            'sex' => $user[1],
                            'date' => $user[2],
                        ];
                        Mail::to($responsable->email)->send(new DailyMail($mailData));

                        array_push($List_responsable,$responsable);
                    }
                    $conge['List_responsable'] = $List_responsable;
              }
        }
    }

}
