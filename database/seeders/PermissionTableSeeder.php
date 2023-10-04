<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;
class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions =  [
              [
                'code' => '123E',
                'namePermission' => 'user.edit',
                'description' => 'you can edite user ...',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
              ],
              [
                'code' => '123C',
                'namePermission' => 'user.create',
                'description' => 'you can create new user ...',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
              ],
              [
                'code' => '123C',
                'namePermission' => 'user.create_multiple',
                'description' => 'you can create multiple user ...',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
              ],
              [
                'code' => '123R',
                'namePermission' => 'user.read',
                'description' => 'you can read list users ...',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
              ],
              [
                'code' => '123RO',
                'namePermission' => 'user.readOne',
                'description' => 'you can read one user ...',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
              ],
              [
                'code' => '123D',
                'namePermission' => 'user.delete',
                'description' => 'you can delete user ...',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
              ],
              [
                'code' => '123A',
                'namePermission' => 'user.archive',
                'description' => 'you can archive user ...',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
              ],
              [
                'code' => '123A',
                'namePermission' => 'user.reset_pwd',
                'description' => 'you can reset password user ...',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
              ],
              [
                'code' => '123A',
                'namePermission' => 'user.read_contract_user',
                'description' => 'you can read list of contract user ...',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
              ],
              [
                'code' => '123A',
                'namePermission' => 'user.list_archive_user',
                'description' => 'you can read list of archive user ...',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
              ],
              [
                'code' => '123A',
                'namePermission' => 'user.restor_user',
                'description' => 'you can restor user archive...',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
              ],



              [
                'code' => '852E',
                'namePermission' => 'permission.edit',
                'description' => 'you can edite permission ...',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
              ],
              [
                'code' => '852C',
                'namePermission' => 'permission.create',
                'description' => 'you can create new permission ...',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
              ],
              [
                'code' => '852R',
                'namePermission' => 'permission.read',
                'description' => 'you can read list permissions ...',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
              ],
              [
                'code' => '852RO',
                'namePermission' => 'permission.readOne',
                'description' => 'you can read one permission ...',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
              ],
              [
                'code' => '852RO',
                'namePermission' => 'permission.archive',
                'description' => 'you can archive permission ...',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
              ],
              [
                'code' => '852RO',
                'namePermission' => 'permission.delete',
                'description' => 'you can delete permission ...',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
              ],
              [
                'code' => '852RO',
                'namePermission' => 'permission.list_archive_permission',
                'description' => 'you can read list of archive permission ...',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
              ],
              [
                'code' => '852RO',
                'namePermission' => 'permission.restor_permission',
                'description' => 'you can restor permission ...',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
              ],


              [
                'code' => '852A',
                'namePermission' => 'role.archive',
                'description' => 'you can archive role ...',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
              ],
              [
                'code' => '852D',
                'namePermission' => 'role.delete',
                'description' => 'you can delete role ...',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
              ],

              [
                'code' => '963E',
                'namePermission' => 'role.edit',
                'description' => 'you can edite role ...',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
              ],
              [
                'code' => '963C',
                'namePermission' => 'role.create',
                'description' => 'you can create new role ...',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
              ],
              [
                'code' => '963R',
                'namePermission' => 'role.read',
                'description' => 'you can read list roles ...',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
              ],
              [
                'code' => '963R',
                'namePermission' => 'role.readOne',
                'description' => 'you can read one role ...',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
              ],
              [
                'code' => '963D',
                'namePermission' => 'role.read_permissions',
                'description' => 'you can read list of permission related to role ...',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
              ],
              [
                'code' => '963R',
                'namePermission' => 'role.list_archive_role',
                'description' => 'you can read list of archive roles ...',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
              ],
              [
                'code' => '963D',
                'namePermission' => 'role.restor_role',
                'description' => 'you can restor one role ...',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
              ],
              [
                'code' => '963D',
                'namePermission' => 'role.read_positions',
                'description' => 'you can delete role ...',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
              ],



              [
                'code' => '456E',
                'namePermission' => 'position.edit',
                'description' => 'you can edite position ...',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
              ],
              [
                'code' => '456C',
                'namePermission' => 'position.create',
                'description' => 'you can create new position ...',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
              ],
              [
                'code' => '456R',
                'namePermission' => 'position.read',
                'description' => 'you can read list of positions ...',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
              ],
              [
                'code' => '456RO',
                'namePermission' => 'position.readOne',
                'description' => 'you can read one position ...',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
              ],
             [
                'code' => '456A',
                'namePermission' => 'position.archive',
                'description' => 'you can archive position ...',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
              ],
              [
                'code' => '456D',
                'namePermission' => 'position.delete',
                'description' => 'you can delete position ...',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
              ],

             [
                'code' => '456A',
                'namePermission' => 'position.list_archive_positions',
                'description' => 'you can read list of archive position ...',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
              ],
              [
                'code' => '456D',
                'namePermission' => 'position.restor_position',
                'description' => 'you can restor position ...',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
              ],




              [
                'code' => '456E',
                'namePermission' => 'department.edit',
                'description' => 'you can edite department ...',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
              ],
              [
                'code' => '456C',
                'namePermission' => 'department.create',
                'description' => 'you can create new department ...',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
              ],
              [
                'code' => '456R',
                'namePermission' => 'department.read',
                'description' => 'you can read list of department ...',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
              ],
              [
                'code' => '456RO',
                'namePermission' => 'department.readOne',
                'description' => 'you can read one department ...',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
              ],
             [
                'code' => '456A',
                'namePermission' => 'department.archive',
                'description' => 'you can archive department ...',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
              ],
              [
                'code' => '456D',
                'namePermission' => 'department.delete',
                'description' => 'you can delete department ...',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
              ],

             [
                'code' => '456A',
                'namePermission' => 'department.list_archive_departments',
                'description' => 'you can read list of archive department ...',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
              ],
              [
                'code' => '456D',
                'namePermission' => 'department.restor_department',
                'description' => 'you can restor department ...',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
              ],



              [
                'code' => '456E',
                'namePermission' => 'company.edit',
                'description' => 'you can edite company ...',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
              ],
              [
                'code' => '456C',
                'namePermission' => 'company.create',
                'description' => 'you can create new company ...',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
              ],
              [
                'code' => '456RO',
                'namePermission' => 'company.read',
                'description' => 'you can read one company ...',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
              ],
              [
                'code' => '456E',
                'namePermission' => 'profil.edit',
                'description' => 'you can edite your profil ...',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
              ],
              [
                'code' => '456RO',
                'namePermission' => 'profil.read',
                'description' => 'you can read your profil ...',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
              ],
              [
                'code' => '456RO',
                'namePermission' => 'dashboard.read',
                'description' => 'you can view dashboard ...',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
              ],
              [
                'code' => '456RC',
                'namePermission' => 'conges.read',
                'description' => 'you can view list des demandes de conges des employées ! ...',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
              ],
              [
                'code' => '456ROC',
                'namePermission' => 'conges.readOne',
                'description' => 'you can view your leave request list ...',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
              ],
              [
                'code' => '456AC',
                'namePermission' => 'conges.add',
                'description' => 'you can add a leave request ...',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
              ],

              [
                'code' => '456RT',
                'namePermission' => 'teletravail.read',
                'description' => 'you can view telework list for employees ! ...',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
              ],
              [
                'code' => '456ROT',
                'namePermission' => 'teletravail.readOne',
                'description' => 'you can view your telework request list ...',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
              ],
              [
                'code' => '456AT',
                'namePermission' => 'teletravail.add',
                'description' => 'you can add a telework request ...',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
              ],

        ];

          Permission::insert($permissions);
    }
}

