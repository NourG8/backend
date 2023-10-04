<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\HistoryRemoteWork;


class HistoryRemoteWorkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $teletravail =  [ 
            // [
            //     'id_responsable' => 4,
            //     'status'=>'Accepter',
            //     'is_rejected_prov'=> 0,
            //     'level'=> 1,
            //     'teletravail_id'=> 1,
            //     'created_at' => now(),
            //     'updated_at' => now()
            // ],
            // [
            //     'id_responsable' => 4,
            //     'status'=>'Accepter',
            //     'is_rejected_prov'=> 0,
            //     'level'=> 1,
            //     'teletravail_id'=> 2,
            //     'created_at' => now(),
            //     'updated_at' => now()
            // ],
            // [
            //     'id_responsable' => 4,
            //     'status'=>'Accepter',
            //     'is_rejected_prov'=> 0,
            //     'level'=> 1,
            //     'teletravail_id'=> 3,
            //     'created_at' => now(),
            //     'updated_at' => now()
            // ],
      ];
      HistoryRemoteWork::insert($teletravail);
    }
}
