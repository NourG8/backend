<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\wordTestController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\CongeController;
use App\Http\Controllers\TeletravailController;
use App\Http\Controllers\TeamController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);

    //CRUD operations user
    Route::get("users", [UserController::class, 'getAllUsers']);
    Route::get("users/model/{id}", [UserController::class, 'getContractsUserModel']);
    Route::get("users/contract/{id}", [UserController::class, 'getContractsUserSigned']);
    Route::get("users/delete/{id}", [UserController::class, 'destroyUser']);
    Route::post("users", [UserController::class, 'AddUser']);
    Route::put("users/{id}", [UserController::class, 'editUser']);
    Route::put("user/archive/{id}", [UserController::class, 'archiveUser']);
    Route::put("admin/reset/password/{id}", [ForgotPasswordController::class, 'AdminResetPassword']);
    Route::put("reset/password/{id}", [ForgotPasswordController::class, 'ResetPassword']);
    Route::get("user/list/archive", [UserController::class, 'getArchivedUser']);
    Route::put("user/reset/{id}", [UserController::class, 'resetUser']);
    Route::post("affectContracts", [UserController::class, 'AffectContractsToUser']);
    Route::put("user_contract/delete/{id}", [UserController::class, 'DeleteContractsUser']);
    Route::put("user/edit/{id}", [UserController::class, 'editUserIntern']);
    Route::put("changerImg/{id}", [UserController::class, 'ChangePhotoProfil']);
    Route::get("user/position/{id}", [UserController::class, 'getPositionUser']);
    Route::post("user/upload/old_contract/{id_user}", [UserController::class, 'uploadOldContract']);
    Route::get("user/download/old_contract/{id_user_contract}", [UserController::class, 'DownloadModelContracts']);
    Route::get("user_contract/download/{id_user_contract}", [UserController::class, 'DownloadOldContract']);
    // Route::get("user_signed_contract/download/{id_user_contract}", [UserController::class, 'DownloadSignedContract']);
    Route::post("editContractUser/{id_user_contract}", [UserController::class, 'EditContractsToUser']);
    Route::get("getRole_user/{id}", [UserController::class, 'getRole_Auth']);
    Route::get("user/teams/{id}", [UserController::class, 'getTeams_Department']);

    //CRUD operations position
    Route::get("positions", [PositionController::class, 'getAllPositions']);
    Route::get("positions/delete/{id}", [PositionController::class, 'destroyPosition']);
    Route::post("positions", [PositionController::class, 'AddPosition']);
    Route::put("positions/{id}", [PositionController::class, 'editPosition']);
    Route::put("position/archive/{id}", [PositionController::class, 'archivePosition']);
    Route::get("position/list/archive", [PositionController::class, 'getArchivedPosition']);
    Route::put("position/reset/{id}", [PositionController::class, 'resetPosition']);
    Route::get("position/getnb_Pos/{id}", [PositionController::class, 'getNb_Users_in_Pos']);

    //CRUD operations role
    Route::get("roles", [RoleController::class, 'getAllRoles']);
    Route::get("roles/{id}", [RoleController::class, 'getOneRole']);
    Route::get("roles/delete/{id}", [RoleController::class, 'destroyRole']);
    Route::post("roles", [RoleController::class, 'AddRole']);
    Route::put("roles/{id}", [RoleController::class, 'editRole']);
    Route::put("role/archive/{id}", [RoleController::class, 'archiveRole']);
    Route::delete("roles/affect/{id}", [RoleController::class, 'deletePermissions']);
    Route::post("roles/affectPerm/{id_role}", [RoleController::class, 'AffectPermissionsRole']);
    Route::get("role/list/archive", [RoleController::class, 'getArchivedRole']);
    Route::put("role/reset/{id}", [RoleController::class, 'resetRole']);
    Route::get("archive/rolePermissions/{id}",[RoleController::class,'ArchiveRolePermission']);
    Route::get("reset/rolePermissions/{id}",[RoleController::class,'ResetRolePermission']);
    Route::get("role/getNb_Pos/{id}",[RoleController::class,'getNb_Pos_in_Role']);
    Route::get("role/getNb_Per/{id}",[RoleController::class,'getNb_Permissions_in_Role']);
    Route::get("Allroles", [RoleController::class, 'getAllRolesWithArchives']);
    Route::get("roles/positions/users/{id}", [RoleController::class, 'RoleUsers']);

    //CRUD operations permission
    Route::get("permissions", [PermissionController::class, 'getAllPermissions']);
    Route::get("permissions/list", [PermissionController::class, 'getPermissions']);
    Route::get("permissions/{id}", [PermissionController::class, 'getOnePermission']);
    Route::get("permissions/delete/{id}", [PermissionController::class, 'destroyPermission']);
    Route::post("permissions", [PermissionController::class, 'AddPermission']);
    Route::put("permissions/{id}", [PermissionController::class, 'editPermission']);
    Route::put("permission/archive/{id}", [PermissionController::class, 'archivePermission']);
    Route::get("permission/list/archive", [PermissionController::class, 'getArchivedPermission']);
    Route::put("permission/reset/{id}", [PermissionController::class, 'resetPermission']);
    Route::get("permission/role/{id}",[PermissionController::class,'getRolePermission']);
    Route::get("permission/users/{id}",[PermissionController::class,'permissionUsers']);

    //CRUD chef departments
    Route::get("chef/departments/{id}", [CongeController::class, 'getChefDepartement']);
    Route::get("gernats", [CongeController::class, 'getAllGerants']);

    //CRUD operations departments
    Route::get("departments", [DepartmentController::class, 'getAllDepartments']);
    Route::get("departments/{id}", [DepartmentController::class, 'getOneDepartment']);
    Route::put("departments/delete/{id}", [DepartmentController::class, 'destroyDepartment']);
    Route::post("departments", [DepartmentController::class, 'AddDepartment']);
    Route::put("departments/{id}", [DepartmentController::class, 'editDepartment']);
    Route::put("departments/archive/{id}/{nbUsers}/{nbTeam}", [DepartmentController::class, 'archiveDepartment']);
    Route::get("department/list/archive", [DepartmentController::class, 'getArchivedDepartment']);
    Route::put("department/reset/{id}", [DepartmentController::class, 'resetDepartment']);
    Route::get("department/user/{id_dep}", [DepartmentController::class, 'getUserDepartment']);
    Route::get("departmentPosUsers/{id}", [DepartmentController::class, 'archiveDepartment_Pos_User']);
    Route::get("getNb_team/{id}", [DepartmentController::class, 'getNb_team_in_dep']);
    Route::get("getNb_team_Archive/{id}", [DepartmentController::class, 'getNb_team_in_dep_Archive']);
    Route::get("getNb_Users/{id}", [DepartmentController::class, 'getNb_Users_in_dep']);
    Route::put("department/resetDep/{id}", [DepartmentController::class, 'resetDep']);

    Route::get("test/{id}", [CongeController::class, 'test_Leader_ChefDep_Gerant']);

     //CRUD operations company
     Route::post("company", [CompanyController::class, 'AddCompany']);
     Route::put("company/{id}", [CompanyController::class, 'editCompany']);
     Route::put("changer/photo/{id}", [CompanyController::class, 'ChangePhoto']);

     //CRUD operations contracts
     Route::get("contracts", [ContractController::class, 'getAllContracts']);
     Route::get("contracts/details/{id}", [ContractController::class, 'getOneContract']);
     Route::put("contract/destroy/{id}", [ContractController::class, 'destroyContract']);
     Route::post("contracts", [ContractController::class, 'AddContract']);
     Route::put("contracts", [ContractController::class, 'editContract']);
     Route::post("upload/contract", [ContractController::class, 'uploadContract']);
     Route::post("update/contract/{id}", [ContractController::class, 'updateContract']);
     Route::get("download/contract/{id}", [ContractController::class, 'downloadContract']);
     Route::get("user/contract/{id}", [UserController::class, 'getAllContractsUser']);

     Route::get("test", [wordTestController::class, 'createWordDocx']);
     Route::get("test/write", [wordTestController::class, 'readWordDocx']);

     Route::post("faqs", [FaqController::class, 'getAllFaqs']);
     Route::get("faq/{id}", [FaqController::class, 'getOneFaq']);
     Route::get("faq/delete/{id}", [FaqController::class, 'destroyFaq']);
     Route::post("faq", [FaqController::class, 'AddFaq']);
     Route::put("faq/{id}", [FaqController::class, 'editFaq']);

     //CRUD operations teams
     Route::post("team", [TeamController::class, 'addTeams']);
     Route::put("delete/team/{id}", [TeamController::class, 'deleteTeams']);
     Route::put("desactiver/team/{id}", [TeamController::class, 'desactiverTeams']);
     Route::put("activer/team/{id}", [TeamController::class, 'activerTeams']);
     Route::put("update/team/{id}", [TeamController::class, 'updateTeams']);
     Route::get("teams", [TeamController::class, 'getAllTeams']);
     Route::get("teams/archive", [TeamController::class, 'getAllArchiveTeams']);
     Route::get("users/manager", [UserController::class, 'getAllUserManager']);
     Route::get("users/teams/{id}", [TeamController::class, 'getUsersInTeams']);
     Route::put("delete/user/team/{id}", [TeamController::class, 'deleteUserTeams']);

     Route::get("conges/{id}", [CongeController::class, 'getAllConge']);
     Route::get("historiques/conges/{id}", [CongeController::class, 'getHistoriqueConge']);
     Route::get("user/historique/conge/{id}", [CongeController::class, 'getHistoriqueCongeUser']);
     Route::post("conge/{id}", [CongeController::class, 'AddConge']);
     Route::put("conge/{id}", [CongeController::class, 'updateConge']);
     Route::put("delete/conge/{id}", [CongeController::class, 'deleteConge']);
     Route::get("user/conge/{id}", [CongeController::class, 'getCongeUser']);

     Route::put("rejet/provisoire/conge/{id}", [CongeController::class, 'RejetProvisoire']);
     Route::put("rejet/definitive/conge/{id}", [CongeController::class, 'RejetDefinitive']);
     Route::put("accepter/conge/{id}", [CongeController::class, 'accepterConge']);
     Route::put("annuler/conge/{id}", [CongeController::class, 'AnnulerConge']);
     Route::get("teletravail/{id}", [TeletravailController::class, 'getAllTeletravils']);
     Route::get("/teletravail/histories/{id}", [TeletravailController::class, 'getAllTeletravilsHistories']);

    //   Route::get("teletravail/{id}", [TeletravailController::class, 'getOneTeletravail']);
      Route::get("/getuser/teletravails", [TeletravailController::class, 'getTeletravailsUser']);
      Route::get("teletravail/delete/{id}", [TeletravailController::class, 'destroyTeletravail']);
      Route::post("teletravail", [TeletravailController::class, 'AddTeletravail']);
      Route::put("teletravail/{id}", [TeletravailController::class, 'editTeletravail']);
      Route::get("teletravail/accept/{id}", [TeletravailController::class, 'acceptTeletravail']);
      Route::get("teletravail/refuse/{id}", [TeletravailController::class, 'refuseTeletravail']);
      Route::get("getUsersFonct/{id}", [TeletravailController::class, 'getUsersFonct']);
      Route::get("getAllTeletravailLeader/{id}", [TeletravailController::class, 'getAllTeletravailLeader']);
      Route::get("user/teletravail/{id}", [TeletravailController::class, 'getTeletravailUser']);

      Route::get("acceptLeader/{id}", [TeletravailController::class, 'acceptTelLeader']);
      Route::get("acceptTelChefDep/{id}", [TeletravailController::class, 'acceptTelChefDep']);
      Route::get("acceptTelGerant/{id}", [TeletravailController::class, 'acceptTelGerant']);

      Route::get("accepter/{id}", [TeletravailController::class, 'accepter']);

      Route::get("getNbLeaders/{id}", [TeletravailController::class, 'getNbLeaders']);
      Route::get("getNbGerants", [TeletravailController::class, 'getNbGerants']);
      Route::get("getNbChefDep/{id}", [TeletravailController::class, 'getNbChefDep']);
      Route::get("responsables/{id}", [TeletravailController::class, 'ResponsableAddTeletravail']);

      Route::post("rejetProvisoire/{id}", [TeletravailController::class, 'RejetProvisoire']);
      Route::post("rejetDefinitive/{id}", [TeletravailController::class, 'RejetDefinitive']);

      Route::get("annulerTeletravail/{id}", [TeletravailController::class, 'AnnulerTeletravail']);
      Route::get("teletravailsHistoriques/{id}", [TeletravailController::class, 'getTeletravailUserHistories']);

      Route::get("send/daily/mail", [CongeController::class, 'SendMailDaily']);

// });

Route::post('/login', [AuthController::class, 'login']);
Route::get('forget-password', [ForgotPasswordController::class, 'showForgetPasswordForm'])->name('forget.password.get');
Route::post('forget', [ForgotPasswordController::class, 'submitForgetPasswordForm'])->name('forget.password.post');
Route::get('reset-password/{token}', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('reset.password.get');
Route::post('resetPassword', [ForgotPasswordController::class, 'submitResetPasswordForm'])->name('reset.password.post');
Route::get('forget-password', [ForgotPasswordController::class, 'showForgetPasswordForm'])->name('forget.password.get');

Route::get("company", [CompanyController::class, 'getCompany']);

