<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Department;
use App\Models\Position;
use App\Models\Role;
class PositionSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $position =  [
            [
                'jobName' => 'Office worker',
                'status' => 'active',
                'description' => 'Maecenas ullamcorper, dui et placerat feugiat, eros pede varius nisi, condimentum viverra felis nunc et lorem. Duis vel nibh at velit scelerisque suscipit. Praesent blandit laoreet nibh. Aenean posuere, tortor sed cursus feugiat, nunc augue blandit nunc, eu sollicitudin urna dolor sagittis lacus. Etiam sollicitudin, ipsum eu pulvinar rutrum, tellus ipsum laoreet sapien, quis venenatis ante odio sit amet eros.',
                'title' => "This is a  ..",
                'role_id' => 3,
                'created_at' => now(),
                'updated_at' => now()
              ],
               [
                'jobName' => 'Computer programmer',
                'status' => 'active',
                'description' => 'Maecenas ullamcorper, dui et placerat feugiat, eros pede varius nisi, condimentum viverra felis nunc et lorem. Duis vel nibh at velit scelerisque suscipit. Praesent blandit laoreet nibh. Aenean posuere, tortor sed cursus feugiat, nunc augue blandit nunc, eu sollicitudin urna dolor sagittis lacus. ',
                'title' => "This is a  ..",
                'role_id' => 2,
                'created_at' => now(),
                'updated_at' => now()
              ],
              [
               'jobName' => 'Administrateur',
               'status' => 'active',
               'description' => 'Maecenas ullamcorper, dui et placerat feugiat, eros pede varius nisi. Duis vel nibh at velit scelerisque suscipit. Praesent blandit laoreet nibh. Aenean posuere, tortor sed cursus feugiat, nunc augue blandit nunc, eu sollicitudin urna dolor sagittis lacus. Etiam sollicitudin, ipsum eu pulvinar rutrum, tellus ipsum laoreet sapien, quis venenatis ante odio sit amet eros.',
               'title' => "This is a ..",
               'role_id' => 1,
               'created_at' => now(),
               'updated_at' => now()
             ],
             [
                'jobName' => 'Human ressources',
                'status' => 'active',
                'description' => 'Maecenas ullamcorper, dui et placerat feugiat, eros pede varius nisi. Duis vel nibh at velit scelerisque suscipit. Praesent blandit laoreet nibh. Aenean posuere, tortor sed cursus feugiat, nunc augue blandit nunc, eu sollicitudin urna dolor sagittis lacus. Etiam sollicitudin, ipsum eu pulvinar rutrum, tellus ipsum laoreet sapien, quis venenatis ante odio sit amet eros.',
                'title' => "This is a  ..",
                'role_id' => 4,
                'created_at' => now(),
                'updated_at' => now()
              ],
              [
                'jobName' => 'Manager',
                'status' => 'active',
                'description' => 'Maecenas ullamcorper, dui et placerat feugiat, eros pede varius nisi. Duis vel nibh at velit scelerisque suscipit. Praesent blandit laoreet nibh. Aenean posuere, tortor sed cursus feugiat, nunc augue blandit nunc, eu sollicitudin urna dolor sagittis lacus. Etiam sollicitudin, ipsum eu pulvinar rutrum, tellus ipsum laoreet sapien, quis venenatis ante odio sit amet eros.',
                'title' => "This is a  ..",
                'role_id' => 3,
                'created_at' => now(),
                'updated_at' => now()
              ],
              [
                'jobName' => 'Gerant',
                'status' => 'active',
                'description' => 'Maecenas ullamcorper, dui et placerat feugiat, eros pede varius nisi. Duis vel nibh at velit scelerisque suscipit. Praesent blandit laoreet nibh. Aenean posuere, tortor sed cursus feugiat, nunc augue blandit nunc, eu sollicitudin urna dolor sagittis lacus. Etiam sollicitudin, ipsum eu pulvinar rutrum, tellus ipsum laoreet sapien, quis venenatis ante odio sit amet eros.',
                'title' => "This is a  ..",
                'role_id' => 7,
                'created_at' => now(),
                'updated_at' => now()
              ],
      ];

        Position::insert($position);
    }
}
