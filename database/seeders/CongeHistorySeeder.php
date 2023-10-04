<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\CongeHistory;

class CongeHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $conges =  [
            [
                'id_responsable' => 4,
                'status'=>'Accepter',
                'is_rejected_prov'=> 0,
                'is_archive'=> 0,
                'level'=> 1,
                'conge_id'=> 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id_responsable' => 4,
                'status'=>'Accepter',
                'is_rejected_prov'=> 0,
                'is_archive'=> 0,
                'level'=> 1,
                'conge_id'=> 2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id_responsable' => 4,
                'status'=>'Accepter',
                'is_rejected_prov'=> 0,
                'is_archive'=> 0,
                'level'=> 1,
                'conge_id'=> 3,
                'created_at' => now(),
                'updated_at' => now()
            ],
      ];

      CongeHistory::insert($conges);
    }
}
