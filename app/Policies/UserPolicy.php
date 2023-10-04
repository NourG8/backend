<?php

namespace App\Policies;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PermissionController;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */

    public function viewAll(User $user)
    {
        $id_permission = PermissionController::Get_id_Permission("user.read");
        return User::checkPermission(Auth::id(), $id_permission) == 1
                         ? true
                         : false;
    }

    public function viewOne(User $user)
    {
        $id_permission = PermissionController::Get_id_Permission("user.readOne");
        return User::checkPermission(Auth::id(), $id_permission) == 1
                         ? true
                         : false;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        $id_permission = PermissionController::Get_id_Permission("user.create");
        return User::checkPermission(Auth::id(), $id_permission) == 1
                         ? true
                         : false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user)
    {
        $id_permission = PermissionController::Get_id_Permission("user.edit");
        return User::checkPermission(Auth::id(), $id_permission) == 1
                         ? true
                         : false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user)
    {
        $id_permission = PermissionController::Get_id_Permission("user.delete");
        return User::checkPermission(Auth::id(), $id_permission) == 1
                         ? true
                         : false;
    }

    public function archive(User $user)
    {
        $id_permission = PermissionController::Get_id_Permission("user.read");
        return User::checkPermission(Auth::id(), $id_permission) == 1
                         ? true
                         : false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, User $model)
    {

    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, User $model)
    {
        //
    }
}
