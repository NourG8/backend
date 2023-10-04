<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Contract;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserContract>
 */
class UserContractFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'contract_id' => Contract::get('id')->random(),
            'user_id' => User::get('id')->random(),
            'startDate' => $this->faker->date($format='Y-m-d', $max='now'),
            'salary' => 1200,
            'placeOfWork' => "Tunis",
            'startTimeWork' =>"8h",
            'endTimeWork' => "17h",
            'trialPeriod' =>"12",
            'status' =>"Draft",
            'is_deleted'=> 0,
            'raison'=> NULL,
            'date_status' =>"2022-01-01",
            'OnlyPhysical' => 0,
            // 'fileContract' => "17h",
            // 'status' => "active",
            'created_at' => now(),
            'updated_at' => now()
        ];
    }
}
