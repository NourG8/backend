<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Carbon;

use App\Models\Department;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $password = Hash::make(123456789);
        $array = array(100, 200, 300);
        $arr = array('motivation 1', 'motivation 2', 'motivation 3');
        return [
            'lastName' => "Admin",
            'firstName' => "Admin",
            'sex' => "Women",
            'email' => "admin@gmail.com",
            'emailProf' => "admin@digitrends.dev",
            'address' => "Bizerte",
            'placeBirth' => "Tunis",
            'dateBirth' => Carbon::createFromFormat('d/m/Y', '01/10/2004'),
            'nationality' => "Tunisienne",
            'phone' => "+21625825825",
            'phoneEmergency' => "+21625825855",
            'FamilySituation' => "Single",
            'status' => "active",
            'nbChildren' => "0",
            'pwd_reset_admin' => 0,
            'levelStudies' => "Bac + 3",
            'specialty' => "informatique",
            'sivp' => "No",
            'durationSivp' =>  "0",
            'cin' =>  "11111111",
            'deliveryDateCin' =>  Carbon::createFromFormat('d/m/Y', '31/10/2022'),
            'deliveryPlaceCin' =>"Bizerte",
            // 'numPassport' =>"11223344",
            // 'integrationDate'=>$this->faker->date($format='Y-m-d', $max='now'),
            'integrationDate'=> now(),
            'motivation' => $arr,
            'matricule' => "12a58-8z4",
            'password' => $password,
            'image'=>'img.png',
            // 'department_id' => Department::get('id')->random(),
            'created_at' => now(),
            'updated_at' => now()
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
