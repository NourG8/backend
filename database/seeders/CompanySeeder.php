<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Company;

class CompanySeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $company = [
            'name'=> 'Digitrends',
            'country'=> 'Tunis',
            'email'=> 'digitrends@dev.com',
            'phone'=> "+216 53 740 917",
            'creation_date'=> now(),
            'status'=> 'Active',
            'logo'=>'logo.png',
            'description'=> 'The name’s John Deo. I am a tireless seeker of knowledge, occasional purveyor of wisdom and also, coincidentally, a graphic designer. Algolia helps businesses across industries quickly create relevant 😎, scaLabel 😀, and lightning 😍 fast search and discovery experiences.',
            'min_cin'=> '8',
            'max_cin'=> '8',
            'nationality'=>'tunisienne',
            'min_passport'=> '8',
            'max_passport'=> '8',
            'max_teletravail' => 1,
            'typeTeletravail'=> 'Flexi',
            'regimeSocial'=> '[{"regimeSocial":"Cnss","type":"number"}]',
            'color'=> '#2C6B8E',
            'color2'=> '#89B9BE',
            'created_at' => now(),
            'updated_at' => now()
        ];

        Company::insert($company);
    }
}
