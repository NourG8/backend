<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Conge;

class CongeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $conge =  [
            [
                'type' => "Congé sans solde(impayé)",
                'raison' => "Raison du conge",
                'dates'=> '["2022-11-03","2022-11-02","2022-11-01","2022-11-04","2022-11-08","2022-11-09","2022-11-11","2022-11-10","2022-12-07","2022-12-08","2022-12-09"]',
                'status'=>'Envoyé',
                'level'=> 2,
                'date' => now(),
                'is_deleted'=> 0,
                'user_id'=> 4,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'type' => "Congé de l'année précédente",
                'raison' => null,
                'dates'=> '["2022-11-20","2022-11-15"]',
                'status'=>'Envoyé',
                'level'=> 1,
                'date' => now(),
                'is_deleted'=> 0,
                'user_id'=>6,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'type' => "Congé de l'année actuelle",
                'raison' => "Raison du conge",
                'dates'=> '["2022-09-08","2022-09-16","2022-10-08","2022-10-07"]',
                'status'=>'Envoyé',
                'level'=> 1,
                'date' => now(),
                'is_deleted'=> 0,
                'user_id'=>10,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'type' => "Congé de l'année actuelle",
                'raison' => "Raison du conge",
                'dates'=> '["2022-09-08","2022-09-16","2022-10-08","2022-10-07"]',
                'status'=>'Envoyé',
                'level'=> 1,
                'date' => now(),
                'is_deleted'=> 0,
                'user_id'=>18,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'type' => "Congé sans solde(impayé)",
                'raison' => "Raison du conge",
                'dates'=> '["2022-10-15","2022-11-18","2022-12-29","2022-12-30"]',
                'status'=>'Envoyé',
                'level'=> 1,
                'date' => now(),
                'is_deleted'=> 0,
                'user_id'=>7,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'type' => "Congé de l'année actuelle",
                'raison' => "Raison du conge",
                'dates'=> '["2022-09-08","2022-09-16","2022-10-08","2022-10-07"]',
                'status'=>'Envoyé',
                'level'=> 1,
                'date' => now(),
                'is_deleted'=> 0,
                'user_id'=>5,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        Conge::insert($conge);
    }
}
