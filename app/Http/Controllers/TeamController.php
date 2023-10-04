<?php

namespace App\Http\Controllers;
use DB;
use App\Models\Team;
use App\Models\TeamUser;
use Illuminate\Http\Request;

use Carbon\Carbon;


class TeamController extends Controller
{

    public function getAllTeams()
    {
        $teams = DB::table('teams')
        ->leftJoin('team_users', 'team_users.team_id', '=', 'teams.id')
        ->select('teams.*','team_users.user_id')
        ->where([
            ['teams.is_deleted','=',0],
            ['team_users.is_deleted','=',0],
            ['teams.status','=','active'],
            ['team_users.is_leader', '=', 1],
            ])
        ->get();

        return response()->json([
            'teams' => $teams,
            'success' => true
        ], 200);
    }

    public function getUsersInTeams($id)
    {
        $teams = Team::where([['is_deleted','=',0],['status','=','active'],['id','=',$id]])->with([
            'teamUser' =>
                fn($query) => $query->where([['is_deleted', '=', 0]])->with([
                    'user'])
        ])->get();

        return response()->json([
            'teams' => $teams,
            'success' => true
        ], 200);
    }

    public function getAllArchiveTeams()
    {
        $teams = Team::where([['is_deleted','=',0],['status','=','inactive']])->get();
        return response()->json([
            'teams' => $teams,
            'success' => true
        ], 200);
    }

    public function addTeams(Request $request)
    {
        $team = new Team();

        
        $team->name = $request->input('name');
        $team->department_id = $request->input('department_id');
        $team->description = $request->input('description');
        $team->status = 'active';
        $team->is_deleted = 0;
        $team->save();

        $team_user = new TeamUser();
        $team_user->team_id = $team->id;
        $team_user->user_id = $request->input('user_id');
        $date_now = Carbon::now()->toDateTimeString();
        $team_user->integration_date = $date_now;
        $team_user->is_leader = 1;
        $team_user->is_deleted = 0;
        $team_user->save();

        $team['user_id'] =$request->input('user_id');

        return response()->json([
            'team' => $team,
            'success' => true
        ], 200);
    }

    public function updateTeams(Request $request,$id_team)
    {
        $team = Team::findOrFail($id_team);
        $team->name = $request->input('name');
        $team->description = $request->input('description');
        $team->status = 'active';
        $team->is_deleted = 0;
        $team->save();

        // $team_user = TeamUser::where([['user_id','=',$request->input('user_id')],['is_deleted','=',0]])->get();

        $team_leader_id = TeamUser::where([['team_id','=',$id_team],['is_leader','=',1],['is_deleted','=',0]])->get('id');

        $team_leader = TeamUser::findOrFail($team_leader_id[0]['id']);

        if($team_leader['user_id'] != $request->input('user_id')){

            $team_leader->is_deleted = 1;
            $team_leader->save();

            $team_user = new TeamUser();
            $team_user->team_id = $team->id;
            $team_user->user_id = $request->input('user_id');
            $date_now = Carbon::now()->toDateTimeString();
            $team_user->integration_date = $date_now;
            $team_user->is_leader = 1;
            $team_user->is_deleted = 0;
            $team_user->save();
        }
        $team['user_id'] =$request->input('user_id');

        return response()->json([
            'user' => $team_user,
            'team' => $team,
            'success' => true
        ], 200);
    }

    public function deleteTeams($id_team)
    {
        $team = Team::findOrFail($id_team);
        $team->is_deleted = 1;
        $team->save();

        return response()->json([
            'team' => $team,
            'success' => true
        ], 200);
    }

    public function deleteUserTeams($id_team_user)
    {
        $team_user = TeamUser::findOrFail($id_team_user);
        $team_user->is_deleted = 1;
        $team_user->save();
        return response()->json([
            'team' => $team_user,
            'success' => true
        ], 200);
    }

    public function desactiverTeams($id_team)
    {
        $team = Team::findOrFail($id_team);
        $team->status = 'inactive';
        $team->save();
        return response()->json([
            'team' => $team,
            'success' => true
        ], 200);
    }

    public function activerTeams($id_team)
    {
        $team = Team::findOrFail($id_team);
        $team->status = 'active';
        $team->save();
        return response()->json([
            'team' => $team,
            'success' => true
        ], 200);
    }


}
