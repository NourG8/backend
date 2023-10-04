<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Department;
class DepartmentSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $departments =  [
            [
              'departmentName' => 'Caller Center',
              'description' => 'Departement CC (Equipe CC_1 et Equipe CC_2)',
              'status' => 'active',
              'chef_dep'=>2,
              'is_deleted'=>0,
              'created_at' => now(),
              'updated_at' => now()
            ],
            [
              'departmentName' => 'Information Technology',
              'description' => 'Departement CC (Equipe IT_MN et Equipe IT_HH et Equipe IT_MM)',
              'status' => 'active',
              'chef_dep'=>3,
              'is_deleted'=>0,
              'created_at' => now(),
              'updated_at' => now()
            ],
      ];

      Department::insert($departments);
    }
}
