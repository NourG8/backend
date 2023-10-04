<?php

namespace App\Http\Controllers;
use DB;
use App\Models\Role;
use App\Models\PermissionRole;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function deletePermissions($role_id)
    {
        $result = DB::table('permission_roles')
            ->select('permission_roles.*')
            ->where('permission_roles.role_id', '=', $role_id)
            ->get();
        $permission_role = json_decode($result,true);
            if(count($permission_role) != 0){
                for ($k=0; $k<count($permission_role); $k++) {
                    $permission_delete = PermissionRole::findOrFail($permission_role[$k]['id']);
                        $permission_delete->delete();
                }
            }
        return response()->json([
            'success' => true
        ], 200);
    }

    public function AffectPermissionsRole(Request $request,$id_role)
    {
        RoleController::deletePermissions($id_role);
        $permission_role = $request->input('tab_permission');

        for ($j=0; $j<count($permission_role); $j++) {
            $permission = new PermissionRole();
            $permission->permission_id = $permission_role[$j];
            $permission->role_id = $id_role;
            $permission->status = "active";
            $permission->save();
        }

        return response()->json([
            'success' => true
        ], 200 );
    }

    public function getAllRoles()
    {
        $roles = Role::where([['status',"active"],['is_deleted',0]])->with('positions','permissions')->get();
        return response()->json($roles);
    }

    public function getAllRolesWithArchives()
    {
        $roles = Role::where([['status',"active"],['is_deleted',0]])->with('positions')->orderBy('id','DESC')->get();
        return response("yes");
    }

    public function AddRole(Request $request)
    {
        $r = new Role();
        $r->role = $request->input('role');
        $r->status = "active";
        $r->description = $request->input('description');

        $r->save();
        return response()->json([
            'role' => $r,
            'success' => true
        ], 200);
    }

    public function getOneRole($id)
    {
        $role= Role::findOrFail($id);
        return response()->json($role->load('permissions'));
    }

    public function editRole(Request $request,$id)
    {
        $role = Role::findOrFail($id);
        $role->role= $request->role;
        $role->description = $request->description;
        $role->save();
        return response()->json([
            'role' => $role,
            'success' => true
        ], 200);
    }

    public function destroyRole($id)
    {
       $role = DB::table('roles')
       ->leftJoin('permission_roles', 'permission_roles.role_id', '=', 'roles.id')
       ->leftJoin('permissions', 'permission_roles.permission_id', '=', 'permissions.id')

       ->select('roles.role')
       ->where([
           ['roles.id', '=', $id],
       ])->update(['roles.is_deleted' => 1,'permission_roles.is_deleted' => 1 ]);

       return response()->json([
        'role' => $role,
        'success' => true
                ], 200);
    }

    public function archiveRole($id)
    {
        $role = Role::findOrFail($id);
        $role->status = "inactive";
        $role->save();
        return response()->json([
            'role' => $role,
            'success' => true
        ], 200);
    }

    public function getNb_Pos_in_Role($id)
    {
       $nbPos = DB::table('positions')
        ->leftJoin('roles', 'positions.role_id', '=', 'roles.id')

        ->select('positions.jobName','roles.role')
        ->where([
            ['roles.id', '=', $id],
            ['positions.is_deleted', '=', 0],
            ['roles.is_deleted', '=', 0],
            ['positions.status', '=',  "active"],
            ['roles.status', '=',  "active"],
        ]) ->count();
        return response()->json(
            $nbPos);
    }

    public function getNb_Permissions_in_Role($id)
    {
       $nbPer = DB::table('permissions')
        ->leftJoin('permission_roles', 'permission_roles.permission_id', '=', 'permissions.id')
        ->leftJoin('roles', 'permission_roles.role_id', '=', 'roles.id')

        ->select('permissions.namePermission','roles.role')
        ->where([
            ['roles.id', '=', $id],
            ['permissions.is_deleted', '=', 0],
            ['roles.is_deleted', '=', 0],
            ['permissions.status', '=', "active"],
            ['roles.status', '=', "active"],
        ]) ->count();
        return response()->json(
            $nbPer);
    }

    public function resetRole($id)
    {
        $role = Role::findOrFail($id);
        $role->status = "active";
        $role->save();
        return response()->json([
            'role' => $role,
            'success' => true
        ], 200);
    }
    public function getArchivedRole()
    {
          $roles = DB::table('roles')
            ->select('roles.*')
            ->where([
                ['roles.status', '=', "inactive"],
                ])
            ->get();
        return response()->json($roles);
    }

    public function ArchiveRolePermission($id)
    {
        $PermissionsRole = DB::table('permission_roles')
        ->leftJoin('roles', 'permission_roles.role_id', '=', 'roles.id')
        ->leftJoin('permissions', 'permission_roles.permission_id', '=', 'permissions.id')
        ->select('permissions.*','permission_roles.status as status_per_role','roles.status as status_role')
        ->where([['role_id',$id], ['permission_roles.status','=','active'], ['permissions.status','=','active'],['permission_roles.is_deleted','=',0], ['permissions.is_deleted','=',0]])
        ->update(['permission_roles.status' =>'inactive-role', 'roles.status'=> 'inactive']);
        return response()->json($PermissionsRole);
    }

    public function ResetRolePermission($id)
    {
        $PermissionsRole = DB::table('permission_roles')
            ->leftJoin('roles', 'permission_roles.role_id', '=', 'roles.id')
            ->leftJoin('permissions', 'permission_roles.permission_id', '=', 'permissions.id')
            ->select('permissions.*','permission_roles.*','roles.*')
            ->where([['role_id',$id], ['permission_roles.status','inactive-role']])
            ->update(['permission_roles.status' =>'active', 'roles.status'=> 'active']);

        return response()->json($PermissionsRole);
    }

    public function RoleUsers($id)
    {
       $roles = Role::where('id',$id)->with([
            'positions'=> [
                'users'=> [
                    'user',
                ],
            ],
        ])->get();

        $List_role = [];
        $objet_user;
        $obj;
        $j = 0;
        $i = 0;

        $obj_position =[] ;

        $count = 0;

        foreach ($roles[0]['positions'] as $position) {
            $obj['id'] = $count++;
            $obj['icon'] = 'mdiBriefcase';
            $obj['name'] = $position['jobName'];
            $obj['children'] = [];
            foreach ($position['users'] as $user) {
                $objet_user['id'] = $count++;
                $objet_user['icon'] = 'mdiAccount';
                $objet_user['name'] = $user['user']['lastName'] .' '. $user['user']['firstName'];
                array_push($obj['children'], $objet_user);
            }
            array_push($obj_position,  $obj);
        }
        return response()->json($obj_position);

    }

}
