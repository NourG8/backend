<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Team;
class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $teams =  [
            [
                'name' => "Equipe IT_MM",
                'description' => "Description equipe mohamed et mourad",
                'is_deleted'=> 0,
                'department_id' => 2,
                'status' => "active",
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => "Equipe IT_HH",
                'description' => "Description equipe houcem bozratti et hamdi",
                'is_deleted'=> 0,
                'department_id' => 2,
                'status' => "active",
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => "Equipe IT_MN",
                'description' => "Description equipe nour et mariem",
                'is_deleted'=> 0,
                'department_id' => 2,
                'status' => "active",
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => "Equipe CC_1",
                'description' => "Description equipe cc 1",
                'is_deleted'=> 0,
                'department_id' => 1,
                'status' => "active",
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => "Equipe CC_2",
                'description' => "Description equipe cc 2",
                'is_deleted'=> 0,
                'department_id' => 1,
                'status' => "active",
                'created_at' => now(),
                'updated_at' => now()
            ],

      ];

      Team::insert($teams);
    }
}
