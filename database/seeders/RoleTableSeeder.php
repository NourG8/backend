<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles =  [
            [
              'role' => 'Admin',
              'status' => 'active',
              'description' => 'description ici ...',
              'created_at' => now(),
              'updated_at' => now()
            ],
            [
              'role' => 'Employee',
              'status' => 'active',
              'description' => 'description ici ...',
              'created_at' => now(),
              'updated_at' => now()
            ],
            [
              'role' => 'Manager',
              'status' => 'active',
              'description' => 'description ici ...',
              'created_at' => now(),
              'updated_at' => now()
            ],
            [
              'role' => 'HR manager',
              'status' => 'active',
              'description' => 'description ici ...',
              'created_at' => now(),
              'updated_at' => now()
            ],
            [
              'role' => 'HR consultant',
              'status' => 'active',
              'description' => 'description ici ...',
              'created_at' => now(),
              'updated_at' => now()
            ],
            [
                'role' => 'Executive Manager',
                'status' => 'active',
                'description' => 'description ici ...',
                'created_at' => now(),
                'updated_at' => now()
              ],
              [
                'role' => 'Cheif officer',
                'status' => 'active',
                'description' => 'description ici ...',
                'created_at' => now(),
                'updated_at' => now()
              ]


          ];

          Role::insert($roles);
    }
}
