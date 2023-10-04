<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use DB;
use PositionController;

class DepartmentController extends Controller
{

    public function getAllDepartments()
    {   $departments = DB::table('departments')
        ->select('departments.*')
        ->where([
            ['departments.status', '=', "active"],
            ['departments.is_deleted','=',0],
            ])
        ->get();
        return response()->json($departments);
    }

    public function AddDepartment(Request $request)
    {
        $dep = new Department();
        $dep->departmentName = $request->input('departmentName');
        $dep->chef_dep= $request->input('chef_dep');
        $dep->description = $request->input('description');
        $dep->is_deleted = 0;
        $dep->status = "active";
        $dep->save();
        return response()->json([
            'department' => $dep,
            'success' => true
        ], 200);
    }

    public function editDepartment(Request $request,$id)
    {
        $dep = Department::findOrFail($id);
        $dep->departmentName= $request->departmentName;
        $dep->chef_dep= $request->chef_dep;
        $dep->description= $request->description;
        $dep->save();
        return response()->json([
            'department' => $dep,
            'success' => true
        ], 200);
    }

    public function destroyDepartment($id)
    {
        $dep = DB::table('departments')
        ->leftJoin('teams', 'teams.department_id', '=', 'departments.id')
        ->select('teams.name','departments.departmentName')
        ->where([
            ['departments.id', '=', $id],
            ['departments.is_deleted', '=', 0],
        ])
        ->update(['teams.is_deleted' =>1, 'departments.is_deleted'=> 1]);

        return response()->json([
            'departement' => $dep,
            'success' => true
        ], 200);
    }

    public function archiveDepartment($id,$nbUsers,$nbTeam)
    {
        $departments = Department::findOrFail($id);
        $test = null;
        if($nbUsers==0 && $nbTeam!=0){
                $dep = DB::table('departments')
                    ->leftJoin('teams', 'teams.department_id', '=', 'departments.id')
                    ->leftJoin('team_users', 'team_users.team_id', '=', 'teams.id')
                    ->leftJoin('users', 'team_users.user_id', '=', 'users.id')
                    ->select('users.lastName','users.firstName','teams.name','departments.departmentName')
                    ->where([
                        ['departments.id', '=', $id],
                        ['departments.is_deleted', '=', 0],
                        ['departments.status', '=', "active"],
                        ['team_users.is_deleted', '=', 0],
                        ['teams.status', '=', "active"],
                        ['teams.is_deleted', '=', 0],
                    ])->update(['teams.status' =>'inactive_dep', 'departments.status'=> 'inactive']);
                    $departments->status = "inactive";
                }else if($nbUsers==0 && $nbTeam==0){
                    $departments = Department::findOrFail($id);
                    $departments->status = "inactive";
                    $departments->save();
                }else if($nbUsers!=0){
                    $test = $nbUsers;
               }
                    return response()->json([
                        'department' => $departments,
                        'test' => $test,
                        'success' => true
                    ], 200);
    }

    //chnowa hedhyyy
    public function archiveDepartment_Pos_User($id)
    {
        $departments = DB::table('departments')
            ->leftJoin('teams', 'teams.department_id', '=', 'departments.id')
            ->leftJoin('team_users', 'team_users.team_id', '=', 'teams.id')
            ->leftJoin('users', 'team_users.team_id', '=', 'users.id')
            ->select('users.lastName','users.firstName','teams.name','departments.departmentName')
            ->where([
                ['departments.id', '=', $id],
                ['position_users.endDate', '=', null],
                ['teams.status', '=', "active"],
            ])->get();
        return response()->json([
            'department' => $departments,
            'success' => true
        ], 200);
    }

    public function getNb_team_in_dep($id)
    {
       $nbteam = DB::table('teams')
        ->leftJoin('departments', 'teams.department_id', '=', 'departments.id')
        ->leftJoin('team_users', 'team_users.team_id', '=', 'teams.id')
        ->select('teams.name','departments.departmentName')
        ->where([
            ['departments.id', '=', $id],
            ['teams.is_deleted', '=', 0],
            ['departments.is_deleted', '=', 0],
            ['teams.status', '=', "active"],
            ['departments.status', '=', "active"],
        ]) ->count();
        return response()->json($nbteam);
    }

    public function getNb_team_in_dep_Archive($id)
    {
       $nbTeam = DB::table('teams')
        ->leftJoin('departments', 'teams.department_id', '=', 'departments.id')
        ->leftJoin('team_users', 'team_users.team_id', '=', 'teams.id')
        ->select('teams.name','departments.departmentName')
        ->where([
            ['departments.id', '=', $id],
            ['teams.is_deleted', '=', 0],
            ['departments.is_deleted', '=', 0],
            ['teams.status', '=', "inactive_dep"],
            ['departments.status', '=', "inactive"],
        ])
        ->count();
        return response()->json($nbTeam);
    }

    public function getNb_Users_in_dep($id)
    {
       $nbUsers = DB::table('users')
       ->leftJoin('team_users', 'team_users.user_id', '=', 'users.id')
         ->leftJoin('teams', 'team_users.team_id', '=', 'teams.id')
        ->leftJoin('departments', 'teams.department_id', '=', 'departments.id')
        ->select('users.lastName','users.firstName','teams.name','departments.departmentName')
        ->where([
            ['departments.id', '=', $id],
            ['users.status', '=', 'active'],
            ['users.is_deleted', '=', 0],
            ['departments.is_deleted', '=', 0],
            ['departments.status', '=', 'active'],
            ['teams.is_deleted', '=', 0],
            ['teams.status', '=', 'active'],
        ])->count();
        return response()->json(
            $nbUsers);
    }

    public function resetDepartment($id)
    {
        $dep = DB::table('departments')
            ->leftJoin('teams', 'teams.department_id', '=', 'departments.id')
            ->leftJoin('team_users', 'team_users.team_id', '=', 'teams.id')
            ->leftJoin('users', 'team_users.user_id', '=', 'users.id')
            ->select('users.lastName','users.firstName','teams.name','departments.departmentName')
            ->where([
                ['departments.id', '=', $id],
                ['teams.status', '=', "inactive_dep"],
                ['departments.status', '=', "inactive"],
            ])
            ->update(['teams.status' =>'active',  'departments.status'=> 'active']);

        return response()->json([
            'department' => $dep,
            'success' => true
        ], 200);
    }

    public function resetDep($id)
    {
        $dep = DB::table('departments')
        ->select('departments.departmentName')
        ->where([
            ['departments.id', '=', $id],
            ['departments.status', '=', "inactive"],
        ])
        ->update(['departments.status'=> 'active']);

        $departments = Department::findOrFail($id);

        return response()->json([
            'department' => $departments,
            'success' => true
        ], 200);
    }

    public function getArchivedDepartment()
    {
          $departments = DB::table('departments')
            ->select('departments.*')
            ->where([
                ['departments.status', '=', "inactive"],
                ])
            ->get();
        return response()->json($departments);
    }

    public function getUserDepartment($id_dep)
    {
        $result = DB::table('users')
              ->leftJoin('team_users', 'team_users.user_id', '=', 'users.id')
              ->leftJoin('teams', 'team_users.team_id', '=', 'teams.id')
             ->leftJoin('departments', 'teams.department_id', '=', 'departments.id')
              ->select('users.*')
              ->where('departments.id', '=', $id_dep)
              ->get();
        return $result;
    }

}
