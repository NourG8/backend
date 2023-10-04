<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Auth;
use DB;
use App\Models\User;

class AuthController extends Controller
{
    static public function login(Request $request)
    {

        $fields=$request->validate([
            'email'=> 'required|string',
            'password'=>'required'
        ]);

        // Check email
        $user = User::where('email',$fields['email'])->first();

        // Check user / password / statuts user !
        if (!$user || !Hash::check($fields['password'], $user->password) || $user->status == "inactive"  ||  $user->is_deleted == 1 ) {
            return response([
                'message' => 'Bad creds'
            ], 401);
        }

        $token = $user->createToken('api_token')->plainTextToken;

        $company = CompanyController::getCompany();

        $role_user = DB::table('users')
            ->leftJoin('position_users', 'position_users.user_id', '=', 'users.id')
            ->leftJoin('positions', 'position_users.position_id', '=', 'positions.id')
            ->leftJoin('roles', 'positions.role_id', '=', 'roles.id')
            ->select('positions.role_id','roles.role')
            ->where('users.id','=',$user['id'])
            ->get();

        $role_id = json_decode($role_user, true);

        $permission = DB::table('permission_roles')
            ->leftJoin('permissions', 'permission_roles.permission_id', '=', 'permissions.id')
            ->leftJoin('roles', 'permission_roles.role_id', '=', 'roles.id')
            ->leftJoin('positions', 'positions.role_id', '=', 'roles.id')
            ->leftJoin('position_users', 'position_users.position_id', '=', 'positions.id')
            ->leftJoin('users', 'position_users.user_id', '=', 'users.id')
            ->select('permissions.*')
            ->where('permission_roles.role_id','=',$role_id[0]['role_id'])
            ->get();

        $result_permission = json_decode($permission, true);
        $List_permission = [];

        foreach ($result_permission as $permission) {
            array_push($List_permission, $permission['namePermission']);
        }

        $response = [
            'token' => $token,
            'company'=> $company,
            'user' => $user,
            'role' => $role_id[0]['role'],
            'permission' => $List_permission
        ];

        return response($response, 201);
    }

    public function logout()
    {
        /** @var User $user */
        $user = Auth::user();
        // Revoke the token that was used to authenticate the current request...
        $user->currentAccessToken()->delete();

        return response([
            'success' => true
        ]);
    }


}



