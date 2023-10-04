<?php

namespace App\Http\Controllers;
use DB;
use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{

    public static function Get_id_Permission($permission)
    {
        $result = DB::table('permissions')
        ->where('permissions.namePermission', '=', $permission)
        ->pluck("id");
        return $result[0];
    }

    public function getAllPermissions()
    {
        $permissions = DB::table('permissions')
        ->select('permissions.*')
        ->where([
        ['permissions.is_deleted','=',0],
        ['permissions.status','=',"active"],
                ])->get();

        return response()->json($permissions);
    }

    public function getPermissions()
    {
        // $arr2 = str_split($str, 3);
        $table = [];
        $permissions = Permission::all();
        for ($i = 0; $i < count($permissions); $i++) {
         $table = $permissions[$i]->namePermission;
         // // this.table.permission = this.getAllPermissions[i].namePermission.split('.')[1];
        //  $table->permission = [];
        // $table = array("text"=>$permissions[$i]->namePermission.split('.')[1],"value"=>$permissions[$i]->id);
        return response()->json($table);
        }
        return response()->json($table);
        //  console.log(this.table);
        // $permissions = DB::table('permissions')
        //      ->select("permissions.namePermission")
        //      ->where('status', '<>', 1)
        //      ->groupBy('status')
        //      ->get();
        // return response()->json($permissions);
    }
    public function AddPermission(Request $request)
    {
        // $validatationData = $request->validate($this->ValidationData());
        $permission = new Permission();
        $permission->namePermission = $request->input('namePermission');
        $permission->description = $request->input('description');
        $permission->code = $request->input('code');
        $permission->status = "active";
        $permission->save();

        return response()->json([
            'permission' => $permission,
            'success' => true
        ], 200);
    }

    public function getOnePermission($id)
    {
        $p = Permission::findOrFail($id);
        return response()->json($p);
    }

    public function editPermission(Request $request,$id)
    {
        $permission = Permission::findOrFail($id);
        $permission->namePermission= $request->namePermission;
        $permission->description = $request->description;
        $permission->code = $request->code;
        $permission->save();
        return response()->json([
            'permission' => $permission,
            'success' => true
        ], 200);
    }

    public function destroyPermission($id)
    {
        $p = Permission::findOrFail($id);
        $p->is_deleted=1;
        $p->save();
        return response()->json([
            'permissions' => $p,
            'success' => true
        ], 200);

    }

    public function archivePermission($id)
    {
        $permission = Permission::findOrFail($id);
        $permission->status = "inactive";
        $permission->save();
        return response()->json([
            'permission' => $permission,
            'success' => true
        ], 200);
    }
    public function resetPermission($id)
    {
        $permission = Permission::findOrFail($id);
        $permission->status = "active";
        $permission->save();
        return response()->json([
            'permission' => $permission,
            'success' => true
        ], 200);
    }
    public function getArchivedPermission()
    {
        $permissions = DB::table('permissions')
        ->select('permissions.*')
        ->where([['permissions.status','inactive']])
            // ->orwhere('permission_roles.status', '=', "inactive-role")
            ->get();
        return response()->json($permissions);
    }

    public function getRolePermission($id)
    {
        $permission = DB::table('permission_roles')
        ->leftJoin('roles', 'permission_roles.role_id', '=', 'roles.id')
        ->leftJoin('permissions', 'permission_roles.permission_id', '=', 'permissions.id')
        ->select('permissions.*')
        ->where([['role_id',$id],['permissions.status','active'],['permissions.is_deleted',0]])
        ->get();
         return response()->json($permission);
    }

    public function permissionUsers($id)
    {
       $permissions = Permission::where('id',$id)->with([
            'roles' =>[
                'role'=> [
                    'positions'=> [
                        'users'=> [
                            'user',
                        ],
                    ],
                ],
          ],
        ])->get();

        $obj_user;

        $objet_permission = [];

        $objet_role ;
        $objet_role['id'] = "";
        $objet_role['name'] = "";
        $objet_role['children'] = [];

        $count = 0;
        foreach ($permissions[0]['roles'] as $role_perm) {
            $objet_role['id'] = $count++;
            $objet_role['name'] = $role_perm['role']['role'];
            $objet_role['icon'] = 'mdiAccountDetails';
            $objet_role['children'] = [];
            foreach ($role_perm['role']['positions'] as $position) {
                $obj['id'] = $count++;
                $obj['name'] = $position['jobName'];
                $obj['icon'] = 'mdiBriefcase';
                $obj['children'] = [];

                $obj_user['id'] = $count++;
                $obj_user['children'] = [];
                foreach ($position['users'] as $user) {
                    $objet_user['id'] = $count++;
                    $objet_user['icon'] = 'mdiAccount';
                    $objet_user['name'] = $user['user']['lastName'] .' '. $user['user']['firstName'];
                    array_push( $obj['children'], $objet_user);
                }
            }
                array_push($objet_role['children'], $obj);
            array_push($objet_permission, $objet_role);
        }
        return response()->json($objet_permission);
    }

}
