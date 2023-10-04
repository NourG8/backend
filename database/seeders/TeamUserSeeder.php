<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\TeamUser;

class TeamUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $team_user =  [
            [
                'team_id' => 1,
                'user_id'=> 4,
                'integration_date'=>now(),
                'is_leader'=> 1,
                'is_deleted'=> 0,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'team_id' => 1,
                'user_id'=> 5,
                'integration_date'=>now(),
                'is_leader'=> 0,
                'is_deleted'=> 0,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'team_id' => 1,
                'user_id'=>6,
                'integration_date'=>now(),
                'is_leader'=> 0,
                'is_deleted'=> 0,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'team_id' => 1,
                'user_id'=>11,
                'integration_date'=>now(),
                'is_leader'=> 0,
                'is_deleted'=> 0,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'team_id' => 1,
                'user_id'=>18,
                'integration_date'=>now(),
                'is_leader'=> 0,
                'is_deleted'=> 0,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'team_id' => 2,
                'user_id'=>4,
                'integration_date'=>now(),
                'is_leader'=> 1,
                'is_deleted'=> 0,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'team_id' => 2,
                'user_id'=>9,
                'integration_date'=>now(),
                'is_leader'=> 0,
                'is_deleted'=> 0,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'team_id' => 2,
                'user_id'=>10,
                'integration_date'=>now(),
                'is_leader'=> 0,
                'is_deleted'=> 0,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'team_id' => 3,
                'user_id'=>4,
                'integration_date'=>now(),
                'is_leader'=> 1,
                'is_deleted'=> 0,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'team_id' => 3,
                'user_id'=>7,
                'integration_date'=>now(),
                'is_leader'=> 0,
                'is_deleted'=> 0,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'team_id' => 3,
                'user_id'=>8,
                'integration_date'=>now(),
                'is_leader'=> 0,
                'is_deleted'=> 0,
                'created_at' => now(),
                'updated_at' => now()
            ],

            [
                'team_id' => 4,
                'user_id'=>13,
                'integration_date'=>now(),
                'is_leader'=> 1,
                'is_deleted'=> 0,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'team_id' => 4,
                'user_id'=>14,
                'integration_date'=>now(),
                'is_leader'=> 0,
                'is_deleted'=> 0,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'team_id' => 4,
                'user_id'=>15,
                'integration_date'=>now(),
                'is_leader'=> 0,
                'is_deleted'=> 0,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'team_id' => 4,
                'user_id'=>16,
                'integration_date'=>now(),
                'is_leader'=> 0,
                'is_deleted'=> 0,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'team_id' => 5,
                'user_id'=>12,
                'integration_date'=>now(),
                'is_leader'=> 1,
                'is_deleted'=> 0,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'team_id' => 5,
                'user_id'=>17,
                'integration_date'=>now(),
                'is_leader'=> 0,
                'is_deleted'=> 0,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'team_id' => 5,
                'user_id'=>18,
                'integration_date'=>now(),
                'is_leader'=> 0,
                'is_deleted'=> 0,
                'created_at' => now(),
                'updated_at' => now()
            ],
      ];

      TeamUser::insert($team_user);
    }
}
