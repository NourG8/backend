<?php

namespace App\Http\Controllers;
use DB;
use App\Models\Position;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PositionController extends Controller
{
    public function getAllPositions()
    {
        $positions = DB::table('positions')
                 ->select('positions.*')
                 ->where([
                    ['positions.status', '=', "active"],
                    ['positions.is_deleted','=',0],
                    ])
                ->get();

      return response()->json($positions);
    }

    public function AddPosition(Request $request)
    {
        $position = new Position();
        $position->jobName = $request->input('jobName');
        $position->description = $request->input('description');
        $position->status = "active";
        $position->title = $request->input('title');
        $position->role_id = $request->input('role_id');
        $position->save();
        return response()->json([
            'position' => $position,
            'success' => true
        ], 200);
    }

    public function editPosition(Request $request,$id)
    {
        $position = Position::findOrFail($id);
        $position->jobName= $request->jobName;
        $position->description= $request->description;
        $position->title = $request->title;
        $position->role_id = $request->role_id;
        $position->save();
        return response()->json([
            'position' => $position,
            'success' => true
        ], 200);
    }

    public function destroyPosition($id)
    {
        $pos = Position::findOrFail($id);
        $pos->is_deleted=1;
        $pos->save();
        return response()->json([
            'position' => $pos,
            'success' => true
        ], 200);

    }
    public function archivePosition($id)
    {
        $position = Position::findOrFail($id);
        $position->status = "inactive";
        $position->save();
        return response()->json([
            'position' => $position,
            'success' => true
        ], 200);
    }

    public function resetPosition($id)
    {
        $position = DB::table('positions')
        ->leftJoin('position_users', 'position_users.position_id', '=', 'positions.id')
        ->leftJoin('users', 'position_users.user_id', '=', 'users.id')
        ->select('users.lastName','users.firstName','positions.jobName')
        ->where([
            ['position_users.endDate', '=', null],
            ['positions.id', '=', $id],
        ])->update(['positions.status' =>'active']);
        return response()->json([
            'position' => $position,
            'success' => true
        ], 200);
    }

    public function getArchivedPosition()
    {
          $position = DB::table('positions')
            ->select('positions.*')
            ->where([
                ['positions.status', '!=', "active"],
                ])
            ->get();
        return response()->json($position);
    }

    public function getNb_Users_in_Pos($id)
    {
       $nbUsers = DB::table('users')
       ->leftJoin('position_users', 'position_users.user_id', '=', 'users.id')
       ->leftJoin('positions', 'position_users.position_id', '=', 'positions.id')
       ->select('users.lastName','users.firstName','positions.jobName')
       ->where([
            ['positions.id', '=', $id],
            ['position_users.endDate', '=', null],
            ['users.is_deleted', '=', 0],
            ['positions.is_deleted', '=', 0],
            ['users.status', '=', "active"],
            ['positions.status', '=', "active"],
            ])->count();
            return response()->json(
                $nbUsers);
    }

}
