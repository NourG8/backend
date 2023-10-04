<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Contract;

class ContractSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $contracts =  [
            [
                'type' => "CDD",
                'file' => null,
                'isDeleted'=> 0,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'type' => "CDI",
                'file' => "Contrat_CDI.docx",
                'isDeleted'=> 0,
                'created_at' => now(),
                'updated_at' => now()
            ],
      ];

      Contract::insert($contracts);
    }
}
